<?php 

namespace App\Services;

use App\Repositories\Contracts\TurnoRepositoryInterface as TurnoRepository;
use Illuminate\Validation\ValidationException;

class TurnoService
{
    private TurnoRepository $turnoRepository;
    
    public function __construct(TurnoRepository $turnoRepository)
{
    $this->turnoRepository = $turnoRepository;    
}

    public function listarTodo(){
        return $this->turnoRepository->listarTodo();
    }

        public function buscar(string $texto, array $filtros = [])
    {
            return $this->turnoRepository->buscar($texto, $filtros);
    }

    public function prepararListado(array $datos): array
    {
        $buscar = trim((string) ($datos['buscar'] ?? ''));
        $filtros = array_intersect_key($datos, array_flip(['dia', 'jornada']));

        return [
            'turnos' => $this->buscar($buscar, $filtros),
            'buscar' => $buscar,
            'filtros' => $filtros,
        ];
    }

    public function guardar(array $datos){
        $datos['jornada'] = $this->jornadaPorHora($datos['horaInicio']);
        $this->validarSinCruce($datos);
        return $this->turnoRepository->guardar($datos);
    }

    public function eliminar(int $id){
        return $this->turnoRepository->eliminar($id);
    }

    public function buscarPorId(int $id){
        return $this->turnoRepository->buscarPorId($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $datos['jornada'] = $this->jornadaPorHora($datos['horaInicio']);
        $this->validarSinCruce($datos, $id);
        return $this->turnoRepository->actualizar($id, $datos);
    }

private function validarSinCruce(array $datos, ?int $turnoId = null): void
{
    $horaInicio = $datos['horaInicio'] . ':00';
    $horaFin = $datos['horaFin'] . ':00';

    if ($this->turnoRepository->tieneCruce(
        (int) $datos['empleado_id'],
        $datos['dia'],
        $horaInicio,
        $horaFin,
        $turnoId
    )) {
        throw ValidationException::withMessages([
            'horaInicio' => 'Este empleado ya tiene un turno que se cruza con ese horario en ese día.',
        ]);
    }
}

private function jornadaPorHora(string $hora): string
{
    $h = (int) substr($hora, 0, 2);
    return $h < 12 ? 'mañana' : ($h < 18 ? 'tarde' : 'noche');
}

}
