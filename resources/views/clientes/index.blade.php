@extends('layouts.app')

@section('title')
    Clientes
@endsection

@section('content')

<x-card>
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Clientes</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('clientes.create') }}"
           class="bg-pink-300 hover:bg-pink-400 text-white font-semibold px-4 py-2 rounded shadow">
           + Nuevo Cliente
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-gray-700 shadow rounded-lg">
            <thead class="bg-purple-200">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Apellido</th>
                    <th class="px-6 py-3">Teléfono</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr class="border-b hover:bg-purple-50">
                    <td class="px-6 py-3">{{ $cliente->nombreCliente }}</td>
                    <td class="px-6 py-3">{{ $cliente->apellidoCliente }}</td>
                    <td class="px-6 py-3">{{ $cliente->telefonoCliente }}</td>
                    <td class="px-6 py-3">{{ $cliente->emailCliente }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <a href="{{ route('clientes.edit', $cliente->id) }}"
                           class="bg-blue-300 hover:bg-blue-400 text-white px-3 py-1 rounded">
                           ✏️ Editar
                        </a>
                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-300 hover:bg-red-400 text-white px-3 py-1 rounded">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-card>
@endsection


