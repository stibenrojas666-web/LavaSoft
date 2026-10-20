<?php

namespace App\Services;

use App\Repositories\AgendaRepository;
use App\Repositories\AgendaServicioRepository;
use App\Repositories\precioServicioRepository;
use App\Repositories\VehiculoRespository;
use App\Support\HorarioLavadero;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AgendaService
{
    private AgendaRepository $agendaRepository;
    private AgendaServicioRepository $agendaServicioRepository;
    private precioServicioRepository $precioServicioRepository;
    private VehiculoRespository $vehiculoRepository;

    public function __construct(
        AgendaRepository $agendaRepository,
        AgendaServicioRepository $agendaServicioRepository,
        precioServicioRepository $precioServicioRepository,
        VehiculoRespository $vehiculoRepository
    ) {
        $this->agendaRepository = $agendaRepository;
        $this->agendaServicioRepository = $agendaServicioRepository;
        $this->precioServicioRepository = $precioServicioRepository;
        $this->vehiculoRepository = $vehiculoRepository;
    }

    public function getAll()
    {
        return $this->agendaRepository->getAll();
    }

    public function keep(array $datos)
    {
        $this->validarHorarioYDisponibilidad($datos);

        $servicios = $datos['servicios'];
        $preciosAcordados = $datos['precios_acordados'] ?? [];
        unset($datos['servicios'], $datos['precios_acordados']);

        return DB::transaction(function () use ($datos, $servicios, $preciosAcordados) {
            $agenda = $this->agendaRepository->keep($datos);

            $tipoVehiculoId = $this->vehiculoRepository
                ->finById($agenda->vehiculoId)
                ->tipoVehiculoId;

            foreach ($servicios as $servicioId) {
                $configuracionPrecio = $this->precioServicioRepository->buscarConfiguracion(
                    (int) $servicioId,
                    (int) $tipoVehiculoId
                );

                if ($configuracionPrecio === null) {
                    throw ValidationException::withMessages([
                        'servicios' => 'Uno de los servicios no tiene precio para ese tipo de vehículo.',
                    ]);
                }

                if ($configuracionPrecio->modalidad === 'convenir') {
                    $precio = $preciosAcordados[$servicioId] ?? null;
                    if (!is_numeric($precio) || (float) $precio < 0.01 || (float) $precio > 99999999.99) {
                        throw ValidationException::withMessages([
                            "precios_acordados.{$servicioId}" => 'Escribe el precio acordado con el cliente.',
                        ]);
                    }
                } else {
                    $precio = $configuracionPrecio->precio;
                    if ($precio === null) {
                        throw ValidationException::withMessages([
                            'servicios' => 'Uno de los servicios no tiene un precio base configurado.',
                        ]);
                    }
                }

                $this->agendaServicioRepository->keep([
                    'agendaId' => $agenda->id,
                    'servicioId' => $servicioId,
                    'Precio' => $precio,
                ]);
            }

            return $agenda;
        });
    }

    /** Calcula el precio a guardar para un servicio agregado desde el CRUD. */
    public function calcularPrecioServicio(int $agendaId, int $servicioId, mixed $precioAcordado = null): string
    {
        $tipoVehiculoId = $this->agendaRepository->tipoVehiculoDeAgenda($agendaId);
        $configuracion = $this->precioServicioRepository->buscarConfiguracion($servicioId, $tipoVehiculoId);

        if ($configuracion === null) {
            throw ValidationException::withMessages([
                'servicioId' => 'No hay un precio registrado para este servicio y tipo de vehículo.',
            ]);
        }

        if ($configuracion->modalidad === 'convenir') {
            if (!is_numeric($precioAcordado) || (float) $precioAcordado < 0.01 || (float) $precioAcordado > 99999999.99) {
                throw ValidationException::withMessages([
                    'Precio' => 'Escribe el precio acordado para este servicio.',
                ]);
            }

            return (string) $precioAcordado;
        }

        if ($configuracion->precio === null) {
            throw ValidationException::withMessages([
                'servicioId' => 'El servicio no tiene un precio base configurado.',
            ]);
        }

        return (string) $configuracion->precio;
    }

    public function findById(int $id)
    {
        return $this->agendaRepository->findById($id);
    }

    public function update(int $id, array $datos)
    {
        $this->validarHorarioYDisponibilidad($datos, $id);

        return $this->agendaRepository->update($id, $datos);
    }

    /** Comprueba reglas de negocio de agenda antes de guardar o mover una cita. */
    private function validarHorarioYDisponibilidad(array $datos, ?int $agendaId = null): void
    {
        if (($datos['estado'] ?? null) === 'cancelada') {
            return;
        }

        $fecha = (string) $datos['fecha'];
        $hora = (string) $datos['hora'];

        if (!HorarioLavadero::permite($fecha, $hora)) {
            throw ValidationException::withMessages([
                'hora' => 'La hora está fuera del horario de atención del lavadero para ese día.',
            ]);
        }

        if ($this->agendaRepository->vehiculoOcupado((int) $datos['vehiculoId'], $fecha, $hora, $agendaId)) {
            throw ValidationException::withMessages([
                'vehiculoId' => 'Ese vehículo ya tiene una cita en esa fecha y hora.',
            ]);
        }

        $empleadoId = isset($datos['empleadoId']) ? (int) $datos['empleadoId'] : null;
        if ($empleadoId === null) {
            return;
        }

        if (!$this->agendaRepository->empleadoActivo($empleadoId)) {
            throw ValidationException::withMessages([
                'empleadoId' => 'Solo se puede asignar una cita a un empleado activo.',
            ]);
        }

        $dias = [1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'];
        $dia = $dias[Carbon::parse($fecha)->dayOfWeekIso];
        $horaCompleta = $hora . ':00';

        if (!$this->agendaRepository->empleadoTieneTurno($empleadoId, $dia, $horaCompleta)) {
            throw ValidationException::withMessages([
                'empleadoId' => 'El empleado no tiene un turno que cubra ese día y esa hora.',
            ]);
        }

        if ($this->agendaRepository->empleadoOcupado($empleadoId, $fecha, $hora, $agendaId)) {
            throw ValidationException::withMessages([
                'empleadoId' => 'Ese empleado ya tiene una cita en esa fecha y hora.',
            ]);
        }
    }

    public function delete(int $id): bool
    {
        if ($this->agendaRepository->tieneDependencias($id)) {
            return false;
        }

        return $this->agendaRepository->delete($id) > 0;
    }
}
