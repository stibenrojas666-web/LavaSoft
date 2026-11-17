<?php

namespace App\Repositories;

use App\Repositories\Contracts\ClienteRepositoryInterface;

use App\Models\Cliente;
use App\Models\Agenda;
use App\Models\Vehiculo;
use App\Support\TextoBusqueda;

class ClienteRepository implements ClienteRepositoryInterface {
    public function listarTodo()
    {
        return Cliente::query()
            ->select(['id', 'nombreCliente', 'apellidoCliente'])
            ->orderBy('apellidoCliente')
            ->orderBy('nombreCliente')
            ->get();
    }

    public function buscar(string $texto)
    {
        $consulta = Cliente::query()
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
