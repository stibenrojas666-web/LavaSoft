<?php

namespace App\Repositories;

use App\Repositories\Contracts\AgendaServicioRepositoryInterface;

use App\Models\AgendaServicios;
use App\Models\Pago;
use App\Support\TextoBusqueda;

class AgendaServicioRepository implements AgendaServicioRepositoryInterface {
    public function getAll()
    {
        return AgendaServicios::query()
            ->select(['id', 'agendaId', 'vehiculoId', 'servicioId', 'Precio','duracionMinutos'])
            ->with([
                'agenda:id,clienteId,empleadoId,fecha,hora,estado',
                'agenda.cliente:id,nombreCliente,apellidoCliente',
                'vehiculo:id,clienteID,tipoVehiculoId,placa,modelo',
                'vehiculo.tipoVehiculo:id,nombre',
                'servicio:id,nombre',
            ])
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        return TextoBusqueda::aplicar(AgendaServicios::query()
            ->select(['id', 'agendaId', 'vehiculoId', 'servicioId', 'Precio','duracionMinutos'])
            ->with([
                'agenda:id,clienteId,empleadoId,fecha,hora,estado',
                'agenda.cliente:id,nombreCliente,apellidoCliente',
                'vehiculo:id,clienteID,tipoVehiculoId,placa,modelo',
                'vehiculo.tipoVehiculo:id,nombre',
                'servicio:id,nombre',
            ]), $texto, fn ($consulta, $termino) => $consulta
                ->where('Precio', 'like', $termino)
                ->orWhereHas('agenda', fn ($agenda) => $agenda->where('id', 'like', $termino)
                    ->orWhere('fecha', 'like', $termino)
                    ->orWhereHas('cliente', fn ($cliente) => $cliente->where('nombreCliente', 'like', $termino)
                        ->orWhere('apellidoCliente', 'like', $termino)))
                ->orWhereHas('vehiculo', fn ($vehiculo) => $vehiculo->where('placa', 'like', $termino))
                ->orWhereHas('servicio', fn ($servicio) => $servicio->where('nombre', 'like', $termino)))
            ->when(($filtros['estado'] ?? '') !== '', fn ($query) => $query->whereHas('agenda', fn ($agenda) => $agenda->where('estado', $filtros['estado'])))
            ->when(($filtros['desde'] ?? '') !== '', fn ($query) => $query->whereHas('agenda', fn ($agenda) => $agenda->whereDate('fecha', '>=', $filtros['desde'])))
            ->when(($filtros['hasta'] ?? '') !== '', fn ($query) => $query->whereHas('agenda', fn ($agenda) => $agenda->whereDate('fecha', '<=', $filtros['hasta'])))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();
    }

    public function keep(array $datos)
    {
        return AgendaServicios::create($datos);
    }

    public function findById(int $id)
    {
        return AgendaServicios::query()
            ->with([
                'agenda.cliente',
                'agenda.empleado',
                'vehiculo.tipoVehiculo',
                'servicio',
            ])
            ->findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $agendaServicio = AgendaServicios::findOrFail($id);
        $agendaServicio->update($datos);
        return $agendaServicio;
    }

    public function delete(int $id)
    {
        return AgendaServicios::destroy($id);
    }

    public function agendaTienePago(int $agendaId): bool
    {
        return Pago::where('agendaId', $agendaId)->exists();
    }

    public function buscarAgenda(int $agendaId)
    {
        return \App\Models\Agenda::with('cliente')->findOrFail($agendaId);
    }
}
