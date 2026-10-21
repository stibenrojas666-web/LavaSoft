@extends('layouts.app')

@section('title')
    Tipo de Vehiculo
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            🚙 Tipos de Vehículo
        </h2>

    </div>

    <x-list-search :action="route('tipoVehiculo.index')" :value="$buscar" placeholder="Nombre del tipo de vehículo" />

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-cyan-100 border-b border-cyan-200 text-cyan-800 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4">Nombre</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($tipoVehiculos as $tipoVehiculo)
                <tr class="hover:bg-cyan-50">
                    <td class="px-4 py-3">{{ $tipoVehiculo->nombre }}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('tipoVehiculo.edit', $tipoVehiculo->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3" title="Editar" aria-label="Editar">
                            <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                        </a>
                        <form action="{{ route('tipoVehiculo.destroy', $tipoVehiculo->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar" aria-label="Eliminar"
                                onclick="return confirm('¿Desea eliminar este tipo de vehículo?')">
                                <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-6 text-center text-gray-500">
                        {{ $buscar !== '' ? 'No encontramos tipos de vehículo con esa búsqueda.' : 'No hay tipos de vehículo registrados.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tipoVehiculos->hasPages())<div class="mt-6">{{ $tipoVehiculos->links() }}</div>@endif
</x-card>
<x-floating-action :href="route('tipoVehiculo.create')" label="Nuevo tipo de vehículo" icon="car" />
@endsection
