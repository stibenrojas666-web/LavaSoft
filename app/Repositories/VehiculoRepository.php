<?php

namespace App\Repositories;

use App\Models\AgendaServicios;
use App\Models\Vehiculo;
use App\Repositories\Contracts\VehiculoRepositoryInterface;
use App\Support\TextoBusqueda;
use Illuminate\Support\Facades\DB;

class VehiculoRepository implements VehiculoRepositoryInterface
{
    public function getAll()
    {
        return Vehiculo::query()
            ->select(['id', 'clienteID', 'tipoVehiculoId', 'placa', 'modelo', 'color', 'estado'])
            ->with(['cliente:id,nombreCliente,apellidoCliente', 'tipoVehiculo:id,nombre'])
            ->get();
    }

    public function listarParaAgenda(array $incluirIds = [])
    {
        $incluirIds = array_values(array_unique(array_filter(array_map('intval', $incluirIds))));

        return Vehiculo::query()
            ->select(['id', 'clienteID', 'tipoVehiculoId', 'placa', 'modelo', 'color', 'estado'])
            ->with(['cliente:id,nombreCliente,apellidoCliente', 'tipoVehiculo:id,nombre'])
            ->where(function ($query) use ($incluirIds) {
                $query->where('estado', 'Activo');

                if ($incluirIds) {
                    $query->orWhereIn('id', $incluirIds);
                }
            })
            ->orderBy('placa')
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(Vehiculo::query()
            ->select(['id', 'clienteID', 'tipoVehiculoId', 'placa', 'modelo', 'color', 'estado'])
            ->with([
                'cliente:id,nombreCliente,apellidoCliente',
                'tipoVehiculo:id,nombre',
            ])
            ->withCount('agendas'),
            $texto, function ($filtros, $termino) {
                $filtros->where('placa', 'like', $termino)
                    ->orWhere('modelo', 'like', $termino)
                    ->orWhere('color', 'like', $termino)
                    ->orWhere('estado', 'like', $termino)
                    ->orWhereHas('cliente', fn ($cliente) => $cliente->where('nombreCliente', 'like', $termino)
                        ->orWhere('apellidoCliente', 'like', $termino)
                        ->orWhere('telefonoCliente', 'like', $termino)
                        ->orWhere('emailCliente', 'like', $termino))
                    ->orWhereHas('tipoVehiculo', fn ($tipo) => $tipo->where('nombre', 'like', $termino));
            })
            ->when(($filtros['estado'] ?? '') !== '', fn ($query) => $query->where('estado', ($filtros['estado'] === 'activo') ? 'Activo' : 'Inactivo'))
            ->when(($filtros['tipoVehiculoId'] ?? '') !== '', fn ($query) => $query->where('tipoVehiculoId', $filtros['tipoVehiculoId']))
            ->orderBy('placa')
            ->paginate(10)
            ->withQueryString();
    }

    public function cambiarEstado(int $id): bool
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->estado = strtolower((string) $vehiculo->estado) === 'activo' ? 'Inactivo' : 'Activo';
        $vehiculo->save();

        return strtolower((string) $vehiculo->estado) === 'activo';
    }

    public function keep(array $datos)
    {
        return Vehiculo::create($datos);
    }

    public function finById(int $id)
    {
        return Vehiculo::with(['cliente', 'tipoVehiculo'])->findOrFail($id);
    }

    public function historialServicios(int $vehiculoId)
    {
        return AgendaServicios::with(['agenda.cliente', 'agenda.empleado', 'agenda.pago', 'servicio'])
            ->where('vehiculoId', $vehiculoId)
            ->whereHas('agenda', fn ($query) => $query->where('estado', 'terminada'))
            ->orderByDesc('agendaId')
            ->orderByDesc('id')
            ->get();
    }

    public function update(int $id, array $datos)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update($datos);

        return $vehiculo;
    }

    public function delete(int $id)
    {
        return Vehiculo::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        $tieneServicios = AgendaServicios::where(
            'vehiculoId',
            $id
        )->exists();

        $tieneAgendasAntiguas = DB::table('agendas')
            ->where('vehiculoId', $id)
            ->exists();

        return $tieneServicios || $tieneAgendasAntiguas;
    }
}
