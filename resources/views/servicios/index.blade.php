@extends('layouts.app')

@section('title')
    Servicios
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            🧽 Servicios
        </h2>

    </div>

    <x-list-search :action="route('servicios.index')" :value="$buscar" placeholder="Nombre o descripción del servicio" :filters="[
        ['name' => 'estado', 'label' => 'Estado', 'options' => ['' => 'Todos', 'activo' => 'Activos', 'inactivo' => 'Inactivos'], 'value' => $filtros['estado'] ?? ''],
    ]" />

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-cyan-100 border-b border-cyan-200 text-cyan-800 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4">Nombre</th>
                    <th class="py-3 px-4">Descripción</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($servicios as $servicio)
                <tr class="hover:bg-cyan-50">
                    <td class="px-4 py-3">{{ $servicio->nombre }}</td>
                    <td class="px-4 py-3">{{ $servicio->descripcion }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <form action="{{ route('servicios.estado', array_merge(['id' => $servicio->id], request()->query())) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" role="switch" aria-checked="{{ $servicio->estado ? 'true' : 'false' }}"
                                    aria-label="{{ $servicio->estado ? 'Desactivar' : 'Activar' }} servicio {{ $servicio->nombre }}"
                                    title="{{ $servicio->estado ? 'Desactivar servicio' : 'Activar servicio' }}"
                                    class="relative inline-flex h-7 w-12 items-center rounded-full transition focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 {{ $servicio->estado ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                    <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition {{ $servicio->estado ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                            <span class="text-sm font-semibold {{ $servicio->estado ? 'text-emerald-700' : 'text-slate-500' }}">{{ $servicio->estado ? 'Activo' : 'Inactivo' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('servicios.edit', $servicio->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3" title="Editar" aria-label="Editar">
                            <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                        </a>
                        <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar" aria-label="Eliminar"
                                onclick="return confirm('¿Desea eliminar este servicio?')">
                                <span aria-hidden="true" class="inline-block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        {{ $buscar !== '' ? 'No encontramos servicios con esa búsqueda.' : 'No hay servicios registrados.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($servicios->hasPages())<div class="mt-6">{{ $servicios->links() }}</div>@endif
</x-card>
<x-floating-action :href="route('servicios.create')" label="Nuevo servicio" icon="plus" />
@endsection
