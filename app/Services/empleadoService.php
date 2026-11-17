<?php 

namespace App\Services;

use App\Repositories\Contracts\EmpleadoRepositoryInterface as EmpleadoRepository;
use Carbon\Carbon;

class EmpleadoService
{
    private EmpleadoRepository $empleadoRepository;
    
    public function __construct(EmpleadoRepository $empleadoRepository)
{
    $this->empleadoRepository = $empleadoRepository;    
}

public function listarTodo(){
    return $this->empleadoRepository->listarTodo();
}

    public function buscar(string $texto, array $filtros = [])
{
        return $this->empleadoRepository->buscar($texto, $filtros);
}

public function prepararListado(array $datos): array
{
    $buscar = trim((string) ($datos['buscar'] ?? ''));
    $filtros = array_intersect_key($datos, array_flip(['estado']));

    return [
        'empleados' => $this->buscar($buscar, $filtros),
        'buscar' => $buscar,
        'filtros' => $filtros,
    ];
}

    public function disponiblesEnHorario(
    string $fecha,
    string $hora,
    ?int $agendaId = null,
    int $duracionMinutos = 30
) {
    $dias = [
        1 => 'lunes',
        2 => 'martes',
        3 => 'miercoles',
        4 => 'jueves',
        5 => 'viernes',
        6 => 'sabado',
        7 => 'domingo',
    ];

    $inicioNueva = Carbon::createFromFormat('Y-m-d H:i', $fecha.' '.substr($hora, 0, 5));
    $finNueva = $inicioNueva->copy()->addMinutes($duracionMinutos);

    if ($finNueva->toDateString() !== $inicioNueva->toDateString()) {
        return collect();
    }

    $dia = $dias[Carbon::parse($fecha)->dayOfWeekIso];
    $empleados = $this->empleadoRepository->candidatosConTurno(
        $fecha,
        $dia,
        $inicioNueva->format('H:i:s'),
        $finNueva->format('H:i:s'),
        $agendaId
    );

    return $empleados->reject(function ($empleado) use ($inicioNueva, $finNueva): bool {
        foreach ($empleado->agendas as $cita) {
            $inicioCita = Carbon::parse($cita->fecha.' '.$cita->hora);
            $duracionCita = max(30, (int) $cita->agendaServicios->sum(
                fn ($linea) => $linea->duracionMinutos ?? 30
            ));
            $finCita = $inicioCita->copy()->addMinutes($duracionCita);

            if ($inicioNueva->lt($finCita) && $finNueva->gt($inicioCita)) {
                return true;
            }
        }

        $empleado->unsetRelation('agendas');

        return false;
    })->values();
}

public function disponiblesParaAgenda(array $datos): array
{
    return $this->disponiblesEnHorario(
        $datos['fecha'],
        $datos['hora'],
        isset($datos['agendaId']) ? (int) $datos['agendaId'] : null,
        (int) ($datos['duracionMinutos'] ?? 30)
    )->map(fn ($empleado) => [
        'id' => $empleado->id,
        'nombre' => trim($empleado->nombre.' '.$empleado->apellido),
        'jornada' => $empleado->turnos->pluck('jornada')->unique()->implode(', '),
    ])->values()->all();
}

public function guardar(array $datos){
    return $this->empleadoRepository->guardar($datos);
}

    public function eliminar(int $id): bool
    {
        if ($this->empleadoRepository->tieneDependencias($id)) {
            return false;
        }

        return $this->empleadoRepository->eliminar($id) > 0;
}

public function buscarPorId(int $id){
    return $this->empleadoRepository->buscarPorId($id);
}

public function actualizar(int $id, array $datos)
{
    return $this->empleadoRepository->actualizar($id, $datos);
}

public function cambiarEstado(int $id): bool
{
    $empleado = $this->empleadoRepository->buscarPorId($id);
    $nuevoEstado = !(bool) $empleado->estado;

    $this->empleadoRepository->actualizar($id, ['estado' => $nuevoEstado]);

    return $nuevoEstado;
}

}
