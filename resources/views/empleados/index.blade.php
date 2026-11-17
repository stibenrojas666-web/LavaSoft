@extends('layouts.app')

@section('title', 'Empleados')
@section('page-title', 'Empleados')

@section('content')
<style>
    .empleado-page{width:100%;max-width:1150px;margin:auto}
    .empleado-stat,.empleado-card{border:1px solid #dce8f0;background:#fff;box-shadow:0 5px 16px rgb(11 42 58 / 5%)}
    .empleado-stat{padding:18px;border-radius:16px}
    .empleado-card{display:flex;height:100%;flex-direction:column;padding:20px;border-radius:18px;transition:.2s ease}
    .empleado-card:hover{transform:translateY(-3px);border-color:#a5dbe5;box-shadow:0 12px 25px rgb(11 42 58 / 9%)}
    .empleado-info-label{color:#8193a2;font-size:12px}
    .empleado-info-value{overflow-wrap:anywhere;color:#17394d;font-size:13px;font-weight:600}
    .empleado-empty{padding:40px 20px;border:1px dashed #cbdce6;border-radius:16px;background:#f8fbfd;text-align:center}
    .empleado-status{display:inline-flex;align-items:center;gap:7px;border-radius:30px;padding:6px 10px;font-size:11px;font-weight:700}
    .empleado-status.activo{background:#ecfdf5;color:#047857}
    .empleado-status.inactivo{background:#f1f5f9;color:#64748b}
    .empleado-icon{display:grid;width:48px;height:48px;flex-shrink:0;place-items:center;border-radius:14px;background:#e4f9fc;color:#087e99}
</style>

<div class="empleado-page">

    <x-module-header
        variant="list"
        title="Equipo de trabajo"
        description="Consulta y administra la información de los empleados del lavadero."
    />

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="empleado-stat flex items-center gap-4">
            <div class="empleado-icon">
                @include('layouts.partials.icons', ['icon' => 'employee'])
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Empleados encontrados</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $empleados->total() }}</p>
            </div>
        </div>

        <div class="empleado-stat flex items-center gap-4">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-sky-50 text-sky-800">
                @include('layouts.partials.icons', ['icon' => 'users'])
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Registros en esta página</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $empleados->count() }}</p>
            </div>
        </div>
    </div>

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

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-extrabold text-slate-800">Listado de empleados</h2>
                <p class="mt-1 text-xs text-slate-400">Información laboral y acciones de cada integrante del equipo.</p>
            </div>

            <span class="rounded-full border border-cyan-100 bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-800">
                {{ $empleados->total() }} registros
            </span>
        </div>

        <div class="mb-6">
            <x-list-search
                :action="route('empleados.index')"
                :value="$buscar"
                placeholder="Nombre, apellido, identificación, teléfono o correo"
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
                ]"
            />
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($empleados as $empleado)
                <article id="empleado-{{ $empleado->id }}" class="empleado-card scroll-mt-24">

                    <div class="flex items-start gap-4">
                        <span class="empleado-icon">
                            @include('layouts.partials.icons', ['icon' => 'employee'])
                        </span>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-lg font-extrabold text-slate-800">
                                {{ $empleado->nombre }} {{ $empleado->apellido }}
                            </h3>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Empleado #{{ $empleado->id }}
                            </p>

                            <span class="empleado-status mt-2 {{ $empleado->estado ? 'activo' : 'inactivo' }}">
                                <span class="h-2 w-2 rounded-full {{ $empleado->estado ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $empleado->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>

                    <div class="my-5 grid grid-cols-2 gap-x-3 gap-y-5">
                        <div class="min-w-0">
                            <p class="empleado-info-label">Identificación</p>
                            <p class="mt-1 truncate empleado-info-value">{{ $empleado->identificacion }}</p>
                        </div>

                        <div class="min-w-0">
                            <p class="empleado-info-label">Teléfono</p>
                            <p class="mt-1 truncate empleado-info-value">{{ $empleado->telefono }}</p>
                        </div>

                        <div class="min-w-0">
                            <p class="empleado-info-label">Tipo de sangre</p>
                            <p class="mt-1 empleado-info-value">{{ $empleado->rh ?: 'Sin registrar' }}</p>
                        </div>

                        <div class="min-w-0">
                            <p class="empleado-info-label">EPS</p>
                            <p class="mt-1 truncate empleado-info-value">{{ $empleado->eps ?: 'Sin registrar' }}</p>
                        </div>
                    </div>

                    <div class="mb-5 flex items-center justify-between rounded-xl bg-cyan-50/70 px-3 py-3">
                        <span class="text-xs font-semibold text-slate-600">Comisión asignada</span>
                        <span class="rounded-lg bg-white px-3 py-1 text-sm font-extrabold text-cyan-800 shadow-sm">
                            {{ number_format((float) $empleado->porcentajeComision, 2, ',', '.') }}%
                        </span>
                    </div>

                    <div class="mt-auto flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">

                        <form data-preserve-scroll action="{{ route('empleados.estado', array_merge(['id' => $empleado->id], request()->query())) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="rounded-lg border px-3 py-2 text-xs font-semibold transition
                                {{ $empleado->estado
                                    ? 'border-red-100 bg-red-50 text-red-600 hover:bg-red-100'
                                    : 'border-emerald-100 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                            >
                                {{ $empleado->estado ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>

                        <div class="flex items-center gap-1">
                            <a
                                href="{{ route('empleados.edit', $empleado->id) }}"
                                class="grid h-9 w-9 place-items-center rounded-xl text-blue-700 transition hover:bg-blue-50"
                                title="Editar empleado"
                                aria-label="Editar empleado"
                            >
                                <span class="h-4 w-4">
                                    @include('layouts.partials.icons', ['icon' => 'pencil'])
                                </span>
                            </a>

                            <form
                                action="{{ route('empleados.destroy', $empleado->id) }}"
                                method="POST"
                                onsubmit="return confirm('¿Desea eliminar este empleado?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="grid h-9 w-9 place-items-center rounded-xl text-red-600 transition hover:bg-red-50"
                                    title="Eliminar empleado"
                                    aria-label="Eliminar empleado"
                                >
                                    <span class="h-4 w-4">
                                        @include('layouts.partials.icons', ['icon' => 'trash'])
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empleado-empty sm:col-span-2 xl:col-span-3">
                    <span class="mx-auto block h-10 w-10 text-slate-400">
                        @include('layouts.partials.icons', ['icon' => 'employee'])
                    </span>

                    <h3 class="mt-3 font-bold text-slate-700">
                        {{ $buscar !== '' ? 'No encontramos empleados con esa búsqueda.' : 'Todavía no hay empleados registrados.' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $buscar !== '' ? 'Intenta con otro nombre, identificación o teléfono.' : 'Comienza registrando tu primer empleado.' }}
                    </p>

                    @if($buscar !== '')
                        <a
                            href="{{ route('empleados.index') }}"
                            class="mt-3 inline-block text-sm font-bold text-cyan-800 hover:underline"
                        >
                            Ver todos los empleados
                        </a>
                    @else
                        <a
                            href="{{ route('empleados.create') }}"
                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-800"
                        >
                            @include('layouts.partials.icons', ['icon' => 'plus'])
                            Registrar empleado
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        @if($empleados->hasPages())
            <div class="mt-6 border-t border-slate-100 pt-5">
                {{ $empleados->links() }}
            </div>
        @endif
    </div>
    <x-floating-action :href="route('empleados.create')" label="Nuevo empleado" icon="plus" />
</div>
@endsection
