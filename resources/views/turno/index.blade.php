@extends('layouts.app')

@section('title', 'Turnos')
@section('page-title', 'Turnos')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-6">

    <x-module-header variant="list" title="Turnos del equipo" description="Administra los horarios de trabajo de tus empleados." />

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-cyan-50 text-cyan-800">
                    <span class="text-2xl">◷</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Turnos encontrados</p>
                    <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $turnos->total() }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-sky-50 text-sky-800">
                    <span class="text-2xl">▦</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">En esta página</p>
                    <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $turnos->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
            <h2 class="font-bold text-slate-800">Listado de turnos</h2>
            <p class="mt-1 text-sm text-slate-500">Busca empleados y filtra los horarios por día o jornada.</p>

            <form action="{{ route('turnos.index') }}" method="GET" class="mt-5 space-y-3">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div>
                        <label for="buscar" class="mb-1.5 block text-xs font-semibold text-slate-600">Buscar</label>
                        <input
                            type="search"
                            name="buscar"
                            id="buscar"
                            value="{{ $buscar }}"
                            placeholder="Empleado, identificación u horario"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100"
                        >
                    </div>

                    <div>
                        <label for="dia" class="mb-1.5 block text-xs font-semibold text-slate-600">Día</label>
                        <select
                            name="dia"
                            id="dia"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100"
                        >
                            <option value="">Todos los días</option>
                            @foreach(['lunes' => 'Lunes', 'martes' => 'Martes', 'miercoles' => 'Miércoles', 'jueves' => 'Jueves', 'viernes' => 'Viernes', 'sabado' => 'Sábado', 'domingo' => 'Domingo'] as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(($filtros['dia'] ?? '') === $valor)>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="jornada" class="mb-1.5 block text-xs font-semibold text-slate-600">Jornada</label>
                        <select
                            name="jornada"
                            id="jornada"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100"
                        >
                            <option value="">Todas las jornadas</option>
                            <option value="mañana" @selected(($filtros['jornada'] ?? '') === 'mañana')>Mañana</option>
                            <option value="tarde" @selected(($filtros['jornada'] ?? '') === 'tarde')>Tarde</option>
                            <option value="noche" @selected(($filtros['jornada'] ?? '') === 'noche')>Noche</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-2">
                    <a href="{{ route('turnos.index') }}"
                       class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Limpiar filtros
                    </a>
                    <button type="submit"
                            class="rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        <div class="grid gap-4 p-5 sm:grid-cols-2 sm:p-6 xl:grid-cols-3">
            @forelse($turnos as $turno)
                <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-cyan-200 hover:shadow-md">

                    <div class="flex items-start gap-4">
                        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-cyan-50 text-cyan-800">
                            <span class="text-2xl">◷</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-lg font-extrabold text-slate-800">
                                {{ $turno->empleado->nombre ?? 'Sin empleado' }}
                                {{ $turno->empleado->apellido ?? '' }}
                            </h3>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-400">
                                {{ $turno->empleado->identificacion ?? 'Identificación no disponible' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Día</p>
                            <p class="mt-1 font-bold capitalize text-slate-800">{{ $turno->dia }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jornada</p>
                            <p class="mt-1 font-bold capitalize text-slate-800">{{ $turno->jornada }}</p>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between gap-3 rounded-xl border border-cyan-100 bg-cyan-50 px-4 py-3">
                        <span class="text-sm font-semibold text-cyan-900">Horario</span>
                        <span class="text-sm font-extrabold text-cyan-900">
                            {{ substr($turno->horaInicio, 0, 5) }} – {{ substr($turno->horaFin, 0, 5) }}
                        </span>
                    </div>

                    <div class="mt-auto flex justify-end gap-2 border-t border-slate-100 pt-4 mt-5">
                        <a
                            href="{{ route('turnos.edit', $turno->id) }}"
                            class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-blue-700 transition hover:bg-blue-100"
                            title="Editar turno"
                            aria-label="Editar turno"
                        >
                            <span class="h-4 w-4">
                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                            </span>
                        </a>

                        <form
                            action="{{ route('turnos.destroy', $turno->id) }}"
                            method="POST"
                            onsubmit="return confirm('¿Desea eliminar este turno?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="grid h-10 w-10 place-items-center rounded-xl bg-red-50 text-red-700 transition hover:bg-red-100"
                                title="Eliminar turno"
                                aria-label="Eliminar turno"
                            >
                                <span class="h-4 w-4">
                                    @include('layouts.partials.icons', ['icon' => 'trash'])
                                </span>
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-12 text-center sm:col-span-2 xl:col-span-3">
                    <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-white text-2xl text-slate-400">
                        ◷
                    </div>
                    <h3 class="mt-4 font-bold text-slate-700">
                        {{ $buscar !== '' ? 'No encontramos turnos con esa búsqueda.' : 'Todavía no hay turnos registrados.' }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">Puedes registrar un nuevo horario para un empleado.</p>

                    @if($buscar !== '' || !empty($filtros))
                        <a href="{{ route('turnos.index') }}" class="mt-3 inline-block text-sm font-bold text-cyan-800 hover:underline">
                            Limpiar búsqueda
                        </a>
                    @else
                    @endif
                </div>
            @endforelse
        </div>

        @if($turnos->hasPages())
            <div class="border-t border-slate-100 px-5 py-5 sm:px-6">
                {{ $turnos->withQueryString()->links() }}
            </div>
        @endif
    </div>
    <x-floating-action :href="route('turnos.create')" label="Registrar turno" icon="plus" />
</div>
@endsection
