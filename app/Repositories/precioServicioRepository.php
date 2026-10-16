<?php

namespace App\Repositories;

use App\Models\precioServicio;

class precioServicioRepository
{
    public function listarTodo()
    {
        return precioServicio::with(['servicio', 'tipoVehiculo'])
            ->whereHas('servicio', fn ($query) => $query->where('estado', true))
            ->get();
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
