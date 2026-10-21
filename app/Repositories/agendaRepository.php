<?php

namespace App\Repositories;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\pago;
use App\Support\TextoBusqueda;

class AgendaRepository
{
    public function getAll()
    {
        return agenda::with([
            'empleado',
            'cliente',
            'agendaServicios.vehiculo.tipoVehiculo',
            'agendaServicios.servicio',
        ])->withSum('agendaServicios', 'Precio')->get();
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
                ->paginate(12)
                ->withQueryString();
        }

        return $this->consultaBusqueda($texto, $filtros)
            ->when(($filtros['desde'] ?? '') !== '', fn ($query) => $query->whereDate('fecha', '>=', $filtros['desde']))
            ->when(($filtros['hasta'] ?? '') !== '', fn ($query) => $query->whereDate('fecha', '<=', $filtros['hasta']))
            ->orderBy('fecha', $orden === 'fecha_asc' ? 'asc' : 'desc')
            ->orderBy('hora', $orden === 'fecha_asc' ? 'asc' : 'desc')
            ->paginate(12)
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

    private function consultaBusqueda(string $texto, array $filtros)
    {
        $consulta = agenda::with([
            'empleado',
            'cliente',
            'agendaServicios.vehiculo.tipoVehiculo',
            'agendaServicios.servicio',
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
        return agenda::create($datos);
    }

    public function findById(int $id)
    {
        return agenda::with([
            'empleado',
            'cliente',
            'agendaServicios.vehiculo.tipoVehiculo',
            'agendaServicios.servicio',
        ])->findOrFail($id);
    }

    public function vehiculoOcupado(int $vehiculoId, string $fecha, string $hora, ?int $agendaId = null): bool
    {
        return agenda::whereHas('agendaServicios', fn ($query) => $query->where('vehiculoId', $vehiculoId))
            ->where('fecha', $fecha)
            ->whereTime('hora', '=', $hora)
            ->where('estado', '!=', 'cancelada')
            ->when($agendaId, fn ($query) => $query->where('id', '!=', $agendaId))
            ->exists();
    }

    public function vehiculoPerteneceACliente(int $vehiculoId, int $clienteId): bool
    {
        return \App\Models\vehiculo::whereKey($vehiculoId)
            ->where('clienteID', $clienteId)
            ->exists();
    }

    public function empleadoActivo(int $empleadoId): bool
    {
        return \App\Models\empleado::whereKey($empleadoId)->where('estado', true)->exists();
    }

    public function empleadoTieneTurno(int $empleadoId, string $dia, string $hora): bool
    {
        return \App\Models\turno::where('empleado_id', $empleadoId)
            ->where('dia', $dia)
            ->where('horaInicio', '<=', $hora)
            ->where('horaFin', '>', $hora)
            ->exists();
    }

    public function empleadoOcupado(int $empleadoId, string $fecha, string $hora, ?int $agendaId = null): bool
    {
        return agenda::where('empleadoId', $empleadoId)
            ->where('fecha', $fecha)
            ->whereTime('hora', '=', $hora)
            ->where('estado', '!=', 'cancelada')
            ->when($agendaId, fn ($query) => $query->where('id', '!=', $agendaId))
            ->exists();
    }

    public function update(int $id, array $datos)
    {
        $agenda = agenda::findOrFail($id);
        $datos['vehiculoId'] = null;
        $agenda->update($datos);
        return $agenda;
    }

    public function quitarLineas(int $agendaId): void
    {
        agendaServicios::where('agendaId', $agendaId)->delete();
    }

    public function delete(int $id)
    {
        return agenda::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return agendaServicios::where('agendaId', $id)->exists()
            || pago::where('agendaId', $id)->exists();
    }
}
