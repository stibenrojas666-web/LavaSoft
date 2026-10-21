<?php 

namespace App\Services;

use App\Repositories\turnoRepository;
use Illuminate\Validation\ValidationException;

class turnoService
{
    private turnoRepository $turnoRepository;
    
    public function __construct(turnoRepository $turnoRepository)
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

public function guardar(array $datos){
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

}
