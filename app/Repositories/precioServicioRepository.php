<?php

namespace App\Repositories;

use App\Models\precioServicio;
use App\Support\TextoBusqueda;

class precioServicioRepository
{
    public function listarTodo()
    {
        return precioServicio::query()
            ->select(['id', 'servicio_id', 'tipo_vehiculo_id', 'modalidad', 'precio'])
            ->with(['servicio:id,nombre,estado', 'tipoVehiculo:id,nombre'])
            ->whereHas('servicio', fn ($query) => $query->where('estado', true))
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(
                precioServicio::query()
                    ->select(['id', 'servicio_id', 'tipo_vehiculo_id', 'modalidad', 'precio'])
                    ->with(['servicio:id,nombre,estado', 'tipoVehiculo:id,nombre'])
                    ->whereHas('servicio', fn ($query) => $query->where('estado', true)),
                $texto,
                fn ($consulta, $termino) => $consulta
                    ->whereHas('servicio', fn ($servicio) => $servicio->where('nombre', 'like', $termino))
                    ->orWhereHas('tipoVehiculo', fn ($tipo) => $tipo->where('nombre', 'like', $termino))
                    ->orWhere('modalidad', 'like', $termino)
                    ->orWhere('precio', 'like', $termino)
            )
            ->when(($filtros['tipoVehiculoId'] ?? '') !== '', fn ($query) => $query->where('tipo_vehiculo_id', $filtros['tipoVehiculoId']))
            ->when(($filtros['modalidad'] ?? '') !== '', fn ($query) => $query->where('modalidad', $filtros['modalidad']))
            ->orderBy('servicio_id')
            ->paginate(10)
            ->withQueryString();
    }

    public function guardar(array $datos)
    {
        return precioServicio::create($datos);
    }

    public function eliminar(int $id)
    {
        precioServicio::destroy($id);
    }

    public function buscarPorId(int $id)
    {
        return precioServicio::findOrfail($id);
    }

    public function buscarPrecio(int $servicioId, int $tipoVehiculoId)
{
    return precioServicio::where('servicio_id', $servicioId)
        ->where('tipo_vehiculo_id', $tipoVehiculoId)
        ->value('precio');
}

    public function buscarConfiguracion(int $servicioId, int $tipoVehiculoId): ?precioServicio
    {
        return precioServicio::where('servicio_id', $servicioId)
            ->where('tipo_vehiculo_id', $tipoVehiculoId)
            ->first();
    }

    public function actualizar(int $id, array $datos)
    {
        $precioServicio = precioServicio::findOrFail($id);
        $precioServicio->update($datos);
        return $precioServicio;
    }
}
