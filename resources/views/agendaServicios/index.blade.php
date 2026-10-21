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

    </div>

    <x-list-search :action="route('agendaServicio.index')" :value="$buscar" placeholder="Cita, placa, cliente o servicio" :filters="[
        ['name' => 'estado', 'label' => 'Estado de la cita', 'options' => ['' => 'Todos', 'pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'terminada' => 'Terminada', 'cancelada' => 'Cancelada'], 'value' => $filtros['estado'] ?? ''],
        ['name' => 'desde', 'label' => 'Desde', 'type' => 'date', 'value' => $filtros['desde'] ?? ''],
        ['name' => 'hasta', 'label' => 'Hasta', 'type' => 'date', 'value' => $filtros['hasta'] ?? ''],
    ]" />

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-cyan-100 border-b border-cyan-200 text-cyan-800 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Cita</th>
                    <th class="py-3 px-4">Vehículo</th>
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

                    <td class="px-4 py-3">{{ $as->vehiculo->placa ?? 'N/A' }}</td>

                    <td class="px-4 py-3">{{ $as->servicio->nombre ?? 'N/A' }}</td>

                    <td class="px-4 py-3">${{ number_format($as->Precio, 2) }}</td>

                    <td class="px-4 py-3 text-center">

                        <a href="{{ route('agendaServicio.edit', $as->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3" title="Editar" aria-label="Editar">
                            <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                        </a>

                        <form action="{{ route('agendaServicio.destroy', $as->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-red-600 hover:text-red-900" title="Eliminar" aria-label="Eliminar"
                                onclick="return confirm('¿Desea quitar este servicio de la cita?')">
                                <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        {{ $buscar !== '' ? 'No encontramos servicios con esa búsqueda.' : 'No hay servicios asignados a citas.' }}
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>
    @if($agendaServicio->hasPages())<div class="mt-6">{{ $agendaServicio->links() }}</div>@endif

</x-card>
<x-floating-action :href="route('agendaServicio.create')" label="Agregar servicio" icon="plus" />

@endsection
