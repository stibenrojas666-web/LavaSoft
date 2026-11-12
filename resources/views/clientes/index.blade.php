
@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Clientes')

@section('content')
@include('clientes.partials.estilos')

<div class="cliente-page">

    <x-module-header variant="list" title="Directorio de clientes" description="Consulta y administra la información de tus clientes.">
        <x-slot:actions>
        <a href="{{ route('clientes.create') }}" class="cliente-btn-nuevo">
            <span class="h-4 w-4">
                    @include('layouts.partials.icons', ['icon' => 'plus'])
            </span>
         Nuevo cliente
        </a>
        </x-slot:actions>
    </x-module-header>

    {{-- Resumen --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="cliente-stat flex items-center gap-4">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-cyan-50 text-cyan-800">
                @include('layouts.partials.icons', ['icon' => 'users'])
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Clientes encontrados</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $clientes->total() }}
                </p>
            </div>
        </div>

        <div class="cliente-stat flex items-center gap-4">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-sky-50 text-sky-800">
                @include('layouts.partials.icons', ['icon' => 'car'])
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Registros en esta página</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $clientes->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            <span class="text-lg">✓</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <span class="text-lg">!</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Directorio --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-extrabold text-slate-800">Listado de clientes</h2>
                <p class="mt-1 text-xs text-slate-400">
                    Información y acciones de cada cliente registrado.
                </p>
            </div>

            <span class="rounded-full border border-cyan-100 bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-800">
                {{ $clientes->total() }} registros
            </span>
        </div>

        <div class="mb-6">
            <x-list-search
                :action="route('clientes.index')"
                :value="$buscar"
                placeholder="Nombre, apellido, teléfono o correo"
            />
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

            @forelse($clientes as $cliente)

                <article class="cliente-card">

                    {{-- Datos principales --}}
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-cyan-50 p-3 text-cyan-800">
                            @include('layouts.partials.icons', ['icon' => 'user'])
                        </span>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-lg font-extrabold text-slate-800">
                                {{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}
                            </h3>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Cliente #{{ $cliente->id }}
                            </p>
                        </div>
                    </div>

                    {{-- Información --}}
                    <div class="my-5 space-y-4">

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 h-4 w-4 shrink-0 text-cyan-700">
                                @include('layouts.partials.icons', ['icon' => 'user'])
                            </span>
                            <div class="min-w-0">
                                <p class="cliente-info-label">Teléfono</p>
                                <p class="cliente-info-value">
                                    {{ $cliente->telefonoCliente ?: 'Sin registrar' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 h-4 w-4 shrink-0 text-cyan-700">
                                @include('layouts.partials.icons', ['icon' => 'user'])
                            </span>
                            <div class="min-w-0">
                                <p class="cliente-info-label">Correo electrónico</p>
                                <p class="cliente-info-value">
                                    {{ $cliente->emailCliente ?: 'Sin registrar' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between rounded-xl bg-cyan-50/70 px-3 py-3">
                            <span class="text-xs font-semibold text-slate-600">
                                Vehículos registrados
                            </span>
                            <span class="rounded-lg bg-white px-3 py-1 text-sm font-extrabold text-cyan-800 shadow-sm">
                                {{ $cliente->vehiculo_count }}
                            </span>
                        </div>

                    </div>

                    {{-- Acciones --}}
                    <div class="mt-auto flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4">

                        <a href="{{ route('clientes.show', $cliente->id) }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-cyan-50 px-3 py-2 text-xs font-bold text-cyan-800 transition hover:bg-cyan-100"
                           title="Consultar vehículos e historial">
                            <span class="h-4 w-4">
                                @include('layouts.partials.icons', ['icon' => 'car'])
                            </span>
                            Vehículos
                        </a>

                        <div class="flex items-center gap-1">

                            <a href="{{ route('clientes.edit', $cliente->id) }}"
                               class="grid h-9 w-9 place-items-center rounded-xl text-blue-700 transition hover:bg-blue-50"
                               title="Editar cliente" aria-label="Editar cliente">
                                <span class="h-4 w-4">
                                    @include('layouts.partials.icons', ['icon' => 'pencil'])
                                </span>
                            </a>

                            <form action="{{ route('clientes.destroy', $cliente->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Desea eliminar este cliente?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="grid h-9 w-9 place-items-center rounded-xl text-red-600 transition hover:bg-red-50"
                                        title="Eliminar cliente" aria-label="Eliminar cliente">
                                    <span class="h-4 w-4">
                                        @include('layouts.partials.icons', ['icon' => 'trash'])
                                    </span>
                                </button>
                            </form>

                        </div>
                    </div>

                </article>

            @empty

                <div class="cliente-empty sm:col-span-2 xl:col-span-3">
                    <span class="mx-auto block h-10 w-10 text-slate-400">
                        @include('layouts.partials.icons', ['icon' => 'users'])
                    </span>

                    <h3 class="mt-3 font-bold text-slate-700">
                        {{ $buscar !== '' ? 'No encontramos clientes con esa búsqueda.' : 'Todavía no hay clientes registrados.' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $buscar !== '' ? 'Intenta con otro nombre, teléfono o correo.' : 'Comienza registrando tu primer cliente.' }}
                    </p>

                    @if($buscar !== '')
                        <a href="{{ route('clientes.index') }}"
                           class="mt-3 inline-block text-sm font-bold text-cyan-800 hover:underline">
                            Ver todos los clientes
                        </a>
                    @else
                        <a href="{{ route('clientes.create') }}"
                           class="mt-4 inline-flex items-center gap-2 rounded-lg bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-800">
                            @include('layouts.partials.icons', ['icon' => 'plus'])
                            Registrar cliente
                        </a>
                    @endif
                </div>

            @endforelse

        </div>

        {{-- Paginación --}}
        @if($clientes->hasPages())
            <div class="mt-6 border-t border-slate-100 pt-5">
                {{ $clientes->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
