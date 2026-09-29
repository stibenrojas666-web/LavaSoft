@extends('layouts.app')

@section('title')
    Gestión de Vehículos
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            🚗 Lista de Vehículos
        </h2>

        <a href="{{ route('vehiculos.create') }}"
            class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2">

            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            Nuevo Vehículo
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
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Cliente</th>
                    <th class="py-3 px-4">Tipo Vehículo</th>
                    <th class="py-3 px-4">Placa</th>
                    <th class="py-3 px-4">Modelo</th>
                    <th class="py-3 px-4">Color</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                @forelse ($vehiculo as $v)

                <tr class="hover:bg-cyan-50">

                    <td class="px-4 py-3">{{ $v->id }}</td>

                    <td class="px-4 py-3">
                        {{ $v->cliente->nombreCliente ?? 'N/A' }}
                        {{ $v->cliente->apellidoCliente ?? '' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $v->tipoVehiculo->nombre ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-3">{{ $v->placa }}</td>

                    <td class="px-4 py-3">{{ $v->modelo }}</td>

                    <td class="px-4 py-3">{{ $v->color }}</td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $v->estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $v->estado }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">

                        <a href="{{ route('vehiculos.edit', $v->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            ✏️
                        </a>

                        <form action="{{ route('vehiculos.destroy', $v->id) }}"
                            method="POST"
                            class="inline">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-red-600 hover:text-red-900"
                                onclick="return confirm('¿Desea eliminar este vehículo?')">
                                🗑️
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                        No hay vehículos registrados.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</x-card>

@endsection