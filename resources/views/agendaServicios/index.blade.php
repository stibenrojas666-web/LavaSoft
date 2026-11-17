@extends('layouts.app')

@section('title', 'Historial de citas')
@section('page-title', 'Historial de citas')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-6">

    <x-module-header
        variant="list"
        title="Historial de citas"
        description="Consulta los servicios asociados a cada cita, sus vehículos y precios."
    />

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <span class="grid h-12 w-12 place-items-center rounded-xl bg-cyan-50 p-3 text-cyan-800">
                    @include('layouts.partials.icons', ['icon' => 'clipboard-list'])
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Registros del historial</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $agendaServicio->total() }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <span class="grid h-12 w-12 place-items-center rounded-xl bg-blue-50 p-3 text-blue-700">
                    @include('layouts.partials.icons', ['icon' => 'calendar'])
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Registros en esta página</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $agendaServicio->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
            <h2 class="font-bold text-slate-800">Buscar y filtrar</h2>
            <p class="mt-1 text-sm text-slate-500">Busca por cita, placa, cliente, estado o fecha.</p>
        </div>

        <div class="p-5 sm:p-6">
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
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
            <div>
                <h2 class="font-bold text-slate-800">Servicios registrados por cita</h2>
                <p class="mt-1 text-sm text-slate-500">Información de los servicios vinculados a las citas.</p>
            </div>

            <span class="rounded-full bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-800">
                {{ $agendaServicio->total() }} registros
            </span>
        </div>

        <div class="lavasoft-table-shell overflow-x-auto">
            <table class="lavasoft-table min-w-[850px] text-left text-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cita</th>
                        <th>Vehículo</th>
                        <th>Servicio</th>
                        <th>Precio</th>
                        <th class="text-center">Ver</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($agendaServicio as $as)
                        <tr>
                            <td>
                                <span class="font-bold text-slate-400">#{{ $as->id }}</span>
                            </td>

                            <td>
                                @if($as->agenda)
                                    <p class="font-bold text-slate-800">Cita #{{ $as->agenda->id }}</p>
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
                                <span class="font-bold text-emerald-700">
                                    ${{ number_format($as->Precio, 2) }}
                                </span>
                            </td>

                            <td class="text-center">
                                <a
                                    href="{{ route('agendaServicio.show', $as->id) }}"
                                    class="inline-grid h-9 w-9 place-items-center rounded-lg bg-cyan-50 text-cyan-800 transition hover:bg-cyan-100"
                                    title="Ver detalle del registro"
                                    aria-label="Ver detalle del registro #{{ $as->id }}"
                                >
                                    <span class="h-4 w-4">
                                        @include('layouts.partials.icons', ['icon' => 'eye'])
                                    </span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-cyan-50 p-3 text-cyan-700">
                                    @include('layouts.partials.icons', ['icon' => 'clipboard-list'])
                                </span>

                                <p class="mt-4 font-bold text-slate-800">
                                    {{ $buscar !== '' ? 'No encontramos resultados' : 'Todavía no hay servicios asociados' }}
                                </p>

                                <p class="mt-2 text-sm text-slate-500">
                                    Los servicios vinculados a las citas aparecerán aquí.
                                </p>
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
@endsection