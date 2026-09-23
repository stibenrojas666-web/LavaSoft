<?php 

namespace App\Services;

use App\Repositories\tipoVehiculoRepository;

class tipoVehiculoService
{
    private tipoVehiculoRepository $tipoVehiculoRepository;
    
    public function __construct(tipoVehiculoRepository $tipoVehiculoRepository)
{
    $this->tipoVehiculoRepository = $tipoVehiculoRepository;    
}

public function listarTodo(){
    return $this->tipoVehiculoRepository->listarTodo();
}

public function guardar(array $datos){
    return $this->tipoVehiculoRepository->guardar($datos);
}

public function eliminar(int $id){
    return $this->tipoVehiculoRepository->eliminar($id);
}

public function buscarPorId(int $id){
    return $this->tipoVehiculoRepository->buscarPorId($id);
}

public function actualizar(int $id, array $datos)
{
    return $this->tipoVehiculoRepository->actualizar($id, $datos);
}

}
