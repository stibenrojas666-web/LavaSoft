<?php

namespace App\Repositories;

use App\Models\tipoVehiculo;
use App\Models\precioServicio;
use App\Models\vehiculo;
use App\Support\TextoBusqueda;

class tipoVehiculoRepository
{
    public function listarTodo()
    {
        return tipoVehiculo::all();
    }

    public function buscar(string $texto)
    {
        return TextoBusqueda::aplicar(tipoVehiculo::query(), $texto, fn ($query, $termino) => $query->where('nombre', 'like', $termino))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();
    }

    public function guardar(array $datos)
    {
        return tipoVehiculo::create($datos);
    }

    public function eliminar(int $id)
    {
        return tipoVehiculo::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return vehiculo::where('tipoVehiculoId', $id)->exists()
            || precioServicio::where('tipo_vehiculo_id', $id)->exists();
    }

    public function buscarPorId(int $id)
    {
        return tipoVehiculo::findOrfail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $tipovehiculo = tipoVehiculo::findOrFail($id);
        $tipovehiculo->update($datos);
        return $tipovehiculo;
    }
}
