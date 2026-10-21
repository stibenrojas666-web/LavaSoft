@extends('layouts.app')

@section('title', 'Historial del vehículo')
@section('page-title', 'Historial del vehículo')

@section('content')
    @php
        $nombreTipo = mb_strtolower($vehiculo->tipoVehiculo->nombre ?? '');
        $iconoVehiculo = str_contains($nombreTipo, 'moto') ? 'motorcycle' : 'car';
    @endphp

    <x-card>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-slate-500">Historial individual del vehículo</p>
                <h1 class="text-2xl font-bold text-cyan-800">{{ $vehiculo->placa }}</h1>
            </div>
            <a href="{{ route('vehiculos.index') }}" class="text-sm font-medium text-cyan-700 hover:text-cyan-900">← Volver a vehículos</a>
        </div>

        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="flex flex-wrap items-center gap-4 bg-gradient-to-r from-slate-50 to-white p-4 sm:p-5">
                <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-cyan-100 p-3 text-cyan-800">
                    @include('layouts.partials.icons', ['icon' => $iconoVehiculo])
                </span>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg font-bold text-slate-900">{{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }} · {{ $vehiculo->placa }}</h2>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ $vehiculo->modelo ?: 'Modelo sin registrar' }}{{ $vehiculo->color ? ' · ' . $vehiculo->color : '' }}
                    </p>
                    <p class="mt-1 text-sm text-slate-600">Propietario: {{ trim(($vehiculo->cliente->nombreCliente ?? '') . ' ' . ($vehiculo->cliente->apellidoCliente ?? '')) ?: 'Sin cliente' }}</p>
                </div>
                <span class="rounded-full bg-cyan-50 px-3 py-1 text-sm font-medium text-cyan-800">
                    {{ $historial->count() }} {{ $historial->count() === 1 ? 'servicio realizado' : 'servicios realizados' }}
                </span>
            </header>

            <div class="border-t border-slate-200 px-4 py-5 sm:px-6">
                <h3 class="mb-5 text-xs font-bold uppercase tracking-wider text-slate-500">Servicios realizados</h3>

                @forelse($historial as $linea)
                    <article class="relative ml-2 border-l-2 border-cyan-100 pb-6 pl-6 last:border-transparent last:pb-0">
                        <span class="absolute -left-[7px] top-1 h-3 w-3 rounded-full border-2 border-white bg-cyan-600 ring-2 ring-cyan-100"></span>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-cyan-800">
                                        {{ \Illuminate\Support\Carbon::parse($linea->agenda->fecha)->format('d/m/Y') }} · {{ substr($linea->agenda->hora, 0, 5) }}
                                    </p>
                                    <h4 class="mt-1 font-semibold text-slate-900">{{ $linea->servicio->nombre ?? 'Servicio' }}</h4>
                                </div>
                                <span class="whitespace-nowrap text-sm font-bold text-slate-800">${{ number_format((float) $linea->Precio, 2, ',', '.') }}</span>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 pt-3">
                                <div class="text-xs text-slate-600">
                                    <p>Atendió: {{ $linea->agenda->empleado ? trim($linea->agenda->empleado->nombre . ' ' . $linea->agenda->empleado->apellido) : 'Sin asignar' }}</p>
                                    <p class="mt-1">Pago: {{ $linea->agenda->pago->MetodoPago ?? 'Sin registro' }}</p>
                                </div>
                                <a href="{{ route('agenda.factura', $linea->agenda->id) }}" class="inline-flex items-center gap-2 rounded-lg border border-cyan-200 bg-white px-3 py-2 text-xs font-semibold text-cyan-800 transition hover:bg-cyan-50" title="Ver factura" aria-label="Ver factura">
                                    <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'receipt'])</span>
                                    Ver factura
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center">
                        <p class="font-medium text-slate-700">Todavía no hay servicios completados para este vehículo.</p>
                        <p class="mt-1 text-sm text-slate-500">Cuando se complete un servicio, aparecerá aquí.</p>
                    </div>
                @endforelse
            </div>
        </article>
    </x-card>
@endsection
