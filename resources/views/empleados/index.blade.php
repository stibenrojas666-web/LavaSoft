@extends('layouts.app')

@section('title', 'Empleados')
@section('page-title', 'Empleados')

@section('content')
    <x-card>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-slate-500">Equipo de trabajo</p>
                <h1 class="text-2xl font-bold text-cyan-800">Empleados</h1>
            </div>
            <span class="rounded-full bg-cyan-50 px-3 py-1 text-sm font-medium text-cyan-800">{{ $empleados->total() }} encontrados</span>
        </div>

        @if(session('success'))
            <p class="mb-5 rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="mb-5 rounded-lg bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</p>
        @endif

        <x-list-search :action="route('empleados.index')" :value="$buscar" placeholder="Nombre, apellido, identificación, teléfono o correo" :filters="[
            ['name' => 'estado', 'label' => 'Estado', 'options' => ['' => 'Todos', 'activo' => 'Activos', 'inactivo' => 'Inactivos'], 'value' => $filtros['estado'] ?? ''],
        ]" />

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($empleados as $empleado)
                <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-md">
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-amber-100 p-3 text-amber-800">
                            @include('layouts.partials.icons', ['icon' => 'employee'])
                        </span>
                        <div class="min-w-0 flex-1">
                            <h2 class="truncate text-lg font-bold text-slate-900">{{ $empleado->nombre }} {{ $empleado->apellido }}</h2>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-400">Empleado #{{ $empleado->id }}</p>
                        </div>
                    </div>

                    <dl class="mt-5 grid grid-cols-2 gap-x-3 gap-y-4 text-sm">
                        <div class="min-w-0">
                            <dt class="text-xs text-slate-500">Identificación</dt>
                            <dd class="mt-1 truncate font-medium text-slate-800">{{ $empleado->identificacion }}</dd>
                        </div>
                        <div class="min-w-0">
                            <dt class="text-xs text-slate-500">Teléfono</dt>
                            <dd class="mt-1 truncate font-medium text-slate-800">{{ $empleado->telefono }}</dd>
                        </div>
                        <div class="min-w-0">
                            <dt class="text-xs text-slate-500">Tipo de sangre</dt>
                            <dd class="mt-1 font-medium text-slate-800">{{ $empleado->rh ?: 'Sin registrar' }}</dd>
                        </div>
                        <div class="min-w-0">
                            <dt class="text-xs text-slate-500">EPS</dt>
                            <dd class="mt-1 truncate font-medium text-slate-800">{{ $empleado->eps ?: 'Sin registrar' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                        <div class="flex items-center gap-2">
                            <form action="{{ route('empleados.estado', $empleado->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" role="switch" aria-checked="{{ $empleado->estado ? 'true' : 'false' }}"
                                    aria-label="{{ $empleado->estado ? 'Desactivar' : 'Activar' }} a {{ $empleado->nombre }} {{ $empleado->apellido }}"
                                    title="{{ $empleado->estado ? 'Desactivar empleado' : 'Activar empleado' }}"
                                    class="relative inline-flex h-7 w-12 items-center rounded-full transition focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 {{ $empleado->estado ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                    <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition {{ $empleado->estado ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                            <span class="text-sm font-semibold {{ $empleado->estado ? 'text-emerald-700' : 'text-slate-500' }}">
                                {{ $empleado->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('empleados.edit', $empleado->id) }}" class="grid h-9 w-9 place-items-center rounded-lg text-blue-700 transition hover:bg-blue-50" title="Editar empleado" aria-label="Editar empleado">
                                <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                            </a>
                            <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" onsubmit="return confirm('¿Desea eliminar este empleado?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="grid h-9 w-9 place-items-center rounded-lg text-red-700 transition hover:bg-red-50" title="Eliminar empleado" aria-label="Eliminar empleado">
                                    <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center sm:col-span-2 xl:col-span-3">
                    <span class="mx-auto block h-8 w-8 text-slate-400">@include('layouts.partials.icons', ['icon' => 'employee'])</span>
                    <p class="mt-3 font-medium text-slate-700">{{ $buscar !== '' ? 'No encontramos empleados con esa búsqueda.' : 'Todavía no hay empleados registrados.' }}</p>
                    @if($buscar !== '')<a href="{{ route('empleados.index') }}" class="mt-2 inline-block text-sm font-semibold text-cyan-800 hover:underline">Ver todos los empleados</a>@endif
                </div>
            @endforelse
        </div>

        @if($empleados->hasPages())<div class="mt-6">{{ $empleados->links() }}</div>@endif
    </x-card>
    <x-floating-action :href="route('empleados.create')" label="Nuevo empleado" icon="employee" />
@endsection
