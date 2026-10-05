<?php
namespace App\Services;
Use App\Repositories\VehiculoRespository;

Class VehiculoService{
    private VehiculoRespository $VehiculoRespository;

    public function __construct(VehiculoRespository $VehiculoRepository) {
    $this->VehiculoRespository = $VehiculoRepository;}

    public function getAll(){
        return $this->VehiculoRespository->getAll(); 
    }

    public function keep(array $datos){
        return $this->VehiculoRespository->keep($datos);
    }

    public function findById(int $id){
        return $this->VehiculoRespository->finById($id);
    }

    public function update(int $id, array $datos){
        return $this->VehiculoRespository->update($id, $datos);
    }

    public function delete(int $id){
        return $this->VehiculoRespository->delete($id);
    }
}