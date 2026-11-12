<?php 

namespace App\Services;

use App\Repositories\Contracts\PrecioServicioRepositoryInterface as PrecioServicioRepository;

class PrecioServicioService
{
    private PrecioServicioRepository $precioServicioRepository;
    
    public function __construct(PrecioServicioRepository $precioServicioRepository)
{
    $this->precioServicioRepository = $precioServicioRepository;    
}

public function listarTodo(){
    return $this->precioServicioRepository->listarTodo();
}

public function buscar(string $texto, array $filtros = [])
{
    return $this->precioServicioRepository->buscar($texto, $filtros);
}

public function prepararListado(array $datos): array
{
    $buscar = trim((string) ($datos['buscar'] ?? ''));
    $filtros = array_intersect_key($datos, array_flip(['tipoVehiculoId', 'modalidad']));

    return [
        'precioServicio' => $this->buscar($buscar, $filtros),
        'buscar' => $buscar,
        'filtros' => $filtros,
    ];
}

public function opcionesDuracion(int $duracionSeleccionada = 30): array
{
    $opciones = [
        30 => '30 minutos',
        40 => '40 minutos',
        50 => '50 minutos',
        60 => '1 hora',
        70 => '1 hora y 10 minutos',
        80 => '1 hora y 20 minutos',
        90 => '1 hora y 30 minutos',
        100 => '1 hora y 40 minutos',
        110 => '1 hora y 50 minutos',
        120 => '2 horas',
        150 => '2 horas y 30 minutos',
        180 => '3 horas',
    ];

    if (! array_key_exists($duracionSeleccionada, $opciones)) {
        $horas = intdiv($duracionSeleccionada, 60);
        $minutos = $duracionSeleccionada % 60;
        $opciones = [$duracionSeleccionada => ($horas > 0 ? $horas.' horas y ' : '').$minutos.' minutos'] + $opciones;
    }

    return $opciones;
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
