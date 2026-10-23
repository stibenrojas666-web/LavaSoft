<?php

namespace App\Repositories;

use App\Models\servicio;
use App\Models\agendaServicios;
use App\Models\precioServicio;
use App\Support\TextoBusqueda;

class servicioRepository
{
    public function listarTodo()
    {
        return servicio::query()
            ->select(['id', 'nombre', 'descripcion', 'estado'])
            ->orderBy('nombre')
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(servicio::query()->select(['id', 'nombre', 'descripcion', 'estado']), $texto, fn ($consulta, $termino) => $consulta
                ->where('nombre', 'like', $termino)
                ->orWhere('descripcion', 'like', $termino))
            ->when(($filtros['estado'] ?? '') !== '', fn ($query) => $query->where('estado', $filtros['estado'] === 'activo'))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();
    }

    public function listarActivosConPrecios()
    {
        return servicio::query()
            ->select(['id', 'nombre', 'estado'])
            ->with('precios:id,servicio_id,tipo_vehiculo_id,modalidad,precio')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();
    }

    public function guardar(array $datos)
    {
        return servicio::create($datos);
    }

    public function cambiarEstado(int $id): bool
    {
        $servicio = servicio::findOrFail($id);
        $servicio->estado = ! (bool) $servicio->estado;
        $servicio->save();

        return (bool) $servicio->estado;
    }

    public function eliminar(int $id)
    {
        return servicio::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return agendaServicios::where('servicioId', $id)->exists()
            || precioServicio::where('servicio_id', $id)->exists();
    }

    public function buscarPorId(int $id)
    {
        return servicio::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $servicio = servicio::findOrFail($id);
        $servicio->update($datos);
        return $servicio;
    }
}
