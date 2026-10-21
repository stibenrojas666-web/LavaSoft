@extends('layouts.app')

@section('title', 'Turnos')
@section('page-title', 'Turnos')

@section('content')
    <x-card>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-slate-500">Horario del equipo</p>
                <h1 class="text-2xl font-bold text-cyan-800">Turnos</h1>
            </div>
            <span class="rounded-full bg-cyan-50 px-3 py-1 text-sm font-medium text-cyan-800">{{ $turnos->total() }} encontrados</span>
        </div>

        @if(session('success'))<p class="mb-5 rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</p>@endif
        @if(session('error'))<p class="mb-5 rounded-lg bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</p>@endif

        <x-list-search :action="route('turnos.index')" :value="$buscar" placeholder="Empleado, identificación, día, jornada u hora" :filters="[
            ['name' => 'dia', 'label' => 'Día', 'options' => ['' => 'Todos', 'lunes' => 'Lunes', 'martes' => 'Martes', 'miercoles' => 'Miércoles', 'jueves' => 'Jueves', 'viernes' => 'Viernes', 'sabado' => 'Sábado', 'domingo' => 'Domingo'], 'value' => $filtros['dia'] ?? ''],
            ['name' => 'jornada', 'label' => 'Jornada', 'options' => ['' => 'Todas', 'mañana' => 'Mañana', 'tarde' => 'Tarde', 'noche' => 'Noche'], 'value' => $filtros['jornada'] ?? ''],
        ]" />

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($turnos as $turno)
                <article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-md">
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-indigo-100 p-3 text-indigo-800">
                            @include('layouts.partials.icons', ['icon' => 'clock'])
                        </span>
                        <div class="min-w-0 flex-1">
                            <h2 class="truncate text-lg font-bold text-slate-900">{{ $turno->empleado->nombre ?? 'Sin empleado' }} {{ $turno->empleado->apellido ?? '' }}</h2>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-400">{{ $turno->empleado->identificacion ?? 'Identificación no disponible' }}</p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Día</p>
                            <p class="mt-1 font-semibold capitalize text-slate-900">{{ $turno->dia }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Jornada</p>
                            <p class="mt-1 font-semibold capitalize text-slate-900">{{ $turno->jornada }}</p>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between rounded-xl border border-cyan-100 bg-cyan-50 px-4 py-3">
                        <span class="text-sm font-medium text-cyan-900">Horario</span>
                        <span class="font-semibold text-cyan-900">{{ substr($turno->horaInicio, 0, 5) }} – {{ substr($turno->horaFin, 0, 5) }}</span>
                    </div>

                    <div class="mt-auto flex justify-end gap-1 border-t border-slate-100 pt-4">
                        <a href="{{ route('turnos.edit', $turno->id) }}" class="grid h-9 w-9 place-items-center rounded-lg text-blue-700 transition hover:bg-blue-50" title="Editar turno" aria-label="Editar turno">
                            <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'pencil'])</span>
                        </a>
                        <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST" onsubmit="return confirm('¿Desea eliminar este turno?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="grid h-9 w-9 place-items-center rounded-lg text-red-700 transition hover:bg-red-50" title="Eliminar turno" aria-label="Eliminar turno">
                                <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'trash'])</span>
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center sm:col-span-2 xl:col-span-3">
                    <span class="mx-auto block h-8 w-8 text-slate-400">@include('layouts.partials.icons', ['icon' => 'clock'])</span>
                    <p class="mt-3 font-medium text-slate-700">{{ $buscar !== '' ? 'No encontramos turnos con esa búsqueda.' : 'Todavía no hay turnos registrados.' }}</p>
                    @if($buscar !== '')<a href="{{ route('turnos.index') }}" class="mt-2 inline-block text-sm font-semibold text-cyan-800 hover:underline">Ver todos los turnos</a>@endif
                </div>
            @endforelse
        </div>

        @if($turnos->hasPages())<div class="mt-6">{{ $turnos->links() }}</div>@endif
    </x-card>
    <x-floating-action :href="route('turnos.create')" label="Nuevo turno" icon="clock" />
@endsection
