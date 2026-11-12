@extends('layouts.app')

@section('title', 'Reporte diario')
@section('page-title', 'Reporte diario')

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
            <form method="GET" action="{{ route('reportes.diario') }}" class="flex flex-wrap items-end gap-3">
                <x-input name="fecha" label="Día del reporte" type="date" :value="$fecha" />
                <x-button variant="cyan" type="submit">Consultar</x-button>
            </form>
            <x-button variant="secondary" type="button" onclick="window.print()">Imprimir / Guardar como PDF</x-button>
        </div>

        <x-card title="Citas atendidas" subtitle="{{ \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') }}">
             <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">                <x-stat-card label="Citas terminadas" :value="number_format($cantidadCitas)" icon="calendar" />
                <x-stat-card label="Recaudado en pagos del día" :value="'$' . number_format($totalRecaudado, 0, ',', '.')" icon="tag" />
                <x-stat-card label="Comisiones del día":value="'$' . number_format($totalComisiones, 0, ',', '.')"icon="tag" />
                <x-stat-card label="Recaudado después de comisiones":value="'$' . number_format((float) $ingresoDespuesComisiones, 0, ',', '.')"icon="wallet"color="emerald" />
            </div>
        </x-card>

        <x-card title="Comisiones por empleado" subtitle="Comisiones correspondientes a los pagos registrados en esta fecha">
            <x-table :headers="['Empleado', 'Pagos', 'Comisión']">
                @forelse($comisionesPorEmpleado as $fila)
                    <tr>
                        <td>
                            {{ $fila->empleado
                                ? $fila->empleado->nombre . ' ' . $fila->empleado->apellido
                                : 'Empleado no disponible' }}
                        </td>
                        <td class="text-right">
                            {{ number_format((int) $fila->cantidadPagos) }}
                        </td>
                        <td class="text-right whitespace-nowrap">
                            ${{ number_format((float) $fila->totalComision, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-500">
                            No hay comisiones registradas en esta fecha.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>

        <x-card title="Mejor empleado del día" subtitle="Según el valor generado en servicios terminados">
            @if($mejorEmpleado)
                <div class="rounded-xl border border-cyan-100 bg-cyan-50/50 p-5">
                    <p class="text-sm text-slate-500">Empleado destacado</p>

                    <h3 class="mt-1 text-xl font-bold text-cyan-900">
                        {{ $mejorEmpleado->nombre }} {{ $mejorEmpleado->apellido }}
                    </h3>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs text-slate-500">Valor generado</p>
                            <p class="text-lg font-bold text-slate-800">
                                ${{ number_format((float) $mejorEmpleado->totalGenerado, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Servicios realizados</p>
                            <p class="text-lg font-bold text-slate-800">
                                {{ number_format((int) $mejorEmpleado->cantidadServicios) }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <p class="py-8 text-center text-sm text-slate-500">
                    No hay servicios terminados con empleados asignados en esta fecha.
                </p>
            @endif
        </x-card>

        <x-card title="Servicios realizados" subtitle="Cantidad por servicio en la fecha seleccionada">
            @if($servicios->isNotEmpty())
                <div class="h-72">
                    <canvas id="grafico-servicios-diario" role="img" aria-label="Gráfico de servicios realizados en el día"></canvas>
                </div>
            @else
                <p class="py-10 text-center text-sm text-slate-500">No hay servicios de citas terminadas para mostrar en esta fecha.</p>
            @endif
        </x-card>

        <x-card title="Detalle de citas atendidas">
            <x-table :headers="['Hora', 'Cliente', 'Servicios', 'Empleado', 'Total']">
                @forelse($citas as $cita)
                    <tr>
                        <td>{{ substr($cita->hora, 0, 5) }}</td>
                        <td>{{ trim(($cita->cliente?->nombreCliente ?? '') . ' ' . ($cita->cliente?->apellidoCliente ?? '')) ?: 'Sin cliente' }}</td>
                        <td>{{ $cita->serviciosResumen }}</td>
                        <td>{{ $cita->empleado ? trim($cita->empleado->nombre . ' ' . $cita->empleado->apellido) : 'Sin asignar' }}</td>
                        <td class="text-right whitespace-nowrap">${{ number_format((float) ($cita->agenda_servicios_sum_precio ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-slate-500">No hay citas terminadas en esta fecha.</td></tr>
                @endforelse
            </x-table>
            <p class="mt-4 text-xs text-slate-500">El conteo usa la fecha de la cita terminada; el recaudo usa la fecha en que se registró cada pago.</p>
        </x-card>
    </div>
@endsection

@push('scripts')
    @if($servicios->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof Chart === 'undefined') return;

                new Chart(document.getElementById('grafico-servicios-diario'), {
                    type: 'bar',
                    data: {
                        labels: @json($serviciosLabels),
                        datasets: [{
                            label: 'Servicios realizados',
                            data: @json($serviciosValues),
                            backgroundColor: '#22d3ee',
                            hoverBackgroundColor: '#0891b2',
                            borderRadius: 8,
                            maxBarThickness: 48
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                padding: 12,
                                displayColors: false,
                                callbacks: { label: contexto => contexto.parsed.y + ' realizado(s)' }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { color: '#64748b', font: { family: 'Inter' } }
                            },
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: '#e2e8f0' },
                                ticks: { precision: 0, stepSize: 1, color: '#64748b', font: { family: 'Inter' } }
                            }
                        }
                    }
                });
            });
        </script>
    @endif
@endpush
