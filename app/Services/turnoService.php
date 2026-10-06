<?php 

namespace App\Services;

use App\Repositories\turnoRepository;

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

public function guardar(array $datos){
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
    return $this->turnoRepository->actualizar($id, $datos);
}

}
