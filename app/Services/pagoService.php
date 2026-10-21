<?php

namespace App\Services;

use App\Mail\FacturaCitaMail;
use App\Models\pago;
use App\Repositories\PagoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class PagoService
{
    public function __construct(private PagoRepository $pagoRepository)
    {
    }

    public function obtenerFactura(int $agendaId): array
    {
        $agenda = $this->pagoRepository->agendaParaFactura($agendaId);
        $total = $this->calcularTotal($agendaId);

        return compact('agenda', 'total');
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

    public function registrarPago(int $agendaId, array $datos): pago
    {
        return DB::transaction(function () use ($agendaId, $datos) {
            $agenda = $this->pagoRepository->bloquearAgendaConfirmadaSinPago($agendaId);

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

            $pago = $this->pagoRepository->crear([
                'agendaId' => $agendaId,
                'Monto' => $total,
                'MetodoPago' => $datos['MetodoPago'],
                'FechaPago' => now(),
                'RequerirFactura' => (bool) ($datos['RequerirFactura'] ?? false),
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
            Mail::to($correo)->send(new FacturaCitaMail($agenda, $this->calcularTotal($agendaId)));
        } catch (Throwable $error) {
            report($error);

            throw ValidationException::withMessages([
                'correo' => 'No se pudo enviar el correo. El pago sigue registrado; intenta nuevamente más tarde.',
            ]);
        }
    }
}
