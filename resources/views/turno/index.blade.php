@extends('layouts.app')

@section('title')
    Turnos
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            🕒 Turnos
        </h2>

        <a href="{{ route('turnos.create') }}"
            class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Turno
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
                <tr class="bg-cyan-10n0 border-b border-cyan-200 text-cyan-800 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4">Empleado</th>
                    <th class="py-3 px-4">Identificacion</th>
                    <th class="py-3 px-4">Día</th>
                    <th class="py-3 px-4">Jornada</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($turnos as $turno)
                <tr class="hover:bg-cyan-50">
                    <td class="px-4 py-3">{{ $turno->empleado->nombre ?? 'N/A' }} {{ $turno->empleado->apellido ?? '' }}</td>
                    <td class="px-4 py-3">{{ $turno->empleado->identificacion ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ ucfirst($turno->dia) }}</td>
                    <td class="px-4 py-3">{{ ucfirst($turno->jornada) }}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('turnos.edit', $turno->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            ✏️
                        </a>
                        <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('¿Desea eliminar este turno?')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        No hay turnos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection