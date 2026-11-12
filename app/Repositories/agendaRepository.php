<?php

namespace App\Repositories;

use App\Repositories\Contracts\AgendaRepositoryInterface;

use App\Models\Agenda;
use App\Models\AgendaServicios;
use App\Models\Pago;
use App\Support\TextoBusqueda;

class AgendaRepository implements AgendaRepositoryInterface {
    public function getAll()
    {
        return Agenda::query()
            ->select(['id', 'empleadoId', 'clienteId', 'vehiculoId', 'fecha', 'hora', 'estado', 'tipoDeAtencion'])
            ->with([
                'empleado:id,nombre,apellido',
                'cliente:id,nombreCliente,apellidoCliente',
                'agendaServicios:id,agendaId,vehiculoId,servicioId,Precio,duracionMinutos',
                'agendaServicios.vehiculo:id,clienteID,tipoVehiculoId,placa,modelo',
                'agendaServicios.vehiculo.tipoVehiculo:id,nombre',
                'agendaServicios.servicio:id,nombre',
            ])
            ->withSum('agendaServicios', 'Precio')
            ->get();
    }

    public function buscar(string $texto, array $filtros = [])
    {
        $orden = $filtros['orden'] ?? 'fecha_desc';

        if (str_starts_with($orden, 'hora_')) {
            $direccionHora = $orden === 'hora_desc' ? 'desc' : 'asc';

            return $this->consultaBusqueda($texto, $filtros)
                ->when(($filtros['desde'] ?? '') !== '', fn ($query) => $query->whereDate('fecha', '>=', $filtros['desde']))
                ->when(($filtros['hasta'] ?? '') !== '', fn ($query) => $query->whereDate('fecha', '<=', $filtros['hasta']))
                ->orderBy('hora', $direccionHora)
                ->orderBy('fecha')
                ->paginate(10)
                ->withQueryString();
        }

        return $this->consultaBusqueda($texto, $filtros)
            ->when(($filtros['desde'] ?? '') !== '', fn ($query) => $query->whereDate('fecha', '>=', $filtros['desde']))
            ->when(($filtros['hasta'] ?? '') !== '', fn ($query) => $query->whereDate('fecha', '<=', $filtros['hasta']))
            ->orderBy('fecha', $orden === 'fecha_asc' ? 'asc' : 'desc')
            ->orderBy('hora', $orden === 'fecha_asc' ? 'asc' : 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    public function buscarParaCalendario(string $texto, array $filtros, string $desde, string $hasta)
    {
        return $this->consultaBusqueda($texto, $filtros)
            ->whereBetween('fecha', [$desde, $hasta])
            ->when(($filtros['desde'] ?? '') !== '', fn ($query) => $query->whereDate('fecha', '>=', $filtros['desde']))
            ->when(($filtros['hasta'] ?? '') !== '', fn ($query) => $query->whereDate('fecha', '<=', $filtros['hasta']))
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();
    }

    public function totalServicios(int $agendaId): float
    {
        return (float) AgendaServicios::query()
            ->where('agendaId', $agendaId)
            ->sum('Precio');
    }

    private function consultaBusqueda(string $texto, array $filtros)
    {
        $consulta = Agenda::query()
            ->select(['id', 'empleadoId', 'clienteId', 'vehiculoId', 'fecha', 'hora', 'estado', 'tipoDeAtencion'])
            ->with([
                'empleado:id,nombre,apellido',
                'cliente:id,nombreCliente,apellidoCliente',
                'agendaServicios:id,agendaId,vehiculoId,servicioId,Precio,duracionMinutos',                'agendaServicios.vehiculo:id,clienteID,tipoVehiculoId,placa,modelo',
                'agendaServicios.vehiculo.tipoVehiculo:id,nombre',
                'agendaServicios.servicio:id,nombre',
            ])->withSum('agendaServicios', 'Precio');

        $consulta = TextoBusqueda::aplicar($consulta, $texto, fn ($coincidencias, $termino) => $coincidencias
            ->where('id', 'like', $termino)
            ->orWhere('fecha', 'like', $termino)
            ->orWhere('hora', 'like', $termino)
            ->orWhere('estado', 'like', $termino)
            ->orWhere('tipoDeAtencion', 'like', $termino)
            ->orWhereHas('cliente', fn ($cliente) => $cliente->where('nombreCliente', 'like', $termino)
                ->orWhere('apellidoCliente', 'like', $termino)
                ->orWhere('telefonoCliente', 'like', $termino))
            ->orWhereHas('empleado', fn ($empleado) => $empleado->where('nombre', 'like', $termino)
                ->orWhere('apellido', 'like', $termino))
            ->orWhereHas('agendaServicios.vehiculo', fn ($vehiculo) => $vehiculo->where('placa', 'like', $termino)
                ->orWhereHas('tipoVehiculo', fn ($tipo) => $tipo->where('nombre', 'like', $termino)))
            ->orWhereHas('agendaServicios.servicio', fn ($servicio) => $servicio->where('nombre', 'like', $termino)));

        return $consulta
            ->when(($filtros['estado'] ?? '') !== '', fn ($query) => $query->where('estado', $filtros['estado']));
    }

    public function keep(array $datos)
    {
        $datos['vehiculoId'] = null;
        return Agenda::create($datos);
    }

    public function findById(int $id)
    {
        return Agenda::with([
            'empleado',
            'cliente',
            'agendaServicios.vehiculo.tipoVehiculo',
            'agendaServicios.servicio',
        ])->findOrFail($id);
    }

    public function vehiculoOcupado(int $vehiculoId, string $fecha, string $hora, ?int $agendaId = null): bool
    {
        return Agenda::whereHas('agendaServicios', fn ($query) => $query->where('vehiculoId', $vehiculoId))
            ->where('fecha', $fecha)
            ->whereTime('hora', '=', $hora)
            ->where('estado', '!=', 'cancelada')
            ->when($agendaId, fn ($query) => $query->where('id', '!=', $agendaId))
            ->exists();
    }

    public function vehiculoPerteneceACliente(int $vehiculoId, int $clienteId): bool
    {
        return \App\Models\Vehiculo::whereKey($vehiculoId)
            ->where('clienteID', $clienteId)
            ->exists();
    }

    public function vehiculoActivo(int $id): bool
    {
        return \App\Models\Vehiculo::whereKey($id)->where('estado', 'Activo')->exists();
    }

    public function servicioActivo(int $id): bool
    {
        return \App\Models\Servicio::whereKey($id)->where('estado', true)->exists();
    }

    public function empleadoActivo(int $empleadoId): bool
    {
        return \App\Models\Empleado::whereKey($empleadoId)->where('estado', true)->exists();
    }

    public function empleadoTieneTurno(
    int $empleadoId,string $dia,string $hora,int $duracionMinutos): bool {
    $horaFin = \Carbon\Carbon::createFromFormat('H:i:s', $hora)
        ->addMinutes($duracionMinutos)
        ->format('H:i:s');

    return \App\Models\Turno::where('empleado_id', $empleadoId)
        ->where('dia', $dia)
        ->where('horaInicio', '<=', $hora)
        ->where('horaFin', '>=', $horaFin)
        ->exists();
    }

    public function empleadoOcupado(int $empleadoId, string $fecha, string $hora, ?int $agendaId = null): bool
    {
        return Agenda::where('empleadoId', $empleadoId)
            ->where('fecha', $fecha)
            ->whereTime('hora', '=', $hora)
            ->where('estado', '!=', 'cancelada')
            ->when($agendaId, fn ($query) => $query->where('id', '!=', $agendaId))
            ->exists();
    }

    public function update(int $id, array $datos)
    {
        $agenda = Agenda::findOrFail($id);
        $datos['vehiculoId'] = null;
        $agenda->update($datos);
        return $agenda;
    }

    public function quitarLineas(int $agendaId): void
    {
        AgendaServicios::where('agendaId', $agendaId)->delete();
    }

    public function delete(int $id)
    {
        return Agenda::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return AgendaServicios::where('agendaId', $id)->exists()
            || Pago::where('agendaId', $id)->exists();
    }

    public function tienePago(int $id): bool
    {
        return Pago::where('agendaId', $id)->exists();
    }

    public function citasActivasDelDia(string $fecha, ?int $excluirId = null)
    {
        return Agenda::with('agendaServicios.servicio:id,duracionMinutos')
            ->whereDate('fecha', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->when($excluirId, fn ($q) => $q->where('id', '!=', $excluirId))
            ->get();
    }

    public function duracionesServicios(array $ids)
    {
        return \App\Models\Servicio::whereIn('id', $ids)->pluck('duracionMinutos', 'id');
    }
}
