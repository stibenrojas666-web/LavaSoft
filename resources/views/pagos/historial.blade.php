@extends('layouts.app')

@section('title', 'Historial de pagos')
@section('page-title', 'Historial de pagos')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-6 px-4 py-6">

    <x-module-header
        variant="list"
        title="Historial de pagos"
        description="Consulta y revisa los pagos registrados por cada cita."
    />

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Pagos registrados</h2>
                    <p class="mt-1 text-sm text-slate-500">Un registro por cada cita pagada.</p>
                </div>

                <div class="rounded-xl bg-cyan-50 px-4 py-2 text-sm font-semibold text-cyan-800">
                    {{ $pagos->total() }} pagos encontrados
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <x-list-search
                :action="route('pagos.historial')"
                :value="$buscar"
                placeholder="Cliente, placa, cita o método de pago"
                :filters="[
                    ['name' => 'metodo', 'label' => 'Método de pago', 'options' => ['' => 'Todos', 'Efectivo' => 'Efectivo', 'Tarjeta' => 'Tarjeta', 'Transferencia' => 'Transferencia'], 'value' => $filtros['metodo'] ?? ''],
                    ['name' => 'desde', 'label' => 'Desde', 'type' => 'date', 'value' => $filtros['desde'] ?? ''],
                    ['name' => 'hasta', 'label' => 'Hasta', 'type' => 'date', 'value' => $filtros['hasta'] ?? ''],
                ]"
            />

            <div class="mt-6 overflow-hidden rounded-xl border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="lavasoft-table min-w-[850px] text-left text-sm">
                        <thead class="bg-slate-50">
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-4 font-semibold text-slate-600">Fecha de pago</th>
                                <th class="px-4 py-4 font-semibold text-slate-600">Cliente</th>
                                <th class="px-4 py-4 font-semibold text-slate-600">Vehículos</th>
                                <th class="px-4 py-4 font-semibold text-slate-600">Cita</th>
                                <th class="px-4 py-4 font-semibold text-slate-600">Método</th>
                                <th class="px-4 py-4 text-right font-semibold text-slate-600">Monto</th>
                                <th class="px-4 py-4 text-center font-semibold text-slate-600">Factura</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($pagos as $pago)
                                @php($agenda = $pago->agenda)

                                <tr class="transition-colors hover:bg-slate-50/70">
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">
                                        {{ $pago->FechaPago?->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="font-semibold text-slate-800">
                                            {{ $agenda->clienteNombreCompleto ?? 'Sin cliente' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 text-slate-600">
                                        {{ $agenda->placasVehiculosResumen ?? 'Sin vehículo' }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="font-semibold text-slate-800">Cita #{{ $agenda->id }}</span>
                                        <span class="mt-1 block text-xs text-slate-500">
                                            {{ \Illuminate\Support\Carbon::parse($agenda->fecha)->format('d/m/Y') }}
                                            · {{ substr($agenda->hora, 0, 5) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">
                                            {{ $pago->MetodoPago }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-right font-bold tabular-nums text-slate-900">
                                        ${{ number_format((float) $pago->Monto, 2, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <a
                                            href="{{ route('agenda.factura', $agenda->id) }}"
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-100 bg-cyan-50 text-cyan-800 transition hover:border-cyan-300 hover:bg-cyan-100"
                                            title="Ver factura"
                                            aria-label="Ver factura"
                                        >
                                            <span class="h-4 w-4">
                                                @include('layouts.partials.icons', ['icon' => 'receipt'])
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-14 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center">
                                            <span class="mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                                <span class="h-7 w-7">
                                                    @include('layouts.partials.icons', ['icon' => 'receipt'])
                                                </span>
                                            </span>

                                            <p class="font-bold text-slate-800">No hay pagos para mostrar</p>
                                            <p class="mt-1 text-sm text-slate-500">
                                                {{ $buscar !== '' || collect($filtros)->contains(fn ($valor) => $valor !== '') ? 'No encontramos pagos con esos criterios.' : 'Todavía no hay pagos registrados.' }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($pagos->hasPages())
                <div class="mt-5 border-t border-slate-100 pt-5">
                    {{ $pagos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection