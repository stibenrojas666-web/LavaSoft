@extends('layouts.app')

@section('title', 'Reporte por período')
@section('page-title', 'Reporte semanal y mensual')

@push('styles')
    <style>
        @media print {
            body * { visibility: hidden !important; }
            #reporte-print, #reporte-print * { visibility: visible !important; }
            #reporte-print { position: absolute; inset: 0 auto auto 0; width: 100%; max-width: none; }
            .no-print { display: none !important; }
            body, #reporte-print { background: #fff !important; color: #111827 !important; }
        }
    </style>
@endpush

@section('content')
    <div id="reporte-print" class="space-y-6">
        <div class="no-print flex flex-wrap items-end justify-between gap-4">
            <form method="GET" action="{{ route('reportes.periodo') }}" class="flex flex-wrap items-end gap-3">
                <x-input name="desde" label="Desde" type="date" :value="$desde" />
                <x-input name="hasta" label="Hasta" type="date" :value="$hasta" />
                <x-button variant="cyan" type="submit">Consultar</x-button>
            </form>
            <div class="flex flex-wrap gap-2">
                <x-button variant="secondary" :href="route('reportes.periodo', $rangoSemana)">Esta semana</x-button>
                <x-button variant="secondary" :href="route('reportes.periodo', $rangoMes)">Este mes</x-button>
                <x-button variant="secondary" type="button" onclick="window.print()">Imprimir / Guardar como PDF</x-button>
            </div>
        </div>

        <x-card title="Resumen del período" subtitle="{{ \Illuminate\Support\Carbon::parse($desde)->format('d/m/Y') }} al {{ \Illuminate\Support\Carbon::parse($hasta)->format('d/m/Y') }}">
            <div class="grid gap-4 sm:grid-cols-3">
                <x-stat-card label="Citas atendidas" :value="number_format($citasAtendidas)" icon="calendar" />
                <x-stat-card label="Recaudado por fecha de pago" :value="'$' . number_format($totalRecaudado, 0, ',', '.')" icon="tag" />
                <x-stat-card label="Valor de servicios terminados" :value="'$' . number_format($totalServicios, 0, ',', '.')" icon="chart" />
            </div>
        </x-card>

        <x-card title="Totales por servicio">
            <x-table :headers="['Servicio', 'Cantidad', 'Total']">
                @forelse($servicios as $fila)
                    <tr>
                        <td>{{ $fila->servicio?->nombre ?? 'Servicio eliminado' }}</td>
                        <td class="text-right">{{ number_format((int) $fila->cantidad) }}</td>
                        <td class="text-right whitespace-nowrap">${{ number_format((float) $fila->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="py-8 text-center text-slate-500">No hay servicios en citas terminadas dentro del rango.</td></tr>
                @endforelse
            </x-table>
            <p class="mt-4 text-xs text-slate-500">Los totales por servicio se calculan con los precios guardados en las citas terminadas según la fecha de la cita.</p>
        </x-card>
    </div>
@endsection
