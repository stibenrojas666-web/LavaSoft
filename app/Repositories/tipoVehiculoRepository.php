<?php

namespace App\Repositories;

use App\Repositories\Contracts\TipoVehiculoRepositoryInterface;

use App\Models\TipoVehiculo;
use App\Models\PrecioServicio;
use App\Models\Vehiculo;
use App\Support\TextoBusqueda;

class TipoVehiculoRepository implements TipoVehiculoRepositoryInterface {
    public function listarTodo()
    {
        return TipoVehiculo::query()->select(['id', 'nombre'])->orderBy('nombre')->get();
    }

    public function buscar(string $texto)
    {
        return TextoBusqueda::aplicar(TipoVehiculo::query()->select(['id', 'nombre']), $texto, fn ($query, $termino) => $query->where('nombre', 'like', $termino))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();
    }

    public function guardar(array $datos)
    {
        return TipoVehiculo::create($datos);
    }

    public function eliminar(int $id)
    {
        return TipoVehiculo::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return Vehiculo::where('tipoVehiculoId', $id)->exists()
            || PrecioServicio::where('tipo_vehiculo_id', $id)->exists();
    }

    public function buscarPorId(int $id)
    {
        return TipoVehiculo::findOrfail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $tipovehiculo = TipoVehiculo::findOrFail($id);
        $tipovehiculo->update($datos);
        return $tipovehiculo;
    }
}
