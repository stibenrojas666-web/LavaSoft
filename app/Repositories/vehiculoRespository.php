<?php

namespace App\Repositories;
use App\Models\vehiculo;
use App\Models\agendaServicios;
use App\Support\TextoBusqueda;

class VehiculoRespository{
    public function getAll(){
        return vehiculo::with(['cliente', 'tipoVehiculo'])->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(vehiculo::with(['cliente', 'tipoVehiculo']), $texto, function ($filtros, $termino) {
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
            ->paginate(12)
            ->withQueryString();
    }

    public function cambiarEstado(int $id): bool
    {
        $vehiculo = vehiculo::findOrFail($id);
        $vehiculo->estado = strtolower((string) $vehiculo->estado) === 'activo' ? 'Inactivo' : 'Activo';
        $vehiculo->save();

        return strtolower((string) $vehiculo->estado) === 'activo';
    }

    public function keep(array $datos){
    return vehiculo::create($datos);
    }

    public function finById(int $id){
        return vehiculo::with(['cliente', 'tipoVehiculo'])->findOrFail($id);
    }

    public function historialServicios(int $vehiculoId)
    {
        return agendaServicios::with(['agenda.cliente', 'agenda.empleado', 'agenda.pago', 'servicio'])
            ->where('vehiculoId', $vehiculoId)
            ->whereHas('agenda', fn ($query) => $query->where('estado', 'terminada'))
            ->orderByDesc('agendaId')
            ->orderByDesc('id')
            ->get();
    }

    public function update (int $id, array $datos){
        $vehiculo= vehiculo::findOrFail($id);
        $vehiculo->update($datos);
        return $vehiculo; 
    }

    public function delete (int $id){
        return vehiculo::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return agendaServicios::where('vehiculoId', $id)->exists();
    }
}
