@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 sm:gap-6">
        <x-stat-card label="Clientes" :value="number_format($clientesTotal)" icon="users" />
        <x-stat-card label="Vehículos registrados en el sistema" :value="number_format($vehiculosTotal)" icon="box" />
        <x-stat-card label="Citas de hoy" :value="number_format($citasHoy)" icon="bell" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 sm:gap-6">
        <x-stat-card label="Servicios de hoy" :value="number_format($serviciosHoy)" icon="chart" />
        <x-stat-card label="Citas pendientes" :value="number_format($citasPendientes)" icon="bell" />
        <x-stat-card label="Empleados activos" :value="number_format($lavadoresActivos)" icon="users" />
        <x-stat-card label="Ingresos de hoy" :value="'$' . number_format($cajaHoy, 0, ',', '.')" icon="tag" />
    </div>

    <div class="mt-6">
        <x-card title="Citas recientes" subtitle="Últimas citas registradas" :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-cyan-50">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-cyan-800">Cliente</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-cyan-800">Vehículos y servicios</th>
                            <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-cyan-800">Total de cita</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-cyan-800">Fecha y hora</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-cyan-800">Empleado</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-cyan-800">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($ultimasCitas as $cita)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3.5 text-sm text-slate-700">
                                    {{ trim(($cita->cliente->nombreCliente ?? '') . ' ' . ($cita->cliente->apellidoCliente ?? '')) ?: 'Sin cliente' }}
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-700">
                                    @forelse($cita->agendaServicios->groupBy('vehiculoId') as $lineas)
                                        @php($vehiculoCita = $lineas->first()->vehiculo)
                                        <div class="mb-2 last:mb-0">
                                            <span class="font-medium">{{ $vehiculoCita->placa ?? 'Sin placa' }}</span>
                                            <span class="text-slate-500">({{ $vehiculoCita->tipoVehiculo->nombre ?? 'Vehículo' }})</span>
                                            <p class="text-xs text-slate-500">{{ $lineas->pluck('servicio.nombre')->filter()->implode(', ') }}</p>
                                        </div>
                                    @empty
                                        <span class="text-slate-400">Sin vehículo o servicio</span>
                                    @endforelse
                                </td>
                                <td class="px-5 py-3.5 text-right text-sm font-semibold text-slate-700 whitespace-nowrap">
                                    ${{ number_format((float) ($cita->agenda_servicios_sum_precio ?? 0), 2, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-sm whitespace-nowrap text-slate-500">
                                    {{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d/m/Y') }} · {{ substr($cita->hora, 0, 5) }}
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-700">
                                    {{ $cita->empleado ? trim($cita->empleado->nombre . ' ' . $cita->empleado->apellido) : 'Sin asignar' }}
                                </td>
                                <td class="px-5 py-3.5 text-sm capitalize text-slate-700">{{ $cita->estado }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Todavía no hay citas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
@endsection
