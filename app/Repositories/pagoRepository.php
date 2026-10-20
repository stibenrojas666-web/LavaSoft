<?php

namespace App\Repositories;

use App\Models\pago;
use App\Models\agenda;

class PagoRepository
{
    public function getAll()
    {
        return pago::with('agenda.cliente')->get();
    }

    public function keep(array $datos)
    {
        return pago::create($datos);
    }

    public function agendasSinPago()
    {
        return agenda::with(['cliente', 'vehiculo.tipoVehiculo', 'agendaServicios.servicio'])
            ->whereDoesntHave('pago')
            ->where('estado', '!=', 'cancelada')
            ->whereHas('agendaServicios')
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();
    }

    public function agendaDisponibleParaPago(int $agendaId, bool $bloquear = false): ?agenda
    {
        $consulta = agenda::with(['cliente', 'vehiculo.tipoVehiculo', 'agendaServicios.servicio'])
            ->whereDoesntHave('pago')
            ->where('estado', '!=', 'cancelada')
            ->whereHas('agendaServicios')
            ->whereKey($agendaId);

        if ($bloquear) {
            $consulta->lockForUpdate();
        }

        return $consulta->first();
    }

    public function totalServiciosDeAgenda(int $agendaId): float
    {
        return (float) \App\Models\agendaServicios::where('agendaId', $agendaId)->sum('Precio');
    }

    public function confirmarAgenda(int $agendaId): void
    {
        agenda::whereKey($agendaId)->update(['estado' => 'confirmada']);
    }

    public function findById(int $id)
    {
        return pago::with(['agenda.cliente', 'agenda.vehiculo.tipoVehiculo', 'agenda.agendaServicios.servicio'])
            ->findOrFail($id);
    }

    public function update(int $id, array $datos)
    {
        $pago = pago::findOrFail($id);
        $pago->update($datos);
        return $pago;
    }

    public function delete(int $id)
    {
        return pago::destroy($id);
    }
}
