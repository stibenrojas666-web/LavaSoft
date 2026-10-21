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
    public function __construct(
        private AgendaRepository $agendaRepository,
        private AgendaServicioRepository $agendaServicioRepository,
        private precioServicioRepository $precioServicioRepository,
        private VehiculoRespository $vehiculoRepository
    ) {
    }

    public function getAll()
    {
        return $this->agendaRepository->getAll();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return $this->agendaRepository->buscar($texto, $filtros);
    }

    public function buscarParaCalendario(string $texto, array $filtros, string $desde, string $hasta)
    {
        return $this->agendaRepository->buscarParaCalendario($texto, $filtros, $desde, $hasta);
    }

    public function keep(array $datos)
    {
        $lineas = $this->aplanarVehiculos($datos['vehiculos']);
        unset($datos['vehiculos']);

        return DB::transaction(function () use ($datos, $lineas) {
            $this->validarHorarioYDisponibilidad($datos, $lineas);
            $agenda = $this->agendaRepository->keep($datos);
            $this->guardarLineas($agenda->id, $lineas);

            return $this->agendaRepository->findById((int) $agenda->id);
        });
    }

    /** Calcula el precio desde el vehículo y el servicio; nunca confía en el precio del formulario. */
    public function calcularPrecioServicio(int $vehiculoId, int $servicioId, mixed $precioAcordado = null, bool $precioAcordadoActivo = false): string
    {
        $vehiculo = $this->vehiculoRepository->finById($vehiculoId);
        $configuracion = $this->precioServicioRepository->buscarConfiguracion(
            $servicioId,
            (int) $vehiculo->tipoVehiculoId
        );

        if ($configuracion === null) {
            throw ValidationException::withMessages([
                'servicioId' => 'No hay un precio registrado para este servicio y tipo de vehículo.',
            ]);
        }

        if ($configuracion->modalidad === 'convenir' || $precioAcordadoActivo) {
            if (!is_numeric($precioAcordado) || (float) $precioAcordado < 0.01 || (float) $precioAcordado > 99999999.99) {
                throw ValidationException::withMessages([
                    'precioAcordado' => 'Escribe el precio acordado para este servicio.',
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

    public function vehiculoPerteneceACliente(int $vehiculoId, int $clienteId): bool
    {
        return $this->agendaRepository->vehiculoPerteneceACliente($vehiculoId, $clienteId);
    }

    public function validarVehiculoDisponible(int $vehiculoId, string $fecha, string $hora, ?int $agendaId = null, string $campoError = 'vehiculoId'): void
    {
        if ($this->agendaRepository->vehiculoOcupado($vehiculoId, $fecha, $hora, $agendaId)) {
            throw ValidationException::withMessages([
                $campoError => 'Ese vehículo ya tiene una cita en esa fecha y hora.',
            ]);
        }
    }

    public function update(int $id, array $datos)
    {
        $lineas = $this->aplanarVehiculos($datos['vehiculos']);
        unset($datos['vehiculos']);

        return DB::transaction(function () use ($id, $datos, $lineas) {
            $this->validarHorarioYDisponibilidad($datos, $lineas, $id);
            $agenda = $this->agendaRepository->update($id, $datos);
            $this->agendaRepository->quitarLineas($id);
            $this->guardarLineas($id, $lineas);

            return $this->agendaRepository->findById($id);
        });
    }

    /** Convierte los bloques del formulario en filas vehículo-servicio para la tabla intermedia. */
    private function aplanarVehiculos(array $vehiculos): array
    {
        $lineas = [];
        $vehiculosIncluidos = [];

        foreach ($vehiculos as $indiceVehiculo => $bloque) {
            $vehiculoId = (int) $bloque['vehiculoId'];

            if (isset($vehiculosIncluidos[$vehiculoId])) {
                throw ValidationException::withMessages([
                    "vehiculos.{$indiceVehiculo}.vehiculoId" => 'No repitas un vehículo en dos bloques de la misma cita.',
                ]);
            }

            $vehiculosIncluidos[$vehiculoId] = true;

            foreach ($bloque['servicios'] as $indiceServicio => $servicio) {
                $lineas[] = [
                    'vehiculoId' => $vehiculoId,
                    'servicioId' => (int) $servicio['servicioId'],
                    'precioAcordado' => $servicio['precioAcordado'] ?? null,
                    'precioAcordadoActivo' => filter_var($servicio['precioAcordadoActivo'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'campoVehiculo' => "vehiculos.{$indiceVehiculo}.vehiculoId",
                    'campoServicio' => "vehiculos.{$indiceVehiculo}.servicios.{$indiceServicio}.servicioId",
                    'campoPrecio' => "vehiculos.{$indiceVehiculo}.servicios.{$indiceServicio}.precioAcordado",
                ];
            }
        }

        return $lineas;
    }

    /** Valida fecha, empleado y todos los vehículos de las líneas antes de escribir. */
    private function validarHorarioYDisponibilidad(array $datos, array $lineas, ?int $agendaId = null): void
    {
        $esCancelada = ($datos['estado'] ?? null) === 'cancelada';
        $fecha = (string) $datos['fecha'];
        $hora = (string) $datos['hora'];
        $paresServicio = [];
        $vehiculosRevisados = [];

        foreach ($lineas as $linea) {
            $vehiculoId = (int) $linea['vehiculoId'];
            $servicioId = (int) $linea['servicioId'];

            if (!$this->agendaRepository->vehiculoPerteneceACliente($vehiculoId, (int) $datos['clienteId'])) {
                throw ValidationException::withMessages([
                    $linea['campoVehiculo'] => 'El vehículo seleccionado no pertenece al cliente de esta cita.',
                ]);
            }

            $par = $vehiculoId . ':' . $servicioId;
            if (isset($paresServicio[$par])) {
                throw ValidationException::withMessages([
                    $linea['campoServicio'] => 'No repitas un servicio dentro del mismo vehículo.',
                ]);
            }
            $paresServicio[$par] = true;

            if (!$esCancelada && !isset($vehiculosRevisados[$vehiculoId])) {
                $this->validarVehiculoDisponible($vehiculoId, $fecha, $hora, $agendaId, $linea['campoVehiculo']);
                $vehiculosRevisados[$vehiculoId] = true;
            }
        }

        if ($esCancelada) {
            return;
        }

        if (!HorarioLavadero::permite($fecha, $hora)) {
            throw ValidationException::withMessages([
                'hora' => 'La hora está fuera del horario de atención del lavadero para ese día.',
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

    private function guardarLineas(int $agendaId, array $lineas): void
    {
        foreach ($lineas as $indice => $linea) {
            $vehiculoId = (int) $linea['vehiculoId'];
            $servicioId = (int) $linea['servicioId'];

            try {
                $precio = $this->calcularPrecioServicio(
                    $vehiculoId,
                    $servicioId,
                    $linea['precioAcordado'] ?? null,
                    $linea['precioAcordadoActivo'] ?? false
                );
            } catch (ValidationException $error) {
                $errores = $error->errors();
                $campo = array_key_exists('precioAcordado', $errores) ? 'precioAcordado' : 'servicioId';
                $mensaje = collect($errores)->flatten()->first();
                $campoError = $campo === 'precioAcordado' ? $linea['campoPrecio'] : $linea['campoServicio'];
                throw ValidationException::withMessages([
                    $campoError => $mensaje ?? 'No se pudo calcular el precio del servicio.',
                ]);
            }

            $this->agendaServicioRepository->keep([
                'agendaId' => $agendaId,
                'vehiculoId' => $vehiculoId,
                'servicioId' => $servicioId,
                'Precio' => $precio,
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
