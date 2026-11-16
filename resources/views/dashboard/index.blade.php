
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @php
        $nombreUsuario = explode(' ', trim(auth()->user()?->name ?? 'Administrador'))[0];
    @endphp

    {{-- Bienvenida --}}
    <section class="relative isolate overflow-hidden rounded-3xl bg-navy-900 p-6 text-white shadow-lg sm:p-8 lg:p-9">
        <div class="pointer-events-none absolute -right-12 -top-16 -z-10 h-72 w-72 rounded-full border-[35px] border-cyan-400/10"></div>
        <div class="pointer-events-none absolute -bottom-24 right-24 -z-10 h-64 w-64 rounded-full border-[25px] border-white/5"></div>

        <div class="relative max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-cyan-200">
                <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                Resumen del día
            </span>

            <h2 class="mt-5 text-2xl font-extrabold tracking-tight sm:text-3xl lg:text-4xl">
                Hola, {{ $nombreUsuario }}.
                <span class="text-cyan-300">¡Bienvenido!</span>
            </h2>

            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300 sm:text-base">
                Tu lavadero está listo para brillar. Aquí tienes el resumen de la actividad y los movimientos de hoy.
            </p>

            <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                <span class="text-slate-300">
                    <strong class="text-white">{{ number_format($citasHoy) }}</strong>
                    {{ $citasHoy == 1 ? 'cita programada hoy' : 'citas programadas hoy' }}
                </span>
                <span class="hidden h-1 w-1 rounded-full bg-slate-500 sm:block"></span>
                <span class="text-slate-300">
                    <strong class="text-white">{{ number_format($citasPendientes) }}</strong>
                    {{ $citasPendientes == 1 ? 'cita pendiente' : 'citas pendientes' }}
                </span>
            </div>

            <div class="mt-7 flex flex-wrap gap-3">
                @can('ver agenda')
                    <a href="{{ route('agenda.index') }}"
                       class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-cyan-400 px-5 text-sm font-bold text-navy-950 shadow-sm transition hover:bg-cyan-300 focus:outline-none focus:ring-4 focus:ring-cyan-300/30">
                        <span class="h-4 w-4">
    @include('layouts.partials.icons', ['icon' => 'calendar'])
</span>
                        Ver agenda
                    </a>
                @endcan

                @can('ver dashboard')
                    <a href="{{ route('reportes.diario') }}"
                       class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-5 text-sm font-semibold text-white transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/20">
                        <span class="h-4 w-4">
    @include('layouts.partials.icons', ['icon' => 'receipt'])
</span>
                        Reporte diario
                    </a>
                @endcan
            </div>
        </div>
    </section>

    {{-- Indicadores principales --}}
    <section class="mt-7">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-slate-800">Indicadores generales</h3>
                <p class="mt-1 text-sm text-slate-500">Actividad registrada en el sistema</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 sm:gap-5">
            <x-stat-card
                label="Ingresos de hoy"
                :value="'$' . number_format($cajaHoy, 0, ',', '.')"
                icon="tag"
                tone="emerald"
                hint="Pagos registrados hoy"
            />

            <x-stat-card
                label="Citas de hoy"
                :value="number_format($citasHoy)"
                icon="calendar"
                tone="cyan"
                hint="Programadas para hoy"
            />

            <x-stat-card
                label="Servicios de hoy"
                :value="number_format($serviciosHoy)"
                icon="chart"
                tone="violet"
                hint="Servicios realizados"
            />

            <x-stat-card
                label="Citas pendientes"
                :value="number_format($citasPendientes)"
                icon="bell"
                tone="amber"
                hint="Esperan confirmación"
            />
        </div>
    </section>

    {{-- Indicadores complementarios --}}
    <section class="mt-7">
        <div class="mb-4">
            <h3 class="text-base font-bold text-slate-800">Resumen del negocio</h3>
            <p class="mt-1 text-sm text-slate-500">Información general de tus registros</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 sm:gap-5">
            <x-stat-card
                label="Clientes"
                :value="number_format($clientesTotal)"
                icon="users"
                tone="cyan"
                hint="Registrados en el sistema"
            />

            <x-stat-card
                label="Vehículos registrados"
                :value="number_format($vehiculosTotal)"
                icon="box"
                tone="rose"
                hint="En todo el sistema"
            />

            <x-stat-card
                label="Empleados activos"
                :value="number_format($lavadoresActivos)"
                icon="users"
                tone="emerald"
                hint="Disponibles para atender"
            />
        </div>
    </section>

    {{-- Gráficas --}}
    <section class="mt-7">
        <div class="mb-4">
            <h3 class="text-base font-bold text-slate-800">Análisis de actividad</h3>
            <p class="mt-1 text-sm text-slate-500">Comportamiento de ingresos, citas y servicios</p>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <x-card title="Ingresos y citas" subtitle="Últimos 7 días" class="lg:col-span-2">
                <div class="relative h-72 w-full">
                    <canvas
                        id="graficaIngresos"
                        role="img"
                        aria-label="Gráfica de ingresos y citas de los últimos 7 días">
                    </canvas>
                </div>
            </x-card>

            <x-card title="Servicios más pedidos" subtitle="Los de mayor demanda">
                @if ($serviciosValuesTotal > 0)
                    <div class="relative h-72 w-full">
                        <canvas
                            id="graficaServicios"
                            role="img"
                            aria-label="Gráfica de servicios más pedidos">
                        </canvas>
                    </div>
                @else
                    <div class="flex h-72 flex-col items-center justify-center px-5 text-center">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                            <span class="h-7 w-7">
                                @include('layouts.partials.icons', ['icon' => 'chart'])
                            </span>
                        </span>
                        <p class="mt-4 text-sm font-semibold text-slate-700">
                            Sin información disponible
                        </p>
                        <p class="mt-1 max-w-xs text-xs leading-5 text-slate-500">
                            Aún no hay servicios registrados para mostrar en esta gráfica.
                        </p>
                    </div>
                @endif
            </x-card>
        </div>
    </section>

    {{-- Citas recientes --}}
    <section class="mt-7">
        <x-card title="Citas recientes" subtitle="Últimas citas registradas" :padding="false">
            <div class="lavasoft-table-shell">
                <table class="lavasoft-table min-w-[900px]">
                    <thead>
                        <tr>
                            <th scope="col">Cliente</th>
                            <th scope="col">Vehículos y servicios</th>
                            <th scope="col" class="!text-right">Total de cita</th>
                            <th scope="col">Fecha y hora</th>
                            <th scope="col">Empleado</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ultimasCitas as $cita)
                            <tr>
                                <td class="text-sm text-slate-700">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-cyan-50 text-sm font-bold text-cyan-800">
                                            {{ strtoupper(mb_substr($cita->clienteNombreCompleto, 0, 1)) }}
                                        </span>

                                        <span class="font-semibold">
                                            {{ $cita->clienteNombreCompleto }}
                                        </span>
                                    </div>
                                </td>

                                <td class="text-sm text-slate-700">
                                    @forelse ($cita->vehiculosResumen as $resumenVehiculo)
                                        <div class="mb-2 last:mb-0">
                                            <span class="font-semibold text-slate-800">
                                                {{ $resumenVehiculo['vehiculo']->placa ?? 'Sin placa' }}
                                            </span>

                                            <span class="text-slate-500">
                                                ({{ $resumenVehiculo['vehiculo']->tipoVehiculo->nombre ?? 'Vehículo' }})
                                            </span>

                                            <p class="mt-0.5 text-xs text-slate-500">
                                                {{ $resumenVehiculo['servicios'] }}
                                            </p>
                                        </div>
                                    @empty
                                        <span class="text-slate-500">Sin vehículo o servicio</span>
                                    @endforelse
                                </td>

                                <td class="whitespace-nowrap text-right text-sm font-bold text-slate-800">
                                    ${{ number_format((float) ($cita->agenda_servicios_sum_precio ?? 0), 2, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap text-sm text-slate-600">
                                    {{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                    <span class="mx-1 text-slate-300">·</span>
                                    {{ substr($cita->hora, 0, 5) }}
                                </td>

                                <td class="text-sm text-slate-700">
                                    {{ $cita->empleado ? trim($cita->empleado->nombre . ' ' . $cita->empleado->apellido) : 'Sin asignar' }}
                                </td>

                                <td>
                                    <x-badge :status="$cita->estado" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="!py-14 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                            <span class="h-7 w-7">
                                                @include('layouts.partials.icons', ['icon' => 'calendar'])
                                            </span>
                                        </span>

                                        <p class="mt-4 text-sm font-semibold text-slate-700">
                                            Todavía no hay citas registradas
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Cuando se registren citas, aparecerán en esta sección.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </section>
@endsection

@push('scripts')
<script>
    window.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart === 'undefined') {
            return;
        }

        const moneda = new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            maximumFractionDigits: 0
        });

        Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
        Chart.defaults.color = '#64748b';

        const ingresos = document.getElementById('graficaIngresos');

        if (ingresos) {
            new Chart(ingresos, {
                data: {
                    labels: @json($dias),
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Ingresos',
                            data: @json($ingresos),
                            backgroundColor: '#0e7490',
                            borderRadius: 8,
                            maxBarThickness: 36,
                            yAxisID: 'y'
                        },
                        {
                            type: 'line',
                            label: 'Citas',
                            data: @json($citasPorDia),
                            borderColor: '#f59e0b',
                            backgroundColor: '#f59e0b',
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 18
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    return context.dataset.label === 'Ingresos'
                                        ? ' Ingresos: ' + moneda.format(context.parsed.y)
                                        : ' Citas: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#edf3f6'
                            },
                            ticks: {
                                callback: (value) => moneda.format(value)
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        const servicios = document.getElementById('graficaServicios');

        if (servicios) {
            new Chart(servicios, {
                type: 'doughnut',
                data: {
                    labels: @json($serviciosLabels),
                    datasets: [{
                        data: @json($serviciosValues),
                        backgroundColor: [
                            '#0e7490',
                            '#22d3ee',
                            '#0f766e',
                            '#f59e0b',
                            '#6366f1',
                            '#f43f5e'
                        ],
                        borderWidth: 0,
                        hoverOffset: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 16
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
