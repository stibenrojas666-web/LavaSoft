@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @php($nombreUsuario = explode(' ', trim(auth()->user()?->name ?? 'Administrador'))[0])

    {{-- Banner de bienvenida --}}
    <section class="relative overflow-hidden rounded-3xl bg-navy-900 p-6 text-white shadow-[0_16px_40px_rgba(11,42,58,0.25)] sm:p-8">
        <svg class="pointer-events-none absolute -right-6 -top-6 h-64 w-64 text-cyan-300/25" viewBox="0 0 260 260" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="90" cy="150" r="60"/><circle cx="190" cy="80" r="38"/><circle cx="215" cy="185" r="26"/><circle cx="140" cy="215" r="14"/><circle cx="235" cy="40" r="10"/>
        </svg>
        <div class="relative max-w-2xl">
            <p class="text-sm font-semibold text-cyan-300">Resumen del día</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight sm:text-3xl">Hola, {{ $nombreUsuario }}. Tu lavadero está listo para brillar.</h2>
            <p class="mt-3 text-sm text-slate-300 sm:text-base">
                Hoy hay <strong class="text-white">{{ number_format($citasHoy) }}</strong> {{ $citasHoy == 1 ? 'cita' : 'citas' }}
                y <strong class="text-white">{{ number_format($citasPendientes) }}</strong> {{ $citasPendientes == 1 ? 'cita pendiente' : 'citas pendientes' }} en total.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                @can('ver agenda')
                    <a href="{{ route('agenda.index') }}" class="inline-flex min-h-[44px] items-center rounded-xl bg-cyan-400 px-5 text-sm font-bold text-navy-950 transition hover:bg-cyan-300 focus:outline-none focus:ring-4 focus:ring-cyan-300/40">Ver agenda</a>
                @endcan
                @can('ver dashboard')
                    <a href="{{ route('reportes.diario') }}" class="inline-flex min-h-[44px] items-center rounded-xl bg-white/10 px-5 text-sm font-semibold text-white ring-1 ring-white/20 transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/30">Reporte diario</a>
                @endcan
            </div>
        </div>
    </section>

    {{-- Indicadores --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 sm:gap-6">
        <x-stat-card label="Ingresos de hoy" :value="'$' . number_format($cajaHoy, 0, ',', '.')" icon="tag" tone="emerald" hint="Pagos registrados hoy" />
        <x-stat-card label="Citas de hoy" :value="number_format($citasHoy)" icon="calendar" tone="cyan" hint="Programadas para hoy" />
        <x-stat-card label="Servicios de hoy" :value="number_format($serviciosHoy)" icon="chart" tone="violet" hint="Servicios realizados" />
        <x-stat-card label="Citas pendientes" :value="number_format($citasPendientes)" icon="bell" tone="amber" hint="Esperan confirmación" />
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 sm:mt-6 sm:gap-6">
        <x-stat-card label="Clientes" :value="number_format($clientesTotal)" icon="users" tone="cyan" hint="Registrados en el sistema" />
        <x-stat-card label="Vehículos registrados" :value="number_format($vehiculosTotal)" icon="box" tone="rose" hint="En todo el sistema" />
        <x-stat-card label="Empleados activos" :value="number_format($lavadoresActivos)" icon="users" tone="emerald" hint="Disponibles para atender" />
    </div>

    {{-- Gráficas --}}
    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3 sm:gap-6">
        <x-card title="Ingresos y citas" subtitle="Últimos 7 días" class="lg:col-span-2">
            <div class="h-72"><canvas id="graficaIngresos" role="img" aria-label="Gráfica de ingresos y citas de los últimos 7 días"></canvas></div>
        </x-card>

        <x-card title="Servicios más pedidos" subtitle="Los de mayor demanda">
            @if (collect($serviciosValues)->sum() > 0)
                <div class="h-72"><canvas id="graficaServicios" role="img" aria-label="Gráfica de servicios más pedidos"></canvas></div>
            @else
                <p class="grid h-72 place-items-center text-center text-sm text-slate-500">Aún no hay servicios registrados para mostrar.</p>
            @endif
        </x-card>
    </div>

    {{-- Citas recientes --}}
    <div class="mt-6">
        <x-card title="Citas recientes" subtitle="Últimas citas registradas" :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full">
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
                            @php($nombreCliente = trim(($cita->cliente->nombreCliente ?? '') . ' ' . ($cita->cliente->apellidoCliente ?? '')) ?: 'Sin cliente')
                            <tr>
                                <td class="text-sm text-slate-700">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-cyan-100 text-xs font-bold text-cyan-800">{{ strtoupper(mb_substr($nombreCliente, 0, 1)) }}</span>
                                        <span class="font-semibold">{{ $nombreCliente }}</span>
                                    </div>
                                </td>
                                <td class="text-sm text-slate-700">
                                    @forelse($cita->agendaServicios->groupBy('vehiculoId') as $lineas)
                                        @php($vehiculoCita = $lineas->first()->vehiculo)
                                        <div class="mb-2 last:mb-0">
                                            <span class="font-semibold">{{ $vehiculoCita->placa ?? 'Sin placa' }}</span>
                                            <span class="text-slate-500">({{ $vehiculoCita->tipoVehiculo->nombre ?? 'Vehículo' }})</span>
                                            <p class="text-xs text-slate-500">{{ $lineas->pluck('servicio.nombre')->filter()->implode(', ') }}</p>
                                        </div>
                                    @empty
                                        <span class="text-slate-500">Sin vehículo o servicio</span>
                                    @endforelse
                                </td>
                                <td class="whitespace-nowrap text-right text-sm font-bold text-slate-800">
                                    ${{ number_format((float) ($cita->agenda_servicios_sum_precio ?? 0), 2, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-slate-600">
                                    {{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d/m/Y') }} · {{ substr($cita->hora, 0, 5) }}
                                </td>
                                <td class="text-sm text-slate-700">
                                    {{ $cita->empleado ? trim($cita->empleado->nombre . ' ' . $cita->empleado->apellido) : 'Sin asignar' }}
                                </td>
                                <td><x-badge :status="$cita->estado" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="!py-12 text-center text-sm text-slate-500">Todavía no hay citas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
@endsection

@push('scripts')
<script>
    window.addEventListener('DOMContentLoaded', function () {
        const moneda = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 });
        Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
        Chart.defaults.color = '#4a6372';

        const ingresos = document.getElementById('graficaIngresos');
        if (ingresos) {
            new Chart(ingresos, {
                data: {
                    labels: @json($dias),
                    datasets: [
                        { type: 'bar', label: 'Ingresos', data: @json($ingresos), backgroundColor: '#0e7490', borderRadius: 8, maxBarThickness: 36, yAxisID: 'y' },
                        { type: 'line', label: 'Citas', data: @json($citasPorDia), borderColor: '#f59e0b', backgroundColor: '#f59e0b', tension: .35, pointRadius: 4, yAxisID: 'y1' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                        tooltip: { callbacks: { label: (c) => c.dataset.label === 'Ingresos' ? ' Ingresos: ' + moneda.format(c.parsed.y) : ' Citas: ' + c.parsed.y } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#edf3f6' }, ticks: { callback: (v) => moneda.format(v) } },
                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { precision: 0 } },
                        x: { grid: { display: false } }
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
                    datasets: [{ data: @json($serviciosValues), backgroundColor: ['#0e7490', '#22d3ee', '#0f766e', '#f59e0b', '#6366f1', '#f43f5e'], borderWidth: 0 }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } }
                }
            });
        }
    });
</script>
@endpush