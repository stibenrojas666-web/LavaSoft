<?php

namespace App\Repositories;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\pago;
use App\Support\TextoBusqueda;

class PagoRepository
{
    public function historialPagos(string $texto = '', array $filtros = [])
    {
        $consulta = TextoBusqueda::aplicar(pago::with([
            'agenda.cliente',
            'agenda.empleado',
            'agenda.agendaServicios.vehiculo.tipoVehiculo',
            'agenda.agendaServicios.servicio',
        ]), $texto, fn ($coincidencias, $termino) => $coincidencias
            ->where('MetodoPago', 'like', $termino)
            ->orWhere('Monto', 'like', $termino)
            ->orWhereHas('agenda', fn ($agenda) => $agenda->where('id', 'like', $termino)
                ->orWhere('fecha', 'like', $termino)
                ->orWhere('hora', 'like', $termino)
                ->orWhereHas('cliente', fn ($cliente) => $cliente->where('nombreCliente', 'like', $termino)
                    ->orWhere('apellidoCliente', 'like', $termino)
                    ->orWhere('telefonoCliente', 'like', $termino))
                ->orWhereHas('agendaServicios.vehiculo', fn ($vehiculo) => $vehiculo->where('placa', 'like', $termino))));

        return $consulta
            ->when(($filtros['metodo'] ?? '') !== '', fn ($query) => $query->where('MetodoPago', $filtros['metodo']))
            ->when(($filtros['desde'] ?? '') !== '', fn ($query) => $query->whereDate('FechaPago', '>=', $filtros['desde']))
            ->when(($filtros['hasta'] ?? '') !== '', fn ($query) => $query->whereDate('FechaPago', '<=', $filtros['hasta']))
            ->orderByDesc('FechaPago')
            ->paginate(12)
            ->withQueryString();
    }

    public function agendaParaFactura(int $agendaId): agenda
    {
        return agenda::with([
            'cliente',
            'empleado',
            'pago',
            'agendaServicios.vehiculo.tipoVehiculo',
            'agendaServicios.servicio',
        ])->findOrFail($agendaId);
    }

    public function bloquearAgendaConfirmadaSinPago(int $agendaId): ?agenda
    {
        $agenda = agenda::whereKey($agendaId)->lockForUpdate()->first();

        if (!$agenda || $agenda->estado !== 'confirmada' || pago::where('agendaId', $agendaId)->exists()) {
            return null;
        }

        return $agenda;
    }

    public function totalServiciosDeAgenda(int $agendaId): float
    {
        return (float) agendaServicios::where('agendaId', $agendaId)->sum('Precio');
    }

    public function crear(array $datos): pago
    {
        return pago::create($datos);
    }

    public function marcarTerminada(int $agendaId): void
    {
        agenda::whereKey($agendaId)->update(['estado' => 'terminada']);
    }
}
