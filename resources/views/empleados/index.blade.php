
@extends('layouts.app')


@section('title', 'Empleados')
@section('page-title', 'Empleados')

@section('content')

<style>
    .empleado-page {
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
    }

    .empleado-stat {
        border: 1px solid #dce8f0;
        border-radius: 15px;
        background: white;
        padding: 17px;
        box-shadow: 0 5px 16px rgb(11 42 58 / 4%);
    }

    .empleado-card {
        display: flex;
        height: 100%;
        flex-direction: column;
        border: 1px solid #dce8f0;
        border-radius: 17px;
        background: white;
        padding: 20px;
        box-shadow: 0 5px 16px rgb(11 42 58 / 4%);
        transition: .2s ease;
    }

    .empleado-card:hover {
        transform: translateY(-3px);
        border-color: #a5dbe5;
        box-shadow: 0 12px 25px rgb(11 42 58 / 9%);
    }

    .empleado-info-label {
        color: #8193a2;
        font-size: 12px;
    }

    .empleado-info-value {
        color: #17394d;
        font-size: 13px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .empleado-btn-nuevo {
        display: inline-flex;
        height: 38px;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 16px;
        border-radius: 9px;
        background: linear-gradient(110deg, #087b98, #06364c);
        color: white;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .empleado-btn-nuevo:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }

    .empleado-empty {
        border: 1px dashed #cbdce6;
        border-radius: 15px;
        background: #f8fbfd;
        padding: 40px 20px;
        text-align: center;
    }
</style>

<div class="empleado-page">

    {{-- Encabezado --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">
                Equipo de trabajo
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Consulta y administra la información de los empleados del lavadero.
            </p>
        </div>

        <a href="{{ route('empleados.create') }}" class="empleado-btn-nuevo">
            <span class="h-4 w-4">
                @include('layouts.partials.icons', ['icon' => 'plus'])
            </span>
            Nuevo empleado
        </a>
    </div>

    {{-- Resumen --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

        <div class="empleado-stat flex items-center gap-4">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-cyan-50 text-cyan-800">
                @include('layouts.partials.icons', ['icon' => 'employee'])
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">
                    Empleados encontrados
                </p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $empleados->total() }}
                </p>
            </div>
        </div>

        <div class="empleado-stat flex items-center gap-4">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-sky-50 text-sky-800">
                @include('layouts.partials.icons', ['icon' => 'users'])
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">
                    Registros en esta página
                </p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $empleados->count() }}
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
                <h2 class="text-base font-extrabold text-slate-800">
                    Listado de empleados
                </h2>
                <p class="mt-1 text-xs text-slate-400">
                    Información laboral y acciones de cada integrante del equipo.
                </p>
            </div>

            <span class="rounded-full border border-cyan-100 bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-800">
                {{ $empleados->total() }} registros
            </span>
        </div>

        {{-- Búsqueda y filtros --}}
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

        {{-- Tarjetas --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

            @forelse($empleados as $empleado)

                <article class="empleado-card">

                    {{-- Identidad --}}
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-cyan-50 p-3 text-cyan-800">
                            @include('layouts.partials.icons', ['icon' => 'employee'])
                        </span>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-lg font-extrabold text-slate-800">
                                {{ $empleado->nombre }} {{ $empleado->apellido }}
                            </h3>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Empleado #{{ $empleado->id }}
                            </p>
                        </div>
                    </div>

                    {{-- Información --}}
                    <div class="my-5 grid grid-cols-2 gap-x-3 gap-y-5">

                        <div class="min-w-0">
                            <p class="empleado-info-label">Identificación</p>
                            <p class="mt-1 truncate empleado-info-value">
                                {{ $empleado->identificacion }}
                            </p>
                        </div>

                        <div class="min-w-0">
                            <p class="empleado-info-label">Teléfono</p>
                            <p class="mt-1 truncate empleado-info-value">
                                {{ $empleado->telefono }}
                            </p>
                        </div>

                        <div class="min-w-0">
                            <p class="empleado-info-label">Tipo de sangre</p>
                            <p class="mt-1 empleado-info-value">
                                {{ $empleado->rh ?: 'Sin registrar' }}
                            </p>
                        </div>

                        <div class="min-w-0">
                            <p class="empleado-info-label">EPS</p>
                            <p class="mt-1 truncate empleado-info-value">
                                {{ $empleado->eps ?: 'Sin registrar' }}
                            </p>
                        </div>

                    </div>

                    {{-- Comisión --}}
                    <div class="mb-5 flex items-center justify-between rounded-xl bg-cyan-50/70 px-3 py-3">
                        <span class="text-xs font-semibold text-slate-600">
                            Comisión asignada
                        </span>
                        <span class="rounded-lg bg-white px-3 py-1 text-sm font-extrabold text-cyan-800 shadow-sm">
                            {{ number_format((float) $empleado->porcentajeComision, 2, ',', '.') }}%
                        </span>
                    </div>

                    
{{-- Acciones --}}
<div class="mt-auto flex items-center justify-between gap-3 border-t border-slate-100 pt-4">

    <div class="flex items-center gap-2">
        <span class="h-2 w-2 rounded-full {{ $empleado->estado ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>

        <span class="text-xs font-semibold {{ $empleado->estado ? 'text-emerald-700' : 'text-slate-500' }}">
            {{ $empleado->estado ? 'Activo' : 'Inactivo' }}
        </span>

        <form action="{{ route('empleados.estado', $empleado->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <button
                type="submit"
                class="rounded-lg border px-2.5 py-1 text-xs font-semibold transition
                {{ $empleado->estado
                    ? 'border-red-100 bg-red-50 text-red-600 hover:bg-red-100'
                    : 'border-emerald-100 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
            >
                {{ $empleado->estado ? 'Desactivar' : 'Activar' }}
            </button>
        </form>
    </div>

    <div class="flex items-center gap-1">
        <a
            href="{{ route('empleados.edit', $empleado->id) }}"
            class="grid h-8 w-8 place-items-center rounded-lg text-blue-700 transition hover:bg-blue-50"
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
                class="grid h-8 w-8 place-items-center rounded-lg text-red-500 transition hover:bg-red-50"
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

        {{-- Paginación --}}
        @if($empleados->hasPages())
            <div class="mt-6">
                {{ $empleados->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
