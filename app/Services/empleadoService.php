<?php 

namespace App\Services;

use App\Repositories\empleadoRepository;

class empleadoService
{
    private empleadoRepository $empleadoRepository;
    
    public function __construct(empleadoRepository $empleadoRepository)
{
    $this->empleadoRepository = $empleadoRepository;    
}

public function listarTodo(){
    return $this->empleadoRepository->listarTodo();
}

public function guardar(array $datos){
    return $this->empleadoRepository->guardar($datos);
}

public function eliminar(int $id){
    return $this->empleadoRepository->eliminar($id);
}

public function buscarPorId(int $id){
    return $this->empleadoRepository->buscarPorId($id);
}

public function actualizar(int $id, array $datos)
{
    return $this->empleadoRepository->actualizar($id, $datos);
}

}
