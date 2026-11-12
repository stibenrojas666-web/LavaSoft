<?php

namespace App\Services;

use App\Repositories\Contracts\AgendaRepositoryInterface as AgendaRepository;
use App\Repositories\Contracts\AgendaServicioRepositoryInterface as AgendaServicioRepository;
use App\Repositories\Contracts\PrecioServicioRepositoryInterface as PrecioServicioRepository;
use App\Repositories\Contracts\VehiculoRepositoryInterface as VehiculoRespository;
use App\Support\HorarioLavadero;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AgendaService
{
    public function __construct(
        private AgendaRepository $agendaRepository,
        private AgendaServicioRepository $agendaServicioRepository,
        private PrecioServicioRepository $precioServicioRepository,
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

    /** Prepara filtros, fechas y colecciones para la pantalla de agenda. */
    public function prepararIndice(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['estado', 'desde', 'hasta', 'orden']));
        $vista = $datos['vista'] ?? 'calendario';
        $mes = $datos['mes'] ?? now()->format('Y-m');
        $primerDia = Carbon::createFromFormat('!Y-m', $mes)->startOfMonth();
        $fechaSeleccionada = Carbon::parse($datos['fecha'] ?? ($primerDia->isCurrentMonth()
            ? today()->toDateString()
            : $primerDia->toDateString()));

        if (! $fechaSeleccionada->isSameMonth($primerDia)) {
            $fechaSeleccionada = $primerDia->copy();
        }

        $fechaCalendario = $fechaSeleccionada->toDateString();
        $inicioCalendario = $primerDia->copy()->startOfWeek(Carbon::MONDAY);
        $finCalendario = $primerDia->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $mesAnterior = $primerDia->copy()->subMonth()->format('Y-m');
        $mesSiguiente = $primerDia->copy()->addMonth()->format('Y-m');
        $parametrosCalendario = array_merge($datos, ['vista' => 'calendario']);
        $agenda = $this->buscar($buscar, $filtros);
        $citasCalendario = $vista === 'calendario'
            ? $this->buscarParaCalendario(
                $buscar,
                array_intersect_key($datos, array_flip(['estado', 'desde', 'hasta'])),
                $primerDia->toDateString(),
                $primerDia->copy()->endOfMonth()->toDateString()
            )
            : collect();
        $citasPorDia = $citasCalendario->groupBy(
            fn ($cita) => Carbon::parse($cita->fecha)->format('Y-m-d')
        );
        $citasDiaSeleccionado = $citasPorDia->get($fechaCalendario, collect())->sortBy('hora');

        $this->agregarResumenVehiculos($agenda->getCollection());
        $this->agregarResumenVehiculos($citasCalendario);

        return compact(
            'agenda', 'buscar', 'filtros', 'vista', 'mes', 'primerDia', 'fechaCalendario',
            'fechaSeleccionada', 'inicioCalendario', 'finCalendario', 'mesAnterior',
            'mesSiguiente', 'parametrosCalendario', 'citasCalendario', 'citasPorDia', 'citasDiaSeleccionado'
        );
    }

    private function agregarResumenVehiculos(iterable $citas): void
    {
        foreach ($citas as $cita) {
            $vehiculos = $cita->agendaServicios
                ->pluck('vehiculo')
                ->filter()
                ->unique('id')
                ->values();

            $cita->setAttribute('vehiculosParaVista', $vehiculos);
            $cita->setAttribute(
                'placasVehiculosParaVista',
                $vehiculos->pluck('placa')->filter()->implode(', ')
            );
        }
    }

    /** Prepara los servicios iniciales del formulario fuera de la plantilla Blade. */
    public function bloquesIniciales(?object $agenda, ?array $bloquesAnteriores, array $serviciosPrecios): array
    {
        if ($bloquesAnteriores !== null) {
            return $bloquesAnteriores ?: [['vehiculoId' => '', 'servicios' => [['servicioId' => '', 'precioAcordado' => '']]]];
        }

        if ($agenda === null) {
            return [['vehiculoId' => '', 'servicios' => [['servicioId' => '', 'precioAcordado' => '']]]];
        }

        $catalogo = collect($serviciosPrecios);

        return $agenda->agendaServicios->groupBy('vehiculoId')->map(function ($lineas) use ($catalogo) {
            return [
                'vehiculoId' => $lineas->first()->vehiculoId,
                'servicios' => $lineas->map(function ($linea) use ($catalogo) {
                    $servicio = $catalogo->firstWhere('id', $linea->servicioId);
                    $configuracion = data_get($servicio, 'precios.'.$linea->vehiculo->tipoVehiculoId);

                    return [
                        'servicioId' => $linea->servicioId,
                        'precioAcordado' => $linea->Precio,
                        'precioAcordadoActivo' => ($configuracion['modalidad'] ?? null) !== 'convenir'
                            && isset($configuracion['precio'])
                            && (float) $linea->Precio !== (float) $configuracion['precio'],
                    ];
                })->values()->all(),
            ];
        })->values()->all();
    }

    public function puedeEditar(object $agenda): bool
    {
        return $agenda->estado !== 'terminada';
    }

    public function vehiculosDeAgenda(object $agenda): array
    {
        return $agenda->agendaServicios->pluck('vehiculoId')->unique()->values()->all();
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

    /**Aca se calcula el precio desde el vehículo y el servicio; nunca confía en el precio del formulario. */
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

    public function calcularDuracionServicio(int $vehiculoId, int $servicioId): int
{
    $vehiculo = $this->vehiculoRepository->finById($vehiculoId);

    $configuracion = $this->precioServicioRepository->buscarConfiguracion(
        $servicioId,
        (int) $vehiculo->tipoVehiculoId
    );

    if ($configuracion === null || (int) $configuracion->duracionMinutos < 1) {
        throw ValidationException::withMessages([
            'servicioId' => 'No hay una duración válida configurada para este servicio y tipo de vehículo.',
        ]);
    }

    return (int) $configuracion->duracionMinutos;
}

    public function findById(int $id)
    {
        return $this->agendaRepository->findById($id);
    }

    
/**
 * aca Valida la disponibilidad de una cita usando todas sus líneas de servicios.
 */
public function validarDisponibilidadDeServicios(int $agendaId, array $lineas): void
{
    $agenda = $this->agendaRepository->findById($agendaId);

    $lineasValidadas = [];

    foreach ($lineas as $indice => $linea) {
        $lineasValidadas[] = [
            'vehiculoId' => (int) $linea['vehiculoId'],
            'servicioId' => (int) $linea['servicioId'],
            'campoVehiculo' => $linea['campoVehiculo'] ?? "vehiculoId",
            'campoServicio' => $linea['campoServicio'] ?? "servicioId",
            'campoPrecio' => $linea['campoPrecio'] ?? "Precio",
        ];
    }

    $datos = [
        'clienteId' => (int) $agenda->clienteId,
        'empleadoId' => $agenda->empleadoId,
        'fecha' => (string) $agenda->fecha,
        'hora' => (string) $agenda->hora,
        'estado' => $agenda->estado,
    ];

    $this->validarHorarioYDisponibilidad(
        $datos,
        $lineasValidadas,
        $agendaId
    );
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
             $actual = $this->agendaRepository->findById($id);
                if ($actual->estado === 'terminada' || $actual->pago) {
                    throw ValidationException::withMessages([
                        'estado' => 'Una cita terminada o pagada ya no se puede modificar.',
                    ]);
                }
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
        $minutosNueva = $this->minutosDeLineas($lineas);
        $citasDelDia = $esCancelada ? collect() : $this->agendaRepository->citasActivasDelDia($fecha, $agendaId);

        foreach ($lineas as $linea) {
            $vehiculoId = (int) $linea['vehiculoId'];
            $servicioId = (int) $linea['servicioId'];

            if (!$this->agendaRepository->vehiculoPerteneceACliente($vehiculoId, (int) $datos['clienteId'])) {
                throw ValidationException::withMessages([
                    $linea['campoVehiculo'] => 'El vehículo seleccionado no pertenece al cliente de esta cita.',
                ]);
            }
            if (!$esCancelada) {
                if (!$this->agendaRepository->vehiculoActivo($vehiculoId)) {
                    throw ValidationException::withMessages([
                        $linea['campoVehiculo'] => 'El vehículo seleccionado está inactivo.',
                    ]);
                }
                if (!$this->agendaRepository->servicioActivo($servicioId)) {
                    throw ValidationException::withMessages([
                        $linea['campoServicio'] => 'El servicio seleccionado está inactivo.',
                    ]);
                }
            }
             
            if (!$esCancelada && !isset($vehiculosRevisados[$vehiculoId])) {

                $minutosVehiculo = $this->minutosDeLineasPorVehiculo(
                    $lineas,
                    $vehiculoId
                );

                $choca = $citasDelDia->contains(function ($cita) use (
                    $vehiculoId,
                    $hora,
                    $minutosVehiculo
                ) {
                    $tieneVehiculo = $cita->agendaServicios->contains(
                        'vehiculoId',
                        $vehiculoId
                    );

                    if (!$tieneVehiculo) {
                        return false;
                    }

                    return $this->seSolapa(
                        $hora,
                        $minutosVehiculo,
                        $cita->hora,
                        $this->minutosDeCitaParaVehiculo($cita, $vehiculoId)
                    );
                });

    if ($choca) {
        throw ValidationException::withMessages([
            $linea['campoVehiculo'] => 'Ese vehículo ya tiene una cita que se cruza con ese horario.',
        ]);
    }

    $vehiculosRevisados[$vehiculoId] = true;
}

            $par = $vehiculoId . ':' . $servicioId;
            if (isset($paresServicio[$par])) {
                throw ValidationException::withMessages([
                    $linea['campoServicio'] => 'No repitas un servicio dentro del mismo vehículo.',
                ]);
            }
            $paresServicio[$par] = true;

        }

        if ($esCancelada) {
            return;
        }

                if ($agendaId === null && Carbon::parse("$fecha $hora")->lt(now()->startOfMinute())) {
         throw ValidationException::withMessages(['hora' => 'La hora ya pasó. Elige una hora posterior a la actual.']);
        }

        if (!HorarioLavadero::permite($fecha, $hora, $minutosNueva)) {
            throw ValidationException::withMessages([
                'hora' => 'La hora está fuera del horario de atención del lavadero para ese día.',
            ]);
        }

        if (($datos['estado'] ?? null) === 'confirmada' && empty($datos['empleadoId'])) {
         throw ValidationException::withMessages(['empleadoId' => 'Asigna un empleado antes de confirmar la cita.']);
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

        if (!$this->agendaRepository->empleadoTieneTurno($empleadoId,$dia,$horaCompleta,$minutosNueva)) {
            throw ValidationException::withMessages([
                'empleadoId' => 'El empleado no tiene un turno que cubra ese día y esa hora.',
            ]);
        }

        $choca = $citasDelDia->contains(fn ($cita) =>
    (int) $cita->empleadoId === $empleadoId
    && $this->seSolapa($hora, $minutosNueva, $cita->hora, $this->minutosDeCita($cita)));

    if ($choca) {
        throw ValidationException::withMessages([
            'empleadoId' => 'Ese empleado ya tiene una cita que se cruza con ese horario.',
        ]);
    }
    }

    
private function guardarLineas(int $agendaId, array $lineas): void
{
    foreach ($lineas as $linea) {
        $vehiculoId = (int) $linea['vehiculoId'];
        $servicioId = (int) $linea['servicioId'];

        try {
            $precio = $this->calcularPrecioServicio(
                $vehiculoId,
                $servicioId,
                $linea['precioAcordado'] ?? null,
                $linea['precioAcordadoActivo'] ?? false
            );

            $duracion = $this->calcularDuracionServicio(
                $vehiculoId,
                $servicioId
            );

            $this->agendaServicioRepository->keep([
                'agendaId' => $agendaId,
                'vehiculoId' => $vehiculoId,
                'servicioId' => $servicioId,
                'Precio' => $precio,
                'duracionMinutos' => $duracion,
            ]);

        } catch (ValidationException $error) {
            $errores = $error->errors();

            $campo = array_key_exists('precioAcordado', $errores)
                ? 'precioAcordado'
                : 'servicioId';

            $mensaje = collect($errores)->flatten()->first();

            $campoError = $campo === 'precioAcordado'
                ? $linea['campoPrecio']
                : $linea['campoServicio'];

            throw ValidationException::withMessages([
                $campoError => $mensaje ?? 'No se pudo guardar el servicio.',
            ]);
        }
    }
}


        public function delete(int $id): bool
    {
        if ($this->agendaRepository->tienePago($id)) {
            return false;
        }

        return DB::transaction(function () use ($id) {
            $this->agendaRepository->quitarLineas($id);
            return $this->agendaRepository->delete($id) > 0;
        });
    }

    private function minutosDeLineas(array $lineas): int
{
    $total = 0;

    foreach ($lineas as $linea) {
        $vehiculo = $this->vehiculoRepository->finById(
            (int) $linea['vehiculoId']
        );

        $configuracion = $this->precioServicioRepository->buscarConfiguracion(
            (int) $linea['servicioId'],
            (int) $vehiculo->tipoVehiculoId
        );

        if ($configuracion === null || (int) $configuracion->duracionMinutos < 1) {
            throw ValidationException::withMessages([
                $linea['campoServicio'] => 'Este servicio no tiene una duración configurada para el tipo de vehículo seleccionado.',
            ]);
        }

        $total += (int) $configuracion->duracionMinutos;
    }

    return max(30, $total);
}

    private function minutosDeCita($cita): int
{
    return max(
        30,
        (int) $cita->agendaServicios->sum(
            fn ($linea) => $linea->duracionMinutos ?? 30
        )
    );
}

   private function seSolapa(string $horaA, int $minA, string $horaB, int $minB): bool
    {
        $inicioA = Carbon::createFromFormat('H:i', substr($horaA, 0, 5));
        $inicioB = Carbon::createFromFormat('H:i', substr($horaB, 0, 5));

        return $inicioA < $inicioB->copy()->addMinutes($minB)
            && $inicioA->copy()->addMinutes($minA) > $inicioB;
    }

    private function minutosDeLineasPorVehiculo(
    array $lineas,
    int $vehiculoId
): int {
    $total = 0;

    foreach ($lineas as $linea) {

        if ((int) $linea['vehiculoId'] !== $vehiculoId) {
            continue;
        }

        $vehiculo = $this->vehiculoRepository->finById(
            $vehiculoId
        );

        $configuracion = $this->precioServicioRepository->buscarConfiguracion(
            (int) $linea['servicioId'],
            (int) $vehiculo->tipoVehiculoId
        );

        if (
            $configuracion === null ||
            (int) $configuracion->duracionMinutos < 1
        ) {
            throw ValidationException::withMessages([
                $linea['campoServicio'] => 'Este servicio no tiene una duración válida configurada.',
            ]);
        }

        $total += (int) $configuracion->duracionMinutos;
    }

    return max(30, $total);
}

private function minutosDeCitaParaVehiculo(
    $cita,
    int $vehiculoId
): int {
    $lineas = $cita->agendaServicios->where(
        'vehiculoId',
        $vehiculoId
    );

    $total = $lineas->sum(function ($linea) {
        return $linea->duracionMinutos
            ?? $linea->servicio?->duracionMinutos
            ?? 30;
    });

    return max(30, (int) $total);
}
}
