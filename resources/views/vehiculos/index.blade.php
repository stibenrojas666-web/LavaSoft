@extends('layouts.app')

@section('title', 'Vehículos')
@section('page-title', 'Vehículos')

@section('content')
    <x-card>
        <x-module-header
            variant="list"
            title="Vehículos"
            description="Administra los vehículos registrados de tus clientes."
        >
            <x-slot:actions>
                <span class="inline-flex items-center rounded-full border border-cyan-100 bg-cyan-50 px-3 py-1.5 text-sm font-semibold text-cyan-800">
                    {{ $vehiculo->total() }} encontrados
                </span>
            </x-slot:actions>
        </x-module-header>

        @if(session('success'))
            <div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                <span class="font-bold">✓</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <span class="font-bold">!</span>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <x-list-search
            :action="route('vehiculos.index')"
            :value="$buscar"
            placeholder="Buscar por placa, modelo, cliente o tipo..."
            :filters="[
                [
                    'name' => 'estado',
                    'label' => 'Estado',
                    'options' => [
                        '' => 'Todos',
                        'activo' => 'Activos',
                        'inactivo' => 'Inactivos'
                    ],
                    'value' => $filtros['estado'] ?? ''
                ],
                [
                    'name' => 'tipoVehiculoId',
                    'label' => 'Tipo de vehículo',
                    'options' => ['' => 'Todos'] + $tipoVehiculosOpciones,
                    'value' => $filtros['tipoVehiculoId'] ?? ''
                ],
            ]"
        />

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($vehiculo as $v)
                @php
                    $tipoNombre = strtolower($v->tipoVehiculo->nombre ?? '');
                    $iconoVehiculo = str_contains($tipoNombre, 'moto') ? 'motorcycle' : 'car';
                    $activo = strtolower((string) $v->estado) === 'activo';
                @endphp

                <article id="vehiculo-{{ $v->id }}" class="flex h-full scroll-mt-24 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-md">

                    <div class="flex items-start gap-4 p-5">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-cyan-50 p-3 text-cyan-800">
                            @include('layouts.partials.icons', ['icon' => $iconoVehiculo])
                        </span>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Placa del vehículo
                            </p>
                            <h2 class="mt-1 truncate text-xl font-bold uppercase tracking-wide text-slate-900">
                                {{ $v->placa }}
                            </h2>
                            <p class="mt-1 text-sm font-medium text-cyan-800">
                                {{ $v->tipoVehiculo->nombre ?? 'Tipo sin registrar' }}
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $activo ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    <div class="mx-5 border-t border-slate-100"></div>

                    <dl class="flex-1 space-y-4 px-5 py-5 text-sm">
                        <div>
                            <dt class="mb-1 text-xs font-medium text-slate-400">Propietario</dt>
                            <dd class="font-semibold text-slate-800">
                                {{ $v->cliente->nombreCliente ?? 'Sin cliente' }}
                                {{ $v->cliente->apellidoCliente ?? '' }}
                            </dd>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="mb-1 text-xs font-medium text-slate-400">Modelo</dt>
                                <dd class="font-medium text-slate-700">
                                    {{ $v->modelo ?: 'Sin registrar' }}
                                </dd>
                            </div>

                            <div>
                                <dt class="mb-1 text-xs font-medium text-slate-400">Color</dt>
                                <dd class="font-medium text-slate-700">
                                    {{ $v->color ?: 'Sin registrar' }}
                                </dd>
                            </div>
                        </div>

                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-3">
                            <span class="text-xs font-medium text-slate-500">Citas relacionadas</span>
                            <span class="font-bold {{ $v->agendas_count > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                                {{ $v->agendas_count }}
                                {{ $v->agendas_count === 1 ? 'cita' : 'citas' }}
                            </span>
                        </div>
                    </dl>

                    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4">
                        <form data-preserve-scroll action="{{ route('vehiculos.estado', array_merge(['id' => $v->id], request()->query())) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                aria-label="{{ $activo ? 'Desactivar' : 'Activar' }} vehículo {{ $v->placa }}"
                                title="{{ $activo ? 'Desactivar vehículo' : 'Activar vehículo' }}"
                                class="rounded-lg border px-3 py-2 text-xs font-semibold transition {{ $activo ? 'border-red-100 bg-red-50 text-red-600 hover:bg-red-100' : 'border-emerald-100 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                            >
                                {{ $activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>

                        <div class="flex items-center gap-1">
                            <a
                                href="{{ route('vehiculos.show', $v->id) }}"
                                class="grid h-9 w-9 place-items-center rounded-lg bg-cyan-50 text-cyan-800 transition hover:bg-cyan-100"
                                title="Historial del vehículo"
                                aria-label="Historial del vehículo"
                            >
                                <span class="h-4 w-4">
                                    @include('layouts.partials.icons', ['icon' => 'history'])
                                </span>
                            </a>

                            <a
                                href="{{ route('vehiculos.edit', $v->id) }}"
                                class="grid h-9 w-9 place-items-center rounded-lg text-blue-700 transition hover:bg-blue-50"
                                title="Editar vehículo"
                                aria-label="Editar vehículo"
                            >
                                <span class="h-4 w-4">
                                    @include('layouts.partials.icons', ['icon' => 'pencil'])
                                </span>
                            </a>

                            @if($v->agendas_count > 0)
                                <button
                                    type="button"
                                    disabled
                                    class="grid h-9 w-9 cursor-not-allowed place-items-center rounded-lg text-slate-300"
                                    title="No se puede eliminar: tiene {{ $v->agendas_count }} citas relacionadas"
                                    aria-label="Vehículo con citas relacionadas"
                                >
                                    <span class="h-4 w-4">
                                        @include('layouts.partials.icons', ['icon' => 'trash'])
                                    </span>
                                </button>
                            @else
                                <form
                                    action="{{ route('vehiculos.destroy', $v->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Desea eliminar este vehículo?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="grid h-9 w-9 place-items-center rounded-lg text-red-700 transition hover:bg-red-50"
                                        title="Eliminar vehículo"
                                        aria-label="Eliminar vehículo"
                                    >
                                        <span class="h-4 w-4">
                                            @include('layouts.partials.icons', ['icon' => 'trash'])
                                        </span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-12 text-center sm:col-span-2 xl:col-span-3">
                    <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-white text-slate-400 shadow-sm">
                        <span class="h-7 w-7">
                            @include('layouts.partials.icons', ['icon' => 'car'])
                        </span>
                    </span>

                    <p class="mt-4 font-semibold text-slate-700">
                        {{ $buscar !== '' ? 'No encontramos vehículos con esa búsqueda.' : 'Todavía no hay vehículos registrados.' }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $buscar !== '' ? 'Intenta cambiar los términos o filtros de búsqueda.' : 'Los vehículos registrados aparecerán en esta sección.' }}
                    </p>

                    @if($buscar !== '')
                        <a
                            href="{{ route('vehiculos.index') }}"
                            class="mt-4 inline-flex text-sm font-semibold text-cyan-800 hover:text-cyan-950 hover:underline"
                        >
                            Ver todos los vehículos
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        @if($vehiculo->hasPages())
            <div class="mt-6 border-t border-slate-100 pt-5">
                {{ $vehiculo->links() }}
            </div>
        @endif
    </x-card>

    <x-floating-action
        :href="route('vehiculos.create')"
        label="Nuevo vehículo"
        icon="car"
    />
@endsection
