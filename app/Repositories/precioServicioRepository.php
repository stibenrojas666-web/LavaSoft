<?php

namespace App\Repositories;

use App\Repositories\Contracts\PrecioServicioRepositoryInterface;

use App\Models\PrecioServicio;
use App\Support\TextoBusqueda;

class PrecioServicioRepository implements PrecioServicioRepositoryInterface {
    public function listarTodo()
    {
        return PrecioServicio::query()
            ->select(['id','servicio_id','tipo_vehiculo_id','modalidad','precio','duracionMinutos',])
            ->with(['servicio:id,nombre,estado', 'tipoVehiculo:id,nombre'])
            ->whereHas('servicio', fn ($query) => $query->where('estado', true))
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(
                PrecioServicio::query()
                    ->select(['id','servicio_id','tipo_vehiculo_id','modalidad','precio','duracionMinutos',])
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
        return PrecioServicio::create($datos);
    }

    public function eliminar(int $id)
    {
        PrecioServicio::destroy($id);
    }

    public function buscarPorId(int $id)
    {
        return PrecioServicio::findOrfail($id);
    }

    public function buscarPrecio(int $servicioId, int $tipoVehiculoId)
{
    return PrecioServicio::where('servicio_id', $servicioId)
        ->where('tipo_vehiculo_id', $tipoVehiculoId)
        ->value('precio');
}

    public function buscarConfiguracion(int $servicioId, int $tipoVehiculoId): ?precioServicio
    {
        return PrecioServicio::where('servicio_id', $servicioId)
            ->where('tipo_vehiculo_id', $tipoVehiculoId)
            ->first();
    }

    public function actualizar(int $id, array $datos)
    {
        $precioServicio = PrecioServicio::findOrFail($id);
        $precioServicio->update($datos);
        return $precioServicio;
    }
}
