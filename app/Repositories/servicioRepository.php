<?php

namespace App\Repositories;

use App\Repositories\Contracts\ServicioRepositoryInterface;

use App\Models\Servicio;
use App\Models\AgendaServicios;
use App\Models\PrecioServicio;
use App\Support\TextoBusqueda;

class ServicioRepository implements ServicioRepositoryInterface {
    public function listarTodo()
    {
        return Servicio::query()
            ->select(['id', 'nombre', 'descripcion', 'estado'])
            ->orderBy('nombre')
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(Servicio::query()->select(['id', 'nombre', 'descripcion', 'estado']), $texto, fn ($consulta, $termino) => $consulta
                ->where('nombre', 'like', $termino)
                ->orWhere('descripcion', 'like', $termino))
            ->when(($filtros['estado'] ?? '') !== '', fn ($query) => $query->where('estado', $filtros['estado'] === 'activo'))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();
    }

    public function listarActivosConPrecios()
    {
        return Servicio::query()
            ->select(['id', 'nombre', 'estado'])
            ->with('precios:id,servicio_id,tipo_vehiculo_id,modalidad,precio,duracionMinutos')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();
    }

    public function guardar(array $datos)
    {
        return Servicio::create($datos);
    }

    public function cambiarEstado(int $id): bool
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->estado = ! (bool) $servicio->estado;
        $servicio->save();

        return (bool) $servicio->estado;
    }

    public function eliminar(int $id)
    {
        return Servicio::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return AgendaServicios::where('servicioId', $id)->exists()
            || PrecioServicio::where('servicio_id', $id)->exists();
    }

    public function buscarPorId(int $id)
    {
        return Servicio::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->update($datos);
        return $servicio;
    }
}
