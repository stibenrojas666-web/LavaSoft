<?php

namespace App\Repositories;

use App\Models\clientes;
use App\Models\agenda;
use App\Models\vehiculo;
use App\Support\TextoBusqueda;

class clienteRepository
{
    public function listarTodo()
    {
        return clientes::query()
            ->select(['id', 'nombreCliente', 'apellidoCliente'])
            ->orderBy('apellidoCliente')
            ->orderBy('nombreCliente')
            ->get();
    }

    public function buscar(string $texto)
    {
        $consulta = clientes::query()
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
        return clientes::create($datos);
    }

    public function eliminar(int $id)
    {
        return clientes::destroy($id);
    }

    public function tieneDependencias(int $id): bool
    {
        return vehiculo::where('clienteID', $id)->exists()
            || agenda::where('clienteId', $id)->exists();
    }

    public function buscarPorId(int $id)
    {
        return clientes::findOrFail($id);
    }

    public function buscarConHistorial(int $id)
    {
        return clientes::with([
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
        $cliente = clientes::findOrFail($id);
        $cliente->update($datos);

        return $cliente;
    }
}
