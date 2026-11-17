@extends('layouts.app')

@section('title', 'Tipos de vehículos')
@section('page-title', 'Tipos de vehículos')

@section('content')
    <div class="mx-auto w-full max-w-7xl space-y-5">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="px-5 pt-5 sm:px-6">
                <x-module-header
                    variant="list"
                    title="Tipos de vehículos"
                    description="Administra las categorías de vehículos disponibles."
                />
            </div>

            <div class="p-5 sm:p-6">
                <x-list-search
                    :action="route('tipoVehiculo.index')"
                    :value="$buscar"
                    placeholder="Buscar tipo de vehículo..."
                />

                <div class="mb-4 mt-5 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-slate-600">
                        <span class="font-bold text-cyan-800">{{ $tipoVehiculos->total() }}</span>
                        tipos de vehículos registrados
                    </p>
                    <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-800">
                        Catálogo
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($tipoVehiculos as $tipoVehiculo)
                        <article class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-md">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-cyan-50 p-3 text-cyan-800">
                                    @include('layouts.partials.icons', ['icon' => 'car'])
                                </span>

                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-slate-400">Tipo de vehículo</p>
                                    <h2 class="mt-1 truncate font-bold text-slate-800">
                                        {{ $tipoVehiculo->nombre }}
                                    </h2>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-1">
                                <a
                                    href="{{ route('tipoVehiculo.edit', $tipoVehiculo->id) }}"
                                    class="grid h-9 w-9 place-items-center rounded-lg text-blue-700 transition hover:bg-blue-50"
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
                                    onsubmit="return confirm('¿Desea eliminar este tipo de vehículo?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="grid h-9 w-9 place-items-center rounded-lg text-red-700 transition hover:bg-red-50"
                                        title="Eliminar tipo de vehículo"
                                        aria-label="Eliminar tipo de vehículo"
                                    >
                                        <span class="h-4 w-4">
                                            @include('layouts.partials.icons', ['icon' => 'trash'])
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-12 text-center sm:col-span-2 lg:col-span-3">
                            <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-white p-3 text-slate-400 shadow-sm">
                                @include('layouts.partials.icons', ['icon' => 'car'])
                            </span>

                            <p class="mt-4 font-semibold text-slate-700">
                                No hay tipos de vehículos para mostrar
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $buscar !== '' ? 'No encontramos tipos de vehículo con esa búsqueda.' : 'Todavía no hay tipos de vehículo registrados.' }}
                            </p>

                            @if($buscar !== '')
                                <a
                                    href="{{ route('tipoVehiculo.index') }}"
                                    class="mt-4 inline-flex text-sm font-semibold text-cyan-800 hover:underline"
                                >
                                    Limpiar búsqueda
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if($tipoVehiculos->hasPages())
                    <div class="mt-5 border-t border-slate-100 pt-4">
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