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

    public function buscar(string $texto, array $filtros = [])
    {
        return $this->VehiculoRespository->buscar($texto, $filtros);
    }

    public function cambiarEstado(int $id): bool
    {
        return $this->VehiculoRespository->cambiarEstado($id);
    }

    public function keep(array $datos){
        return $this->VehiculoRespository->keep($datos);
    }

    public function findById(int $id){
        return $this->VehiculoRespository->finById($id);
    }

    public function historial(int $id): array
    {
        $vehiculo = $this->VehiculoRespository->finById($id);
        $historial = $this->VehiculoRespository->historialServicios($id);

        return compact('vehiculo', 'historial');
    }

    public function update(int $id, array $datos){
        return $this->VehiculoRespository->update($id, $datos);
    }

    public function delete(int $id): bool{
        if ($this->VehiculoRespository->tieneDependencias($id)) {
            return false;
        }

        return $this->VehiculoRespository->delete($id) > 0;
    }
}
