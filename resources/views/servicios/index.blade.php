@extends('layouts.app')

@section('title')
    Servicios 
@endsection

@section('content')

<x-card>
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Servicios</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('servicios.create') }}"
           class="bg-pink-300 hover:bg-pink-400 text-white font-semibold px-4 py-2 rounded shadow">
           + Nuevo Servicio
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-gray-700 shadow rounded-lg">
            <thead class="bg-purple-200">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Descripcion</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicios as $servicio)
                <tr class="border-b hover:bg-purple-50">
                    <td class="px-6 py-3">{{ $servicio->nombre }}</td>
                    <td class="px-6 py-3">{{ $servicio->descripcion }}</td>
                    <td class="px-6 py-3">{{ $servicio->estado ? 'Activo' : 'Inactivo' }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <a href="{{ route('servicios.edit', $servicio->id) }}"
                           class="bg-blue-300 hover:bg-blue-400 text-white px-3 py-1 rounded">
                           ✏️ Editar
                        </a>
                        <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" onsubmit="
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-300 hover:bg-red-400 text-white px-3 py-1 rounded">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        No hay servicios registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection