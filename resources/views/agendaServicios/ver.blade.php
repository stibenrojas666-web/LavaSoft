@extends('layouts.app')

@section('title', 'Detalle del historial de citas')
@section('page-title', 'Detalle del historial de citas')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <a href="{{ route('agendaServicio.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-cyan-800 hover:text-cyan-950">
        <span aria-hidden="true">←</span> Volver al historial de citas
    </a>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <header class="bg-gradient-to-r from-slate-900 to-cyan-800 px-6 py-7 text-white sm:px-8">
            <p class="text-sm font-semibold text-cyan-100">Registro #{{ $agendaServicio->id }}</p>
            <h1 class="mt-1 text-2xl font-extrabold">Detalle del servicio registrado</h1>
            <p class="mt-2 text-sm text-cyan-50">Información de solo consulta asociada a la cita.</p>
        </header>

        <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">
            <section class="space-y-4">
                <h2 class="border-b border-slate-100 pb-2 text-sm font-bold uppercase tracking-wide text-slate-500">Cita</h2>
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-slate-500">Número</dt><dd class="font-semibold text-slate-800">{{ $agendaServicio->agenda ? '#' . $agendaServicio->agenda->id : 'No disponible' }}</dd></div>
                    <div><dt class="text-slate-500">Fecha</dt><dd class="font-semibold text-slate-800">{{ $agendaServicio->agenda?->fecha ? \Carbon\Carbon::parse($agendaServicio->agenda->fecha)->format('d/m/Y') : 'No disponible' }}</dd></div>
                    <div><dt class="text-slate-500">Hora</dt><dd class="font-semibold text-slate-800">{{ $agendaServicio->agenda?->hora ? substr($agendaServicio->agenda->hora, 0, 5) : 'No disponible' }}</dd></div>
                    <div><dt class="text-slate-500">Estado</dt><dd class="font-semibold text-slate-800">{{ ucfirst($agendaServicio->agenda?->estado ?? 'No disponible') }}</dd></div>
                    <div><dt class="text-slate-500">Cliente</dt><dd class="font-semibold text-slate-800">{{ trim(($agendaServicio->agenda?->cliente?->nombreCliente ?? '') . ' ' . ($agendaServicio->agenda?->cliente?->apellidoCliente ?? '')) ?: 'No disponible' }}</dd></div>
                    <div><dt class="text-slate-500">Empleado asignado</dt><dd class="font-semibold text-slate-800">{{ trim(($agendaServicio->agenda?->empleado?->nombre ?? '') . ' ' . ($agendaServicio->agenda?->empleado?->apellido ?? '')) ?: 'No disponible' }}</dd></div>
                </dl>
            </section>

            <section class="space-y-4">
                <h2 class="border-b border-slate-100 pb-2 text-sm font-bold uppercase tracking-wide text-slate-500">Servicio y vehículo</h2>
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-slate-500">Servicio</dt><dd class="font-semibold text-slate-800">{{ $agendaServicio->servicio?->nombre ?? 'No disponible' }}</dd></div>
                    <div><dt class="text-slate-500">Vehículo</dt><dd class="font-semibold text-slate-800">{{ $agendaServicio->vehiculo?->placa ?? 'No disponible' }}</dd></div>
                    <div><dt class="text-slate-500">Modelo</dt><dd class="font-semibold text-slate-800">{{ $agendaServicio->vehiculo?->modelo ?? 'No disponible' }}</dd></div>
                    <div><dt class="text-slate-500">Tipo de vehículo</dt><dd class="font-semibold text-slate-800">{{ $agendaServicio->vehiculo?->tipoVehiculo?->nombre ?? 'No disponible' }}</dd></div>
                    <div><dt class="text-slate-500">Precio registrado</dt><dd class="font-bold text-emerald-700">${{ number_format((float) $agendaServicio->Precio, 2, ',', '.') }}</dd></div>
                    <div><dt class="text-slate-500">Duración estimada</dt><dd class="font-semibold text-slate-800">{{ $agendaServicio->duracionMinutos ?? 0 }} minutos</dd></div>
                </dl>
            </section>
        </div>
    </section>
</div>
@endsection
