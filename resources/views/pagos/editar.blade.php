
@extends('layouts.app')

@section('title', 'Editar Pago')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6">
    <x-module-header title="Editar pago" description="Actualiza la información del pago registrado." icon="credit-card" :back-url="route('pagos.historial')" />

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <form action="{{ route('pagos.update', $pago->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-5 rounded-lg border border-cyan-100 bg-cyan-50 p-4">
                <p class="font-semibold text-cyan-900">Cita #{{ $pago->agendaId }}</p>
                <p class="text-sm text-cyan-800">
                    {{ $pago->agenda->cliente->nombreCliente ?? 'Cliente' }} ·
                    {{ $pago->agenda->agendaServicios->pluck('vehiculo.placa')->filter()->unique()->implode(', ') ?: 'Vehículo' }}
                </p>
            </div>

            <div class="mb-5">
                <label for="Monto" class="mb-1.5 block text-xs font-semibold text-slate-600">Total de servicios</label>
                <input type="text" id="Monto" readonly value="${{ number_format($total, 2) }}"
                    class="w-full rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-700">
                <p class="mt-1 text-xs text-slate-500">El total se vuelve a calcular desde los servicios guardados.</p>
            </div>

            <div class="mb-5">
                <label for="MetodoPago" class="mb-1.5 block text-xs font-semibold text-slate-600">Método de pago</label>
                <select name="MetodoPago" id="MetodoPago"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('MetodoPago') border-red-500 @enderror">
                    @foreach(['Efectivo', 'Tarjeta', 'Transferencia'] as $metodo)
                        <option value="{{ $metodo }}" {{ old('MetodoPago', $pago->MetodoPago) == $metodo ? 'selected' : '' }}>{{ $metodo }}</option>
                    @endforeach
                </select>
                @error('MetodoPago') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="mb-1.5 block text-xs font-semibold text-slate-600">Fecha de pago</label>
                <p class="rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-600">
                    {{ $pago->FechaPago?->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="hidden" name="RequerirFactura" value="0">
                    <input type="checkbox" name="RequerirFactura" value="1"
                        class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                        {{ old('RequerirFactura', $pago->RequerirFactura) ? 'checked' : '' }}>
                    Requiere factura
                </label>
                @error('RequerirFactura') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col-reverse justify-end gap-2 border-t border-slate-100 pt-5 sm:flex-row">
                <a href="{{ route('pagos.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</a>
                <button type="submit" class="rounded-lg bg-cyan-600 px-5 py-2 text-sm font-semibold text-white hover:bg-cyan-700">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
