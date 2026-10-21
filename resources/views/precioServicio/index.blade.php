@extends('layouts.app')

@section('title')
    Precio de Servicio
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            💲 Precios de Servicio
        </h2>

    </div>

    <x-list-search :action="route('precioServicio.index')" :value="$buscar" placeholder="Servicio, tipo de vehículo o modalidad" :filters="[
        ['name' => 'tipoVehiculoId', 'label' => 'Tipo de vehículo', 'options' => ['' => 'Todos'] + $tipoVehiculos->pluck('nombre', 'id')->all(), 'value' => $filtros['tipoVehiculoId'] ?? ''],
        ['name' => 'modalidad', 'label' => 'Precio', 'options' => ['' => 'Todas', 'fijo' => 'Fijo', 'desde' => 'Desde', 'convenir' => 'A convenir'], 'value' => $filtros['modalidad'] ?? ''],
    ]" />

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-cyan-100 border-b border-cyan-200 text-cyan-800 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4">Servicio</th>
                    <th class="py-3 px-4">Tipo de vehículo</th>
                    <th class="py-3 px-4">Precio</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($precioServicio as $precio)
                <tr class="hover:bg-cyan-50">
                    <td class="px-4 py-3">{{ $precio->servicio->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $precio->tipoVehiculo->nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        @if($precio->modalidad === 'convenir')
                            A convenir
                        @elseif($precio->modalidad === 'desde')
                            Desde ${{ number_format($precio->precio, 0, ',', '.') }}
                        @else
                            ${{ number_format($precio->precio, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('precioServicio.edit', $precio->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3" title="Editar" aria-label="Editar">
                            <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                        </a>
                        <form action="{{ route('precioServicio.destroy', $precio->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar" aria-label="Eliminar"
                                onclick="return confirm('¿Desea eliminar este precio de servicio?')">
                                <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        {{ $buscar !== '' ? 'No encontramos precios con esa búsqueda.' : 'No hay precios de servicio registrados.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
<x-floating-action :href="route('precioServicio.create')" label="Nuevo precio" icon="plus" />
@endsection
