
@extends('layouts.app')

@section('title', 'Historial del cliente')
@section('page-title', 'Historial de servicios')

@section('content')
@include('clientes.partials.estilos')

<div class="cliente-page">

    {{-- Encabezado --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>


            <h1 class="text-2xl font-extrabold text-slate-800">
                Historial del cliente
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consulta los vehículos y servicios realizados.
            </p>
        </div>

        <a href="{{ route('clientes.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-800">
            ← Volver a clientes
        </a>
    </div>

    {{-- Tarjeta de identificación --}}
    <div class="cliente-banner mb-6 flex items-center gap-4 rounded-2xl px-6 py-6 sm:px-8">
        <div class="cliente-icon">
            @include('layouts.partials.icons', ['icon' => 'user'])
        </div>

        <div class="relative z-10 min-w-0">
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-cyan-300">
                Información del cliente
            </p>

            <h2 class="truncate text-xl font-extrabold text-white sm:text-2xl">
                {{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}
            </h2>

            <p class="mt-1 text-xs text-slate-200 sm:text-sm">
                Consulta el historial de sus vehículos y servicios.
            </p>
        </div>
    </div>

    {{-- Vehículos --}}
    <div class="mb-5 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-extrabold text-slate-800">
                Vehículos registrados
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Selecciona un vehículo para consultar sus servicios.
            </p>
        </div>

        <span class="rounded-full border border-cyan-100 bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-800">
            {{ $cliente->vehiculo->count() }}
            {{ $cliente->vehiculo->count() === 1 ? 'vehículo' : 'vehículos' }}
        </span>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">

        @forelse($cliente->vehiculo as $vehiculo)

            @php
                $nombreTipo = mb_strtolower($vehiculo->tipoVehiculo->nombre ?? '');
                $iconoVehiculo = str_contains($nombreTipo, 'moto') ? 'motorcycle' : 'car';
            @endphp

            <details class="cliente-history-card group">

                {{-- Cabecera desplegable --}}
                <summary class="flex items-center gap-4 bg-gradient-to-r from-cyan-50/70 to-white p-5">

                    <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-cyan-100 p-3 text-cyan-800">
                        @include('layouts.partials.icons', ['icon' => $iconoVehiculo])
                    </span>

                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-lg font-extrabold text-slate-800">
                            {{ $vehiculo->placa }}
                        </span>

                        <span class="mt-1 block truncate text-sm text-slate-500">
                            {{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }}
                            · {{ $vehiculo->modelo ?: 'Modelo sin registrar' }}
                        </span>

                        <span class="mt-2 inline-flex rounded-full bg-cyan-100 px-2.5 py-1 text-xs font-bold text-cyan-800">
                            {{ $vehiculo->agendaServicios->count() }}
                            {{ $vehiculo->agendaServicios->count() === 1 ? 'servicio realizado' : 'servicios realizados' }}
                        </span>
                    </span>

                    <span class="flex shrink-0 items-center gap-2 text-xs font-bold text-cyan-800">
                        <span class="hidden sm:inline">Ver historial</span>
                        <span class="cliente-chevron h-4 w-4 transition-transform">
                            @include('layouts.partials.icons', ['icon' => 'chevron-down'])
                        </span>
                    </span>

                </summary>

                {{-- Contenido del historial --}}
                <div class="border-t border-slate-100 px-4 py-5 sm:px-6">

                    <div class="mb-5 flex items-center gap-2">
                        <span class="h-5 w-5 text-cyan-700">
                            @include('layouts.partials.icons', ['icon' => 'history'])
                        </span>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-600">
                            Historial de servicios
                        </h3>
                    </div>

                    @forelse($vehiculo->agendaServicios as $linea)

                        <article class="relative ml-2 border-l-2 border-cyan-100 pb-6 pl-6 last:border-transparent last:pb-0">

                            <span class="absolute -left-[7px] top-1 h-3 w-3 rounded-full border-2 border-white bg-cyan-600 ring-2 ring-cyan-100"></span>

                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">

                                <div class="flex flex-wrap items-start justify-between gap-3">

                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-cyan-800">
                                            {{ \Illuminate\Support\Carbon::parse($linea->agenda->fecha)->format('d/m/Y') }}
                                            · {{ substr($linea->agenda->hora, 0, 5) }}
                                        </p>

                                        <h4 class="mt-1 font-extrabold text-slate-800">
                                            {{ $linea->servicio->nombre ?? 'Servicio' }}
                                        </h4>
                                    </div>

                                    <span class="whitespace-nowrap rounded-lg bg-white px-3 py-2 text-sm font-extrabold text-cyan-900 shadow-sm">
                                        ${{ number_format((float) $linea->Precio, 2, ',', '.') }}
                                    </span>

                                </div>

                                <div class="mt-4 grid gap-3 border-t border-slate-200 pt-3 sm:grid-cols-2">

                                    <div>
                                        <p class="text-xs text-slate-400">Empleado encargado</p>
                                        <p class="mt-1 text-xs font-semibold text-slate-700">
                                            {{ $linea->agenda->empleado ? trim($linea->agenda->empleado->nombre . ' ' . $linea->agenda->empleado->apellido) : 'Sin asignar' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-slate-400">Método de pago</p>
                                        <p class="mt-1 text-xs font-semibold text-slate-700">
                                            {{ $linea->agenda->pago->MetodoPago ?? 'Sin registro' }}
                                        </p>
                                    </div>

                                </div>

                                <div class="mt-4 flex justify-end">
                                    <a href="{{ route('agenda.factura', $linea->agenda->id) }}"
                                       class="inline-flex items-center gap-2 rounded-lg border border-cyan-200 bg-white px-3 py-2 text-xs font-bold text-cyan-800 transition hover:bg-cyan-50">
                                        @include('layouts.partials.icons', ['icon' => 'receipt'])
                                        Ver factura →
                                    </a>
                                </div>

                            </div>
                        </article>

                    @empty

                        <div class="cliente-empty">
                            <span class="mx-auto block h-8 w-8 text-slate-400">
                                @include('layouts.partials.icons', ['icon' => 'history'])
                            </span>

                            <p class="mt-3 font-bold text-slate-700">
                                Todavía no hay servicios registrados.
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Cuando se realice un servicio para este vehículo, aparecerá aquí.
                            </p>
                        </div>

                    @endforelse

                </div>
            </details>

        @empty

            <div class="cliente-empty xl:col-span-2">
                <span class="mx-auto block h-10 w-10 text-slate-400">
                    @include('layouts.partials.icons', ['icon' => 'car'])
                </span>

                <h3 class="mt-3 font-bold text-slate-700">
                    Este cliente todavía no tiene vehículos registrados.
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Cuando se registren sus vehículos, aparecerán en esta sección.
                </p>
            </div>

        @endforelse

    </div>
</div>
@endsection
