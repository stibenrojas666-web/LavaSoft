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

        <x-card title="Valor generado por servicio" subtitle="Ingresos asociados a citas terminadas dentro del período">
            @if($servicios->isNotEmpty())
                <div class="h-80">
                    <canvas id="grafico-servicios-periodo" role="img" aria-label="Gráfico del valor generado por servicio en el período"></canvas>
                </div>
            @else
                <p class="py-10 text-center text-sm text-slate-500">No hay servicios de citas terminadas para mostrar en este período.</p>
            @endif
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

@push('scripts')
    @if($servicios->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof Chart === 'undefined') return;

                const formatoPesos = valor => '$' + Number(valor).toLocaleString('es-CO');

                new Chart(document.getElementById('grafico-servicios-periodo'), {
                    type: 'bar',
                    data: {
                        labels: @json($serviciosLabels),
                        datasets: [{
                            label: 'Valor por servicio',
                            data: @json($serviciosValues),
                            backgroundColor: '#22d3ee',
                            hoverBackgroundColor: '#0891b2',
                            borderRadius: 8,
                            maxBarThickness: 34
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                padding: 12,
                                displayColors: false,
                                callbacks: { label: contexto => formatoPesos(contexto.parsed.x) }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: '#e2e8f0' },
                                ticks: {
                                    color: '#64748b',
                                    font: { family: 'Inter' },
                                    callback: valor => formatoPesos(valor)
                                }
                            },
                            y: {
                                border: { display: false },
                                grid: { display: false },
                                ticks: { color: '#64748b', font: { family: 'Inter' } }
                            }
                        }
                    }
                });
            });
        </script>
    @endif
@endpush
