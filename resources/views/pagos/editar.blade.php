@extends('layouts.app')

@section('title', 'Editar pago')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 px-4 py-6">
    <x-module-header
        title="Editar pago"
        description="Actualiza la información del pago registrado."
        icon="credit-card"
    />

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <form action="{{ route('pagos.update', $pago->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-6 rounded-2xl border border-cyan-100 bg-cyan-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-cyan-800">Cita asociada</p>
                <p class="mt-1 text-lg font-bold text-slate-900">Cita #{{ $pago->agenda?->referencia ?? str_pad((string) $pago->agendaId, 7, '0', STR_PAD_LEFT) }}</p>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $pago->agenda->cliente->nombreCliente ?? 'Cliente' }}
                    · {{ $pago->agenda->agendaServicios->pluck('vehiculo.placa')->filter()->unique()->implode(', ') ?: 'Vehículo' }}
                </p>
            </div>

            <div class="mb-6">
                <label for="Monto" class="mb-2 block text-sm font-semibold text-slate-700">Total de servicios</label>
                <input
                    type="text"
                    id="Monto"
                    readonly
                    value="${{ number_format($total, 2, ',', '.') }}"
                    class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-700"
                >
                <p class="mt-2 text-xs text-slate-500">El total se calcula nuevamente desde los servicios registrados.</p>
            </div>

            <div class="mb-6">
                <label for="MetodoPago" class="mb-2 block text-sm font-semibold text-slate-700">Método de pago</label>
                <select
                    name="MetodoPago"
                    id="MetodoPago"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100 @error('MetodoPago') border-red-500 @enderror"
                >
                    @foreach(['Efectivo', 'Tarjeta', 'Transferencia'] as $metodo)
                        <option value="{{ $metodo }}" @selected(old('MetodoPago', $pago->MetodoPago) == $metodo)>{{ $metodo }}</option>
                    @endforeach
                </select>
                @error('MetodoPago')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="mb-2 block text-sm font-semibold text-slate-700">Fecha de pago</label>
                <p class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    {{ $pago->FechaPago?->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 text-sm font-medium text-slate-700">
                    <input type="hidden" name="RequerirFactura" value="0">
                    <input
                        type="checkbox"
                        name="RequerirFactura"
                        value="1"
                        class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600"
                        @checked(old('RequerirFactura', $pago->RequerirFactura))
                    >
                    El cliente requiere factura
                </label>
                @error('RequerirFactura')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
                <a href="{{ route('pagos.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Cancelar</a>
                <button type="submit" class="rounded-xl bg-cyan-700 px-6 py-3 text-sm font-bold text-white transition hover:bg-cyan-800">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
