<?php 

namespace App\Services;

use App\Repositories\precioServicioRepository;

class precioServicioService
{
    private precioServicioRepository $precioServicioRepository;
    
    public function __construct(precioServicioRepository $precioServicioRepository)
{
    $this->precioServicioRepository = $precioServicioRepository;    
}

public function listarTodo(){
    return $this->precioServicioRepository->listarTodo();
}

public function guardar(array $datos){
    return $this->precioServicioRepository->guardar($datos);
}

public function eliminar(int $id){
    return $this->precioServicioRepository->eliminar($id);
}

public function buscarPorId(int $id){
    return $this->precioServicioRepository->buscarPorId($id);
}

public function actualizar(int $id, array $datos)
{
    return $this->precioServicioRepository->actualizar($id, $datos);
}

}
