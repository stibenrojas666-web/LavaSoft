<?php

namespace App\Services;

use App\Mail\FacturaCitaMail;
use App\Models\Pago;
use App\Repositories\Contracts\EmpleadoRepositoryInterface as EmpleadoRepository;
use App\Repositories\Contracts\PagoRepositoryInterface as PagoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;


class PagoService
{
    public function __construct(
        private PagoRepository $pagoRepository,
        private EmpleadoRepository $empleadoRepository
    )
    {
    }

    public function obtenerFactura(int $agendaId): array
    {
        $agenda = $this->pagoRepository->agendaParaFactura($agendaId);
        $total = $this->calcularTotal($agendaId);
        $serviciosPorVehiculo = $this->detallePorVehiculo($agenda);
        $urlWhatsApp = $this->enlaceWhatsAppFactura($agenda);

        return compact('agenda', 'total', 'serviciosPorVehiculo', 'urlWhatsApp');
    }

    public function obtenerFacturaPublica(int $agendaId): array
    {
        $datosFactura = $this->obtenerFactura($agendaId);

        if (! $datosFactura['agenda']->pago) {
            throw (new ModelNotFoundException())->setModel(\App\Models\Agenda::class, $agendaId);
        }

        return $datosFactura;
    }


    public function detallePorVehiculo(object $agenda): array
    {
        return $agenda->agendaServicios->groupBy('vehiculoId')->map(fn ($lineas) => [
            'vehiculo' => $lineas->first()->vehiculo,
            'lineas' => $lineas,
            'subtotal' => (float) $lineas->sum(fn ($linea) => (float) $linea->Precio),
        ])->values()->all();
    }

    private function enlaceWhatsAppFactura(object $agenda): ?string
    {
        if (! $agenda->pago) {
            return null;
        }

        $telefono = preg_replace('/\D+/', '', (string) ($agenda->cliente?->telefonoCliente ?? ''));
        if (strlen($telefono) === 10) {
            $telefono = '57'.$telefono;
        }
        if (preg_match('/^57[0-9]{10}$/', $telefono) !== 1) {
            return null;
        }

        $enlaceFactura = URL::temporarySignedRoute('factura.publica', now()->addDays(7), ['agendaId' => $agenda->id]);
        $nombreCliente = $agenda->cliente?->nombreCliente ?? 'cliente';
        $nombreLavadero = config('lavadero.nombre', 'LavaSoft');
        $monto = number_format((float) $agenda->pago->Monto, 0, ',', '.');
        $mensaje = "Hola {$nombreCliente}, gracias por visitarnos en {$nombreLavadero}.\n\n"
            .'El total de tu servicio fue: $'.$monto.".\n\n"
            .'Puedes consultar tu factura, imprimirla o guardarla como PDF en el siguiente enlace:'
            ."\n\n{$enlaceFactura}\n\n¡Gracias por preferirnos!";

        return 'https://wa.me/'.$telefono.'?text='.rawurlencode($mensaje);
    }

    public function calcularTotal(int $agendaId): float
    {
        return $this->pagoRepository->totalServiciosDeAgenda($agendaId);
    }

    public function historialPagos()
    {
        return $this->pagoRepository->historialPagos();
    }

    public function buscarHistorialPagos(string $texto, array $filtros = [])
    {
        return $this->pagoRepository->historialPagos($texto, $filtros);
    }

    public function prepararHistorial(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['metodo', 'desde', 'hasta']));
        $pagos = $this->buscarHistorialPagos($buscar, $filtros);

        foreach ($pagos as $pago) {
            $agenda = $pago->agenda;
            if ($agenda === null) {
                continue;
            }

            $nombreCliente = trim(($agenda->cliente->nombreCliente ?? '').' '.($agenda->cliente->apellidoCliente ?? ''));
            $placas = $agenda->agendaServicios
                ->pluck('vehiculo.placa')
                ->filter()
                ->unique()
                ->implode(', ');

            $agenda->setAttribute('clienteNombreCompleto', $nombreCliente ?: 'Sin cliente');
            $agenda->setAttribute('placasVehiculosResumen', $placas ?: 'Sin vehículo');
        }

        return [
            'pagos' => $pagos,
            'buscar' => $buscar,
            'filtros' => $filtros,
        ];
    }

    public function registrarPago(int $agendaId, array $datos): Pago
{
    return DB::transaction(function () use ($agendaId, $datos) {
        $agenda = $this->pagoRepository
            ->bloquearAgendaConfirmadaSinPago($agendaId);

        if (!$agenda) {
            throw ValidationException::withMessages([
                'pago' => 'Solo se puede pagar una cita confirmada que todavía no tenga un pago.',
            ]);
        }

        $total = $this->calcularTotal($agendaId);

        if ($total <= 0 || $total > 99999999.99) {
            throw ValidationException::withMessages([
                'pago' => 'La cita debe tener un total válido mayor que cero para registrar el pago.',
            ]);
        }

        $empleadoAsignado = $agenda->empleadoId
            ? $this->empleadoRepository->buscarParaPago((int) $agenda->empleadoId)
            : null;

        $porcentaje = (float) (
            $empleadoAsignado?->porcentajeComision ?? 0
        );

        $comision = round($total * ($porcentaje / 100), 2);

        $pago = $this->pagoRepository->crear([
            'agendaId' => $agendaId,
            'empleadoId' => $empleadoAsignado?->id,
            'Monto' => $total,
            'MetodoPago' => $datos['MetodoPago'],
            'FechaPago' => now(),
            'RequerirFactura' => (bool) ($datos['RequerirFactura'] ?? false),
            'porcentajeComision' => $porcentaje,
            'valorComision' => $comision,
        ]);

        $this->pagoRepository->marcarTerminada($agendaId);

        return $pago;
    });
}

    public function enviarFacturaPorCorreo(int $agendaId): void
    {
        $agenda = $this->pagoRepository->agendaParaFactura($agendaId);

        if (!$agenda->pago) {
            throw ValidationException::withMessages([
                'correo' => 'Registra el pago antes de enviar la factura por correo.',
            ]);
        }

        $correo = trim((string) $agenda->cliente?->emailCliente);
        if ($correo === '') {
            throw ValidationException::withMessages([
                'correo' => 'El cliente no tiene un correo registrado. Agrega su correo para enviarle la factura.',
            ]);
        }

        try {
            Mail::to($correo)->send(new FacturaCitaMail($agenda, $this->calcularTotal($agendaId), $this->detallePorVehiculo($agenda)));
        } catch (Throwable $error) {
            report($error);

            throw ValidationException::withMessages([
                'correo' => 'No se pudo enviar el correo. El pago sigue registrado; intenta nuevamente más tarde.',
            ]);
        }
    }
}
