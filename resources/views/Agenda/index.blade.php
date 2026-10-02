@extends('layouts.app')

@section('title')
    Gestión de Agenda
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            📅 Agenda de Citas
        </h2>

        <a href="{{ route('agenda.create') }}"
            class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2">

            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            Nueva Cita
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
                    <th class="py-3 px-4">Vehículo</th>
                    <th class="py-3 px-4">Empleado</th>
                    <th class="py-3 px-4">Fecha</th>
                    <th class="py-3 px-4">Hora</th>
                    <th class="py-3 px-4">Atención</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                @forelse ($agenda as $a)

                <tr class="hover:bg-cyan-50">

                    <td class="px-4 py-3">{{ $a->id }}</td>

                    <td class="px-4 py-3">
                        {{ $a->cliente->nombreCliente ?? 'N/A' }}
                        {{ $a->cliente->apellidoCliente ?? '' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $a->vehiculo->placa ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $a->empleado->nombreEmpleado ?? 'Sin asignar' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3">
                        {{ substr($a->hora, 0, 5) }}
                    </td>

                    <td class="px-4 py-3">{{ $a->tipoDeAtencion }}</td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($a->estado === 'confirmada') bg-green-100 text-green-800
                            @elseif($a->estado === 'cancelada') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($a->estado) }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">

                        <a href="{{ route('agenda.edit', $a->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            ✏️
                        </a>

                        <form action="{{ route('agenda.destroy', $a->id) }}"
                            method="POST"
                            class="inline">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-red-600 hover:text-red-900"
                                onclick="return confirm('¿Desea eliminar esta cita?')">
                                🗑️
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                        No hay citas registradas.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</x-card>

@endsection