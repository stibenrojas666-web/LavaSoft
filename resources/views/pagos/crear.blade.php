@extends('layouts.app')

@section('title', 'Registrar pago')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 px-4 py-6">
    <x-module-header
        title="Registrar nuevo pago"
        description="Registra el pago correspondiente a una cita."
        icon="credit-card"
    />

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <form action="{{ route('pagos.store') }}" method="POST" novalidate>
            @csrf

            <div class="mb-6">
                <label for="agendaId" class="mb-2 block text-sm font-semibold text-slate-700">Cita</label>
                <select
                    name="agendaId"
                    id="agendaId"
                    onchange="if(this.value){window.location.href='{{ route('pagos.create') }}?agendaId='+encodeURIComponent(this.value)}else{window.location.href='{{ route('pagos.create') }}'}"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100 @error('agendaId') border-red-500 @enderror"
                >
                    <option value="">Seleccione una cita</option>
                    @forelse($agenda as $a)
                        <option value="{{ $a->id }}" @selected((string) request('agendaId') === (string) $a->id)>
                            #{{ $a->referencia }} · {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                            · {{ substr($a->hora, 0, 5) }} · {{ $a->cliente->nombreCliente ?? 'N/A' }}
                        </option>
                    @empty
                        <option value="" disabled>No hay citas pendientes de pago</option>
                    @endforelse
                </select>
                @error('agendaId')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if($agendaSeleccionada)
                <div class="mb-6 rounded-2xl border border-cyan-100 bg-cyan-50 p-5">
                    <h2 class="font-bold text-slate-900">Servicios de la cita #{{ $agendaSeleccionada->referencia }}</h2>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ $agendaSeleccionada->cliente->nombreCliente ?? 'Cliente' }}
                        · {{ $agendaSeleccionada->agendaServicios->pluck('vehiculo.placa')->filter()->unique()->implode(', ') ?: 'Vehículo' }}
                        · {{ \Carbon\Carbon::parse($agendaSeleccionada->fecha)->format('d/m/Y') }}
                    </p>

                    <div class="mt-4 divide-y divide-cyan-100">
                        @forelse($agendaSeleccionada->agendaServicios as $linea)
                            <div class="flex items-center justify-between gap-4 py-3 text-sm">
                                <span class="font-medium text-slate-700">{{ $linea->servicio->nombre ?? 'Servicio' }}</span>
                                <span class="font-semibold text-slate-900">${{ number_format((float) $linea->Precio, 2, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="py-3 text-sm text-red-700">Esta cita no tiene servicios registrados.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <div class="mb-6">
                <label for="Monto" class="mb-2 block text-sm font-semibold text-slate-700">Total a pagar</label>
                <input
                    type="text"
                    id="Monto"
                    readonly
                    value="{{ $total === null ? '' : '$' . number_format($total, 2, ',', '.') }}"
                    placeholder="Selecciona una cita para ver el total"
                    class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-700"
                >
                <p class="mt-2 text-xs text-slate-500">El total se calcula sumando los servicios registrados.</p>
            </div>

            <div class="mb-6">
                <label for="MetodoPago" class="mb-2 block text-sm font-semibold text-slate-700">Método de pago</label>
                <select
                    name="MetodoPago"
                    id="MetodoPago"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100 @error('MetodoPago') border-red-500 @enderror"
                >
                    <option value="">Seleccione un método</option>
                    @foreach(['Efectivo', 'Tarjeta', 'Transferencia'] as $metodo)
                        <option value="{{ $metodo }}" @selected(old('MetodoPago') == $metodo)>{{ $metodo }}</option>
                    @endforeach
                </select>
                @error('MetodoPago')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 text-sm font-medium text-slate-700">
                    <input type="hidden" name="RequerirFactura" value="0">
                    <input
                        type="checkbox"
                        name="RequerirFactura"
                        value="1"
                        class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600"
                        @checked(old('RequerirFactura'))
                    >
                    El cliente requiere factura
                </label>
                @error('RequerirFactura')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
                <a href="{{ route('pagos.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Cancelar</a>
                <button
                    type="submit"
                    @disabled(!$agendaSeleccionada || $total <= 0)
                    class="rounded-xl bg-cyan-700 px-6 py-3 text-sm font-bold text-white transition hover:bg-cyan-800 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Guardar pago
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
