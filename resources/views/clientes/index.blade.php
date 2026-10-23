@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Clientes')

@section('content')
    <x-card>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-slate-500">Directorio</p>
                <h1 class="text-2xl font-bold text-cyan-800">Clientes</h1>
            </div>
            <span class="rounded-full bg-cyan-50 px-3 py-1 text-sm font-medium text-cyan-800">{{ $clientes->total() }} encontrados</span>
        </div>

        @if(session('success'))
            <p class="mb-5 rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="mb-5 rounded-lg bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</p>
        @endif

        <x-list-search :action="route('clientes.index')" :value="$buscar" placeholder="Nombre, apellido, teléfono o correo" />

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($clientes as $cliente)
                <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-md">
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-cyan-100 p-3 text-cyan-800">
                            @include('layouts.partials.icons', ['icon' => 'user'])
                        </span>
                        <div class="min-w-0 flex-1">
                            <h2 class="truncate text-lg font-bold text-slate-900">{{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}</h2>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-400">Cliente #{{ $cliente->id }}</p>
                        </div>
                    </div>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex gap-3">
                            <dt class="w-20 shrink-0 text-slate-500">Teléfono</dt>
                            <dd class="break-all font-medium text-slate-800">{{ $cliente->telefonoCliente ?: 'Sin registrar' }}</dd>
                        </div>
                        <div class="flex gap-3">
                            <dt class="w-20 shrink-0 text-slate-500">Correo</dt>
                            <dd class="break-all font-medium text-slate-800">{{ $cliente->emailCliente ?: 'Sin registrar' }}</dd>
                        </div>
                    <div class="flex gap-3">
                        <dt class="w-36 shrink-0 text-slate-500">Vehículos registrados</dt>
                      <dd class="font-medium text-slate-800">{{ $cliente->vehiculo_count }}</dd>
                      </div>
                    </dl>

                    <div class="mt-auto flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4">
                        <a href="{{ route('clientes.show', $cliente->id) }}" class="inline-flex items-center gap-2 rounded-lg bg-cyan-50 px-3 py-2 text-sm font-semibold text-cyan-800 transition hover:bg-cyan-100" title="Historial de vehículos">
                            <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'car'])</span>
                            Vehículos
                        </a>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="grid h-9 w-9 place-items-center rounded-lg text-blue-700 transition hover:bg-blue-50" title="Editar cliente" aria-label="Editar cliente">
                                <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                            </a>
                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" onsubmit="return confirm('¿Desea eliminar este cliente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="grid h-9 w-9 place-items-center rounded-lg text-red-700 transition hover:bg-red-50" title="Eliminar cliente" aria-label="Eliminar cliente">
                                    <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center sm:col-span-2 xl:col-span-3">
                    <span class="mx-auto block h-8 w-8 text-slate-400">@include('layouts.partials.icons', ['icon' => 'users'])</span>
                    <p class="mt-3 font-medium text-slate-700">{{ $buscar !== '' ? 'No encontramos clientes con esa búsqueda.' : 'Todavía no hay clientes registrados.' }}</p>
                    @if($buscar !== '')<a href="{{ route('clientes.index') }}" class="mt-2 inline-block text-sm font-semibold text-cyan-800 hover:underline">Ver todos los clientes</a>@endif
                </div>
            @endforelse
        </div>

        @if($clientes->hasPages())<div class="mt-6">{{ $clientes->links() }}</div>@endif
    </x-card>
    <x-floating-action :href="route('clientes.create')" label="Nuevo cliente" />
@endsection
