@extends('layouts.app')

@section('title')
    Clientes
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            🧑‍🤝‍🧑 Clientes
        </h2>

        <a href="{{ route('clientes.create') }}"
            class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Cliente
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-cyan-100 border-b border-cyan-200 text-cyan-800 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4">Nombre</th>
                    <th class="py-3 px-4">Apellido</th>
                    <th class="py-3 px-4">Teléfono</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($clientes as $cliente)
                <tr class="hover:bg-cyan-50">
                    <td class="px-4 py-3">{{ $cliente->nombreCliente }}</td>
                    <td class="px-4 py-3">{{ $cliente->apellidoCliente }}</td>
                    <td class="px-4 py-3">{{ $cliente->telefonoCliente }}</td>
                    <td class="px-4 py-3">{{ $cliente->emailCliente }}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('clientes.edit', $cliente->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            ✏️
                        </a>
                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('¿Desea eliminar este cliente?')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                        No hay clientes registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection