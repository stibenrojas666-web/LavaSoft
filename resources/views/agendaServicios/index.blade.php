@extends('layouts.app')

@section('title')
    Servicios por Cita
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            🧽 Servicios por Cita
        </h2>

        <a href="{{ route('agendaServicio.create') }}"
            class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Agregar Servicio
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
                    <th class="py-3 px-4">Cita</th>
                    <th class="py-3 px-4">Servicio</th>
                    <th class="py-3 px-4">Precio</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                @forelse ($agendaServicio as $as)

                <tr class="hover:bg-cyan-50">

                    <td class="px-4 py-3">{{ $as->id }}</td>

                    <td class="px-4 py-3">
                        #{{ $as->agenda->id ?? 'N/A' }}
                        @if($as->agenda)
                            - {{ \Carbon\Carbon::parse($as->agenda->fecha)->format('d/m/Y') }}
                            {{ substr($as->agenda->hora, 0, 5) }}
                            - {{ $as->agenda->cliente->nombreCliente ?? 'N/A' }}
                        @endif
                    </td>

                    <td class="px-4 py-3">{{ $as->servicio->nombre ?? 'N/A' }}</td>

                    <td class="px-4 py-3">${{ number_format($as->Precio, 2) }}</td>

                    <td class="px-4 py-3 text-center">

                        <a href="{{ route('agendaServicio.edit', $as->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            ✏️
                        </a>

                        <form action="{{ route('agendaServicio.destroy', $as->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-red-600 hover:text-red-900"
                                onclick="return confirm('¿Desea quitar este servicio de la cita?')">
                                🗑️
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                        No hay servicios asignados a citas.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</x-card>

@endsection