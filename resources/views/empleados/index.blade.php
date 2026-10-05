@extends('layouts.app')

@section('title')
    Empleados
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            👷 Empleados
        </h2>

        <a href="{{ route('empleados.create') }}"
            class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Empleado
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
                    <th class="py-3 px-4">Identificación</th>
                    <th class="py-3 px-4">Teléfono</th>
                    <th class="py-3 px-4">Rh</th>
                    <th class="py-3 px-4">Eps</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($empleados as $empleado)
                <tr class="hover:bg-cyan-50">
                    <td class="px-4 py-3">{{ $empleado->nombre }}</td>
                    <td class="px-4 py-3">{{ $empleado->apellido }}</td>
                    <td class="px-4 py-3">{{ (int) $empleado->identificacion }}</td>
                    <td class="px-4 py-3">{{ (int) $empleado->telefono }}</td>
                    <td class="px-4 py-3">{{ $empleado->rh }}</td>
                    <td class="px-4 py-3">{{ $empleado->eps }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($empleado->estado) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $empleado->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('empleados.edit', $empleado->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            ✏️
                        </a>
                        <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('¿Desea eliminar este empleado?')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        No hay empleados registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection