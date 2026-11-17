@extends('layouts.app')

@section('title', 'Historial del vehículo')
@section('page-title', 'Historial del vehículo')

@section('content')
    @php
        $nombreTipo = mb_strtolower($vehiculo->tipoVehiculo->nombre ?? '');
        $iconoVehiculo = str_contains($nombreTipo, 'moto') ? 'motorcycle' : 'car';
    @endphp

    <x-card>
        <div class="mb-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Historial individual del vehículo
                </p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                    {{ $vehiculo->placa }}
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Consulta los servicios realizados y sus detalles.
                </p>
            </div>

        </div>

        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <header class="flex flex-wrap items-center gap-4 bg-gradient-to-r from-slate-50 to-white p-5 sm:p-6">
                <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-cyan-50 p-3 text-cyan-800">
                    @include('layouts.partials.icons', ['icon' => $iconoVehiculo])
                </span>

                <div class="min-w-0 flex-1">
                    <h2 class="text-lg font-bold text-slate-900">
                        {{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }} · {{ $vehiculo->placa }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-600">
                        {{ $vehiculo->modelo ?: 'Modelo sin registrar' }}
                        {{ $vehiculo->color ? ' · ' . $vehiculo->color : '' }}
                    </p>

                    <p class="mt-1 text-sm text-slate-600">
                        Propietario:
                        <span class="font-semibold text-slate-800">
                            {{ trim(($vehiculo->cliente->nombreCliente ?? '') . ' ' . ($vehiculo->cliente->apellidoCliente ?? '')) ?: 'Sin cliente' }}
                        </span>
                    </p>
                </div>

                <div class="rounded-xl border border-cyan-100 bg-cyan-50 px-4 py-3 text-center">
                    <p class="text-2xl font-bold text-cyan-800">
                        {{ $historial->count() }}
                    </p>
                    <p class="text-xs font-semibold text-cyan-700">
                        {{ $historial->count() === 1 ? 'Servicio realizado' : 'Servicios realizados' }}
                    </p>
                </div>
            </header>

            <div class="border-t border-slate-200 px-4 py-6 sm:px-6">
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-slate-800">Servicios realizados</h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Registro de los servicios asociados a este vehículo.
                    </p>
                </div>

                @forelse($historial as $linea)
                    <article class="relative ml-2 border-l-2 border-cyan-100 pb-6 pl-6 last:border-transparent last:pb-0">

                        <span class="absolute -left-[7px] top-1 h-3 w-3 rounded-full border-2 border-white bg-cyan-600 ring-2 ring-cyan-100"></span>

                        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-cyan-200 hover:bg-white">

                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-cyan-800">
                                        {{ \Illuminate\Support\Carbon::parse($linea->agenda->fecha)->format('d/m/Y') }}
                                        ·
                                        {{ substr($linea->agenda->hora, 0, 5) }}
                                    </p>

                                    <h4 class="mt-1 font-bold text-slate-900">
                                        {{ $linea->servicio->nombre ?? 'Servicio' }}
                                    </h4>
                                </div>

                                <span class="whitespace-nowrap rounded-lg bg-white px-3 py-2 text-sm font-bold text-slate-800 shadow-sm">
                                    ${{ number_format((float) $linea->Precio, 2, ',', '.') }}
                                </span>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center justify-between gap-4 border-t border-slate-200 pt-4">
                                <div class="space-y-1 text-xs text-slate-600">
                                    <p>
                                        <span class="font-semibold text-slate-700">Atendió:</span>
                                        {{ $linea->agenda->empleado ? trim($linea->agenda->empleado->nombre . ' ' . $linea->agenda->empleado->apellido) : 'Sin asignar' }}
                                    </p>

                                    <p>
                                        <span class="font-semibold text-slate-700">Pago:</span>
                                        {{ $linea->agenda->pago->MetodoPago ?? 'Sin registro' }}
                                    </p>
                                </div>

                                <a
                                    href="{{ route('agenda.factura', $linea->agenda->id) }}"
                                    class="inline-flex items-center gap-2 rounded-xl border border-cyan-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-cyan-800 transition hover:bg-cyan-50"
                                    title="Ver factura"
                                    aria-label="Ver factura"
                                >
                                    <span class="h-4 w-4">
                                        @include('layouts.partials.icons', ['icon' => 'receipt'])
                                    </span>
                                    Ver factura
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center">
                        <span class="mx-auto grid h-12 w-12 place-items-center rounded-xl bg-white text-slate-400 shadow-sm">
                            <span class="h-6 w-6">
                                @include('layouts.partials.icons', ['icon' => 'history'])
                            </span>
                        </span>

                        <p class="mt-4 font-semibold text-slate-700">
                            Todavía no hay servicios completados para este vehículo.
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Cuando se complete un servicio, aparecerá aquí.
                        </p>
                    </div>
                @endforelse
            </div>
        </article>
    </x-card>
@endsection
