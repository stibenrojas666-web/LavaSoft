<?php 

namespace App\Services;

use App\Repositories\servicioRepository;

class servicioService
{
    private servicioRepository $servicioRepository;
    
    public function __construct(servicioRepository $servicioRepository)
{
    $this->servicioRepository = $servicioRepository;    
}

public function listarTodo(){
    return $this->servicioRepository->listarTodo();
}

public function guardar(array $datos){
    return $this->servicioRepository->guardar($datos);
}

public function eliminar(int $id){
    return $this->servicioRepository->eliminar($id);
}

public function buscarPorId(int $id){
    return $this->servicioRepository->buscarPorId($id);
}

public function actualizar(int $id, array $datos)
{
    return $this->servicioRepository->actualizar($id, $datos);
}

}
