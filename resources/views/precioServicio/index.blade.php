@extends('layouts.app')

@section('title')
    Precio de Servicio
@endsection

@section('page-title', 'Precios de servicio')

@section('content')

<div class="mx-auto w-full max-w-7xl space-y-5">

    {{-- Tarjeta principal --}}
    <div class="overflow-hidden rounded-2xl border border-cyan-100 bg-white shadow-md shadow-cyan-100/40">

        {{-- Cabecera --}}
        <div class="border-b border-cyan-100 bg-gradient-to-r from-cyan-50 via-white to-sky-50 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500 text-white shadow-sm shadow-cyan-200">
                    <span class="h-5 w-5">
                        @include('layouts.partials.icons', ['icon' => 'plus'])
                    </span>
                </div>

                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        Precios registrados
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Gestiona los precios fijos, desde y a convenir.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">

            {{-- Buscador y filtros --}}
            <x-list-search
                :action="route('precioServicio.index')"
                :value="$buscar"
                placeholder="Servicio, tipo de vehículo o modalidad"
                :filters="[
                    ['name' => 'tipoVehiculoId', 'label' => 'Tipo de vehículo', 'options' => ['' => 'Todos'] + $tipoVehiculosOpciones, 'value' => $filtros['tipoVehiculoId'] ?? ''],
                    ['name' => 'modalidad', 'label' => 'Precio', 'options' => ['' => 'Todas', 'fijo' => 'Fijo', 'desde' => 'Desde', 'convenir' => 'A convenir'], 'value' => $filtros['modalidad'] ?? ''],
                ]"
            />

            {{-- Contador --}}
            <div class="mb-4 mt-5">
                <p class="text-sm text-slate-600">
                    <span class="font-bold text-cyan-700">{{ $precioServicio->total() }}</span>
                    precios de servicio encontrados
                </p>
            </div>

            {{-- Tabla --}}
            <div class="lavasoft-table-shell">
                <table class="lavasoft-table min-w-[700px] text-left text-sm">

                    <thead>
                        <tr class="bg-gradient-to-r from-cyan-600 to-sky-600 text-white">
                            <th class="px-4 py-3.5 font-semibold">Servicio</th>
                            <th class="px-4 py-3.5 font-semibold">Tipo de vehículo</th>
                            <th class="px-4 py-3.5 font-semibold">Precio</th>
                            <th class="px-4 py-3.5 text-center font-semibold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-cyan-50 text-slate-700">
                        @forelse($precioServicio as $precio)
                            <tr class="transition-colors hover:bg-cyan-50/70">

                                {{-- Servicio --}}
                                <td class="px-4 py-4">
                                    <span class="font-semibold text-slate-800">
                                        {{ $precio->servicio->nombre ?? 'N/A' }}
                                    </span>
                                </td>

                                {{-- Tipo de vehículo --}}
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-sky-100 px-3 py-1.5 text-xs font-semibold text-sky-700">
                                        {{ $precio->tipoVehiculo->nombre ?? 'N/A' }}
                                    </span>
                                </td>

                                {{-- Precio --}}
                                <td class="px-4 py-4">
                                    @if($precio->modalidad === 'convenir')
                                        <span class="inline-flex items-center rounded-lg bg-amber-100 px-3 py-1.5 font-semibold text-amber-700">
                                            A convenir
                                        </span>
                                    @elseif($precio->modalidad === 'desde')
                                        <span class="font-bold text-emerald-700">
                                            Desde ${{ number_format($precio->precio, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="font-bold text-cyan-700">
                                            ${{ number_format($precio->precio, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">

                                        <a
                                            href="{{ route('precioServicio.edit', $precio->id) }}"
                                            class="inline-grid h-9 w-9 place-items-center rounded-xl border border-blue-200 bg-blue-50 text-blue-600 transition hover:border-blue-400 hover:bg-blue-600 hover:text-white"
                                            title="Editar"
                                            aria-label="Editar"
                                        >
                                            <span class="h-4 w-4">
                                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                                            </span>
                                        </a>

                                        <form action="{{ route('precioServicio.destroy', $precio->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-grid h-9 w-9 place-items-center rounded-xl border border-rose-200 bg-rose-50 text-rose-600 transition hover:border-rose-400 hover:bg-rose-600 hover:text-white"
                                                title="Eliminar"
                                                aria-label="Eliminar"
                                                onclick="return confirm('¿Desea eliminar este precio de servicio?')"
                                            >
                                                <span class="h-4 w-4">
                                                    @include('layouts.partials.icons', ['icon' => 'trash'])
                                                </span>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-600">
                                        <span class="h-7 w-7">
                                            @include('layouts.partials.icons', ['icon' => 'plus'])
                                        </span>
                                    </div>

                                    <p class="font-semibold text-slate-700">
                                        No hay precios de servicio para mostrar
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $buscar !== '' ? 'No encontramos precios con esa búsqueda.' : 'No hay precios de servicio registrados.' }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Paginación --}}
            @if($precioServicio->hasPages())
                <div class="mt-5 border-t border-cyan-100 pt-4">
                    {{ $precioServicio->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<x-floating-action
    :href="route('precioServicio.create')"
    label="Nuevo precio"
    icon="plus"
/>

@endsection
```
