@extends('layouts.app')

@section('title', 'Tipos de vehículos')
@section('page-title', 'Tipos de vehículos')

@section('content')
    <div class="mx-auto w-full max-w-7xl space-y-5">


        {{-- Contenido --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="px-5 pt-5 sm:px-6">
                <x-module-header variant="list" title="Tipos de vehículos" description="Consulta, modifica o elimina los tipos de vehículos." />
            </div>

            <div class="p-5 sm:p-6">

                {{-- Buscador --}}
                <x-list-search
                    :action="route('tipoVehiculo.index')"
                    :value="$buscar"
                    placeholder="Nombre del tipo de vehículo"
                />

                {{-- Contador --}}
                <div class="mb-4 mt-5 flex items-center justify-between gap-3">
                    <p class="text-sm text-slate-600">
                        <span class="font-bold text-cyan-700">{{ $tipoVehiculos->total() }}</span>
                        tipos de vehículos encontrados
                    </p>

                    <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-700">
                        Listado
                    </span>
                </div>

                {{-- Tabla --}}
                <div class="lavasoft-table-shell">
                    <table class="lavasoft-table min-w-[550px] text-left text-sm">

                        <thead class="bg-gradient-to-r from-cyan-600 to-sky-600 text-white">
                            <tr>
                                <th class="px-4 py-3.5 font-semibold">
                                    Nombre
                                </th>
                                <th class="px-4 py-3.5 text-center font-semibold">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-cyan-50">
                            @forelse($tipoVehiculos as $tipoVehiculo)
                                <tr class="transition-colors hover:bg-cyan-50/70">

                                    {{-- Nombre --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
                                                <span class="h-4 w-4">
                                                    @include('layouts.partials.icons', ['icon' => 'car'])
                                                </span>
                                            </div>

                                            <span class="font-semibold text-slate-800">
                                                {{ $tipoVehiculo->nombre }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">

                                            <a
                                                href="{{ route('tipoVehiculo.edit', $tipoVehiculo->id) }}"
                                                class="inline-grid h-9 w-9 place-items-center rounded-xl border border-blue-200 bg-blue-50 text-blue-600 transition hover:border-blue-400 hover:bg-blue-600 hover:text-white"
                                                title="Editar tipo de vehículo"
                                                aria-label="Editar tipo de vehículo"
                                            >
                                                <span class="h-4 w-4">
                                                    @include('layouts.partials.icons', ['icon' => 'pencil'])
                                                </span>
                                            </a>

                                            <form
                                                action="{{ route('tipoVehiculo.destroy', $tipoVehiculo->id) }}"
                                                method="POST"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-grid h-9 w-9 place-items-center rounded-xl border border-rose-200 bg-rose-50 text-rose-600 transition hover:border-rose-400 hover:bg-rose-600 hover:text-white"
                                                    title="Eliminar tipo de vehículo"
                                                    aria-label="Eliminar tipo de vehículo"
                                                    onclick="return confirm('¿Desea eliminar este tipo de vehículo?')"
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
                                    <td colspan="2" class="px-4 py-12 text-center">
                                        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-600">
                                            <span class="h-7 w-7">
                                                @include('layouts.partials.icons', ['icon' => 'car'])
                                            </span>
                                        </div>

                                        <p class="font-semibold text-slate-700">
                                            No hay tipos de vehículos para mostrar
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $buscar !== '' ? 'No encontramos tipos de vehículo con esa búsqueda.' : 'No hay tipos de vehículo registrados.' }}
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Paginación --}}
                @if($tipoVehiculos->hasPages())
                    <div class="mt-5 border-t border-cyan-100 pt-4">
                        {{ $tipoVehiculos->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <x-floating-action
        :href="route('tipoVehiculo.create')"
        label="Nuevo tipo de vehículo"
        icon="car"
    />
@endsection
```
