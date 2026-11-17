@extends('layouts.app')

@section('title', 'Detalle del historial de citas')
@section('page-title', 'Detalle del historial de citas')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <header class="bg-gradient-to-r from-slate-900 to-cyan-800 px-6 py-7 text-white sm:px-8">
            <p class="text-sm font-semibold text-cyan-100">
                Registro #{{ $agendaServicio->id }}
            </p>

            <h1 class="mt-1 text-2xl font-extrabold">
                Detalle del servicio registrado
            </h1>

            <p class="mt-2 text-sm text-cyan-50">
                Información de consulta asociada a la cita.
            </p>
        </header>

        <div class="grid gap-8 p-6 sm:grid-cols-2 sm:p-8">

            <section class="space-y-4">
                <h2 class="border-b border-slate-100 pb-3 text-sm font-bold uppercase tracking-wide text-slate-500">
                    Información de la cita
                </h2>

                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-400">Número de cita</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $agendaServicio->agenda ? '#' . $agendaServicio->agenda->id : 'No disponible' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Fecha</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $agendaServicio->agenda?->fecha ? \Carbon\Carbon::parse($agendaServicio->agenda->fecha)->format('d/m/Y') : 'No disponible' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Hora</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $agendaServicio->agenda?->hora ? substr($agendaServicio->agenda->hora, 0, 5) : 'No disponible' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Estado</dt>
                        <dd class="mt-1">
                            <span class="inline-flex rounded-full bg-cyan-50 px-2.5 py-1 text-xs font-semibold text-cyan-800">
                                {{ ucfirst($agendaServicio->agenda?->estado ?? 'No disponible') }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Cliente</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ trim(($agendaServicio->agenda?->cliente?->nombreCliente ?? '') . ' ' . ($agendaServicio->agenda?->cliente?->apellidoCliente ?? '')) ?: 'No disponible' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Empleado asignado</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ trim(($agendaServicio->agenda?->empleado?->nombre ?? '') . ' ' . ($agendaServicio->agenda?->empleado?->apellido ?? '')) ?: 'No disponible' }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="space-y-4">
                <h2 class="border-b border-slate-100 pb-3 text-sm font-bold uppercase tracking-wide text-slate-500">
                    Servicio y vehículo
                </h2>

                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-400">Servicio</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $agendaServicio->servicio?->nombre ?? 'No disponible' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Vehículo</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $agendaServicio->vehiculo?->placa ?? 'No disponible' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Modelo</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $agendaServicio->vehiculo?->modelo ?? 'No disponible' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Tipo de vehículo</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $agendaServicio->vehiculo?->tipoVehiculo?->nombre ?? 'No disponible' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Precio registrado</dt>
                        <dd class="mt-1 text-lg font-extrabold text-emerald-700">
                            ${{ number_format((float) $agendaServicio->Precio, 2, ',', '.') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Duración estimada</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $agendaServicio->duracionMinutos ?? 0 }} minutos
                        </dd>
                    </div>
                </dl>
            </section>

        </div>
    </section>
</div>
@endsection
