
@extends('layouts.app')

@section('title', 'Servicios por Cita')

@push('styles')
<style>
    .as-page { color: #183445; }
    .as-hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 28px;
        color: white;
        background: linear-gradient(120deg, #0b2a3a 0%, #10546b 58%, #0891b2 100%);
        box-shadow: 0 14px 35px rgba(11,42,58,.16);
    }
    .as-hero::after {
        content: "";
        position: absolute;
        width: 210px;
        height: 210px;
        right: -55px;
        top: -100px;
        border: 30px solid rgba(255,255,255,.08);
        border-radius: 50%;
    }
    .as-stat {
        border: 1px solid #e1edf2;
        border-radius: 18px;
        background: white;
        padding: 18px;
        box-shadow: 0 7px 22px rgba(11,42,58,.045);
    }
    .as-panel {
        overflow: hidden;
        border: 1px solid #e1edf2;
        border-radius: 20px;
        background: white;
        box-shadow: 0 9px 26px rgba(11,42,58,.055);
    }
    .as-table {
        width: 100%;
        min-width: 850px;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }
    .as-table thead th {
        padding: 15px 17px;
        background: #f0f8fa;
        color: #527080;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .09em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .as-table tbody td {
        padding: 16px 17px;
        border-top: 1px solid #edf3f6;
        color: #526b79;
        vertical-align: middle;
    }
    .as-table tbody tr { transition: background .2s ease; }
    .as-table tbody tr:hover { background: #f0fbfd; }
    .as-icon {
        display: grid;
        width: 42px;
        height: 42px;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 14px;
        background: rgba(255,255,255,.14);
        color: white;
    }
    .as-action {
        display: inline-grid;
        width: 36px;
        height: 36px;
        place-items: center;
        border-radius: 11px;
        transition: .2s ease;
    }
    .as-edit { background: #e8f8fc; color: #087c9b; }
    .as-edit:hover { background: #c9f0f8; transform: translateY(-2px); }
    .as-delete { background: #fff0f1; color: #d04458; }
    .as-delete:hover { background: #ffe0e4; transform: translateY(-2px); }
    .as-action svg { width: 17px; height: 17px; }
    @media(max-width:640px) {
        .as-hero { padding: 22px; }
    }
</style>
@endpush

@section('content')

<div class="as-page space-y-6">

    {{-- ENCABEZADO PRINCIPAL --}}
    <section class="as-hero">
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-5">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-cyan-100">
                    <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                    Gestión de operaciones
                </div>

                <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                    Servicios por cita
                </h1>

                <p class="mt-2 max-w-xl text-sm leading-6 text-cyan-50/85">
                    Administra los servicios asociados a cada cita, consulta sus precios y mantén organizada la operación del lavadero.
                </p>
            </div>

            <div class="as-icon">
                @include('layouts.partials.icons', ['icon' => 'clipboard-list'])
            </div>
        </div>
    </section>

    {{-- INDICADOR DE REGISTROS --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="as-stat flex items-center gap-4">
            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-cyan-50 text-cyan-700">
                @include('layouts.partials.icons', ['icon' => 'clipboard-list'])
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Servicios registrados
                </p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $agendaServicio->total() }}
                </p>
            </div>
        </div>

        <div class="as-stat flex items-center gap-4">
            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-50 text-blue-700">
                @include('layouts.partials.icons', ['icon' => 'calendar'])
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Registros en esta página
                </p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $agendaServicio->count() }}
                </p>
            </div>
        </div>
    </section>

    {{-- BÚSQUEDA Y FILTROS --}}
    <section class="as-panel p-4 sm:p-5">
        <div class="mb-4">
            <h2 class="text-base font-bold text-slate-800">Buscar y filtrar</h2>
            <p class="mt-1 text-xs text-slate-500">
                Encuentra servicios por cita, placa, cliente, estado o fecha.
            </p>
        </div>

        <x-list-search
            :action="route('agendaServicio.index')"
            :value="$buscar"
            placeholder="Cita, placa, cliente o servicio"
            :filters="[
                ['name' => 'estado', 'label' => 'Estado de la cita', 'options' => ['' => 'Todos', 'pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'terminada' => 'Terminada', 'cancelada' => 'Cancelada'], 'value' => $filtros['estado'] ?? ''],
                ['name' => 'desde', 'label' => 'Desde', 'type' => 'date', 'value' => $filtros['desde'] ?? ''],
                ['name' => 'hasta', 'label' => 'Hasta', 'type' => 'date', 'value' => $filtros['hasta'] ?? ''],
            ]"
        />
    </section>

    {{-- TABLA DE SERVICIOS --}}
    <section class="as-panel">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-5">
            <div>
                <h2 class="text-base font-bold text-slate-800">
                    Servicios asociados
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Información de los servicios vinculados a las citas.
                </p>
            </div>

            <span class="rounded-full bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-800">
                {{ $agendaServicio->total() }} registros
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="as-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cita</th>
                        <th>Vehículo</th>
                        <th>Servicio</th>
                        <th>Precio</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($agendaServicio as $as)
                        <tr>
                            <td>
                                <span class="font-bold text-slate-400">
                                    #{{ $as->id }}
                                </span>
                            </td>

                            <td>
                                @if($as->agenda)
                                    <p class="font-bold text-slate-800">
                                        Cita #{{ $as->agenda->id }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($as->agenda->fecha)->format('d/m/Y') }}
                                        · {{ substr($as->agenda->hora, 0, 5) }}
                                    </p>
                                    <p class="mt-1 text-xs font-medium text-cyan-800">
                                        {{ $as->agenda->cliente->nombreCliente ?? 'N/A' }}
                                    </p>
                                @else
                                    <span class="text-slate-400">Cita no disponible</span>
                                @endif
                            </td>

                            <td>
                                <span class="inline-flex rounded-lg bg-slate-100 px-3 py-2 font-bold tracking-wide text-slate-700">
                                    {{ $as->vehiculo->placa ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                <span class="font-semibold text-slate-800">
                                    {{ $as->servicio->nombre ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                <span class="font-extrabold text-emerald-700">
                                    ${{ number_format($as->Precio, 2) }}
                                </span>
                            </td>

                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('agendaServicio.edit', $as->id) }}"
                                       class="as-action as-edit"
                                       title="Editar"
                                       aria-label="Editar">
                                        @include('layouts.partials.icons', ['icon' => 'pencil'])
                                    </a>

                                    <form action="{{ route('agendaServicio.destroy', $as->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('¿Desea quitar este servicio de la cita?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="as-action as-delete"
                                                title="Eliminar"
                                                aria-label="Eliminar">
                                            @include('layouts.partials.icons', ['icon' => 'trash'])
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="mb-4 grid h-16 w-16 place-items-center rounded-2xl bg-cyan-50 text-cyan-700">
                                        @include('layouts.partials.icons', ['icon' => 'clipboard-list'])
                                    </div>

                                    <p class="font-bold text-slate-800">
                                        {{ $buscar !== '' ? 'No encontramos resultados' : 'Todavía no hay servicios asociados' }}
                                    </p>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Los servicios vinculados a las citas aparecerán aquí.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agendaServicio->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $agendaServicio->links() }}
            </div>
        @endif
    </section>

</div>

<x-floating-action
    :href="route('agendaServicio.create')"
    label="Agregar servicio"
    icon="plus"
/>

@endsection
