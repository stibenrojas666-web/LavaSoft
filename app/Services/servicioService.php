<?php 

namespace App\Services;

use App\Repositories\Contracts\ServicioRepositoryInterface as ServicioRepository;

class ServicioService
{
    private ServicioRepository $servicioRepository;
    
    public function __construct(ServicioRepository $servicioRepository)
{
    $this->servicioRepository = $servicioRepository;    
}

public function listarTodo(){
    return $this->servicioRepository->listarTodo();
}

public function buscar(string $texto, array $filtros = [])
{
    return $this->servicioRepository->buscar($texto, $filtros);
}

public function prepararListado(array $datos): array
{
    $buscar = trim((string) ($datos['buscar'] ?? ''));
    $filtros = array_intersect_key($datos, array_flip(['estado']));

    return [
        'servicios' => $this->buscar($buscar, $filtros),
        'buscar' => $buscar,
        'filtros' => $filtros,
    ];
}

public function listarActivosConPrecios(){
    return $this->servicioRepository->listarActivosConPrecios();
}

public function catalogoActivosConPrecios(): array
{
    return $this->servicioRepository->listarActivosConPrecios()
        ->map(fn ($servicio) => [
            'id' => $servicio->id,
            'nombre' => $servicio->nombre,
            'precios' => $servicio->precios->mapWithKeys(fn ($precio) => [
                $precio->tipo_vehiculo_id => [
                    'modalidad' => $precio->modalidad,
                    'precio' => $precio->precio,
                    'duracionMinutos' => $precio->duracionMinutos,
                ],
            ])->all(),
        ])
        ->values()
        ->all();
}

public function guardar(array $datos){
    return $this->servicioRepository->guardar($datos);
}

public function cambiarEstado(int $id): bool
{
    return $this->servicioRepository->cambiarEstado($id);
}

public function eliminar(int $id): bool
{
    if ($this->servicioRepository->tieneDependencias($id)) {
        return false;
    }

    return $this->servicioRepository->eliminar($id) > 0;
}

public function buscarPorId(int $id){
    return $this->servicioRepository->buscarPorId($id);
}

public function actualizar(int $id, array $datos)
{
    return $this->servicioRepository->actualizar($id, $datos);
}

}
