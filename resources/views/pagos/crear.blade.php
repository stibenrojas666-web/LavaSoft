
@extends('layouts.app')

@section('title', 'Registrar Pago')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6">
    <x-module-header title="Registrar nuevo pago" description="Registra el pago correspondiente a una cita." icon="credit-card" :back-url="route('pagos.historial')" />

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <form action="{{ route('pagos.store') }}" method="POST" novalidate>
            @csrf

            <div class="mb-5">
                <label for="agendaId" class="mb-1.5 block text-xs font-semibold text-slate-600">Cita</label>
                <select name="agendaId" id="agendaId"
                    onchange="if(this.value){window.location.href='{{ route('pagos.create') }}?agendaId='+encodeURIComponent(this.value)}else{window.location.href='{{ route('pagos.create') }}'}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('agendaId') border-red-500 @enderror">
                    <option value="">Seleccione una cita</option>
                    @forelse($agenda as $a)
                        <option value="{{ $a->id }}" {{ (string) request('agendaId') === (string) $a->id ? 'selected' : '' }}>
                            #{{ $a->id }} - {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                            {{ substr($a->hora, 0, 5) }} - {{ $a->cliente->nombreCliente ?? 'N/A' }}
                        </option>
                    @empty
                        <option value="" disabled>No hay citas pendientes de pago</option>
                    @endforelse
                </select>
                @error('agendaId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            @if($agendaSeleccionada)
                <div class="mb-5 rounded-lg border border-cyan-100 bg-cyan-50 p-4">
                    <h2 class="font-semibold text-cyan-900">Servicios de la cita #{{ $agendaSeleccionada->id }}</h2>
                    <p class="mb-3 text-sm text-cyan-800">
                        {{ $agendaSeleccionada->cliente->nombreCliente ?? 'Cliente' }} ·
                        {{ $agendaSeleccionada->agendaServicios->pluck('vehiculo.placa')->filter()->unique()->implode(', ') ?: 'Vehículo' }} ·
                        {{ \Carbon\Carbon::parse($agendaSeleccionada->fecha)->format('d/m/Y') }}
                    </p>
                    <div class="divide-y divide-cyan-100">
                        @forelse($agendaSeleccionada->agendaServicios as $linea)
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <span>{{ $linea->servicio->nombre ?? 'Servicio' }}</span>
                                <span>${{ number_format((float) $linea->Precio, 2) }}</span>
                            </div>
                        @empty
                            <p class="py-2 text-sm text-red-700">Esta cita no tiene servicios registrados.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <div class="mb-5">
                <label for="Monto" class="mb-1.5 block text-xs font-semibold text-slate-600">Total a pagar</label>
                <input type="text" id="Monto" readonly
                    value="{{ $total === null ? '' : '$' . number_format($total, 2) }}"
                    placeholder="Selecciona una cita para ver el total"
                    class="w-full rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-700">
                <p class="mt-1 text-xs text-slate-500">El total se calcula sumando los servicios y no se puede editar.</p>
            </div>

            <div class="mb-5">
                <label for="MetodoPago" class="mb-1.5 block text-xs font-semibold text-slate-600">Método de pago</label>
                <select name="MetodoPago" id="MetodoPago"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('MetodoPago') border-red-500 @enderror">
                    <option value="">Seleccione un método</option>
                    @foreach(['Efectivo', 'Tarjeta', 'Transferencia'] as $metodo)
                        <option value="{{ $metodo }}" {{ old('MetodoPago') == $metodo ? 'selected' : '' }}>{{ $metodo }}</option>
                    @endforeach
                </select>
                @error('MetodoPago') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="hidden" name="RequerirFactura" value="0">
                    <input type="checkbox" name="RequerirFactura" value="1"
                        class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                        {{ old('RequerirFactura') ? 'checked' : '' }}>
                    Requiere factura
                </label>
                @error('RequerirFactura') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col-reverse justify-end gap-2 border-t border-slate-100 pt-5 sm:flex-row">
                <a href="{{ route('pagos.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
                <button type="submit" {{ !$agendaSeleccionada || $total <= 0 ? 'disabled' : '' }}
                    class="rounded-lg bg-cyan-600 px-5 py-2 text-sm font-semibold text-white hover:bg-cyan-700 disabled:cursor-not-allowed disabled:opacity-50">
                    Guardar pago
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
