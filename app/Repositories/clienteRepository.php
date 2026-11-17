<?php

namespace App\Repositories;

use App\Models\Agenda;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Repositories\Contracts\ClienteRepositoryInterface;
use App\Support\TextoBusqueda;
use Illuminate\Support\Facades\DB;

class ClienteRepository implements ClienteRepositoryInterface
{
    public function listarTodo()
    {
        return Cliente::query()
            ->where('activo', true)
            ->select(['id', 'nombreCliente', 'apellidoCliente'])
            ->orderBy('apellidoCliente')
            ->orderBy('nombreCliente')
            ->get();
    }

    public function buscar(string $texto, bool $archivados = false)
    {
        $consulta = Cliente::query()
            ->where('activo', ! $archivados)
            ->select([
                'id',
                'nombreCliente',
                'apellidoCliente',
                'telefonoCliente',
                'emailCliente',
            ])
            ->withCount('vehiculo');

        return TextoBusqueda::aplicar(
            $consulta,
            $texto,
            fn ($filtros, $termino) => $filtros
                ->where('nombreCliente', 'like', $termino)
                ->orWhere('apellidoCliente', 'like', $termino)
                ->orWhere('telefonoCliente', 'like', $termino)
                ->orWhere('emailCliente', 'like', $termino)
        )
            ->orderBy('apellidoCliente')
            ->orderBy('nombreCliente')
            ->paginate(10)
            ->withQueryString();
    }

    public function guardar(array $datos)
    {
        return Cliente::create($datos);
    }

    public function eliminar(int $id)
    {
        return Cliente::destroy($id);
    }

    public function archivar(int $id): int
    {
        return Cliente::whereKey($id)->update(['activo' => false]);
    }

    public function reactivar(int $id): int
    {
        return Cliente::whereKey($id)->update(['activo' => true]);
    }

    public function eliminarTodo(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $cliente = Cliente::query()->lockForUpdate()->findOrFail($id);
            $agendaIds = Agenda::where('clienteId', $id)->pluck('id');
            $vehiculoIds = Vehiculo::where('clienteID', $id)->pluck('id');

            DB::table('pagos')->whereIn('agendaId', $agendaIds)->delete();
            DB::table('agenda_servicios')
                ->whereIn('agendaId', $agendaIds)
                ->orWhereIn('vehiculoId', $vehiculoIds)
                ->delete();
            Agenda::whereIn('id', $agendaIds)->delete();
            Vehiculo::whereIn('id', $vehiculoIds)->delete();

            return $cliente->delete();
        });
    }

    public function tieneDependencias(int $id): bool
    {
        return Vehiculo::where('clienteID', $id)->exists()
            || Agenda::where('clienteId', $id)->exists();
    }

    public function buscarPorId(int $id)
    {
        return Cliente::findOrFail($id);
    }

    public function buscarConHistorial(int $id)
    {
        return Cliente::with([
            'vehiculo.tipoVehiculo',
            'vehiculo.agendaServicios' => function ($query) {
                $query->whereHas(
                    'agenda',
                    fn ($agenda) => $agenda->where('estado', 'terminada')
                )
                    ->with(['agenda.empleado', 'agenda.pago', 'servicio'])
                    ->orderByDesc('agendaId')
                    ->orderByDesc('id');
            },
        ])->findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($datos);

        return $cliente;
    }
}
