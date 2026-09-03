@extends('layouts.app')

@section('title')
    Empleados
@endsection

@section('content')

<x-card>
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Empleados</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('empleados.create') }}"
           class="bg-pink-300 hover:bg-pink-400 text-white font-semibold px-4 py-2 rounded shadow">
           + Nuevo Empleado
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-gray-700 shadow rounded-lg">
            <thead class="bg-purple-200">
                <tr>
                    <th class="px-6 py-3">Teléfono</th>
                    <th class="px-6 py-3">RH</th>
                    <th class="px-6 py-3">EPS</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $empleado)
                <tr class="border-b hover:bg-purple-50">
                    <td class="px-6 py-3">{{ $empleado->telefono }}</td>
                    <td class="px-6 py-3">{{ $empleado->rh }}</td>
                    <td class="px-6 py-3">{{ $empleado->eps }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <a href="{{ route('empleados.edit', $empleado->id) }}"
                           class="bg-blue-300 hover:bg-blue-400 text-white px-3 py-1 rounded">
                           ✏️ Editar
                        </a>
                        <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" onsubmit="
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
                        No hay empleados registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection