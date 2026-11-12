<?php 

namespace App\Services;

use App\Repositories\Contracts\TipoVehiculoRepositoryInterface as TipoVehiculoRepository;

class TipoVehiculoService
{
    private TipoVehiculoRepository $tipoVehiculoRepository;
    
    public function __construct(TipoVehiculoRepository $tipoVehiculoRepository)
{
    $this->tipoVehiculoRepository = $tipoVehiculoRepository;    
}

public function listarTodo(){
    return $this->tipoVehiculoRepository->listarTodo();
}

public function opcionesParaFiltro(): array
{
    return $this->listarTodo()->pluck('nombre', 'id')->all();
}

public function buscar(string $texto)
{
    return $this->tipoVehiculoRepository->buscar($texto);
}

public function prepararListado(array $datos): array
{
    $buscar = trim((string) ($datos['buscar'] ?? ''));

    return [
        'tipoVehiculos' => $this->buscar($buscar),
        'buscar' => $buscar,
    ];
}

public function guardar(array $datos){
    return $this->tipoVehiculoRepository->guardar($datos);
}

public function eliminar(int $id): bool
{
    if ($this->tipoVehiculoRepository->tieneDependencias($id)) {
        return false;
    }

    return $this->tipoVehiculoRepository->eliminar($id) > 0;
}

public function buscarPorId(int $id){
    return $this->tipoVehiculoRepository->buscarPorId($id);
}

public function actualizar(int $id, array $datos)
{
    return $this->tipoVehiculoRepository->actualizar($id, $datos);
}

}
