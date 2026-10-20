@extends('layouts.app')

@section('title')
    Editar Pago
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            ✏️ Editar Pago
        </h1>

        <a href="{{ route('pagos.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('pagos.update', $pago->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4 rounded-lg border border-cyan-100 bg-cyan-50 p-4">
            <p class="font-semibold text-cyan-900">Cita #{{ $pago->agendaId }}</p>
            <p class="text-sm text-cyan-800">
                {{ $pago->agenda->cliente->nombreCliente ?? 'Cliente' }} ·
                {{ $pago->agenda->vehiculo->placa ?? 'Vehículo' }}
            </p>
        </div>

        {{-- Total --}}
        <div class="mb-4">
            <label for="Monto" class="block text-gray-700 font-semibold mb-1">Total de servicios</label>
            <input type="text" id="Monto" readonly
                value="${{ number_format($total, 2) }}"
                class="w-full rounded-lg border bg-gray-100 px-3 py-2 text-gray-700">
            <p class="mt-1 text-xs text-gray-500">El total se vuelve a calcular desde los servicios guardados al actualizar el pago.</p>
        </div>

        {{-- Método de pago --}}
        <div class="mb-4">
            <label for="MetodoPago" class="block text-gray-700 font-semibold mb-1">Método de pago</label>

            <select name="MetodoPago" id="MetodoPago"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('MetodoPago') border-red-500 @enderror">

                @foreach(['Efectivo', 'Tarjeta', 'Transferencia'] as $metodo)
                    <option value="{{ $metodo }}"
                        {{ old('MetodoPago', $pago->MetodoPago) == $metodo ? 'selected' : '' }}>
                        {{ $metodo }}
                    </option>
                @endforeach
            </select>

            @error('MetodoPago')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fecha de pago (solo lectura) --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Fecha de pago</label>
            <p class="px-3 py-2 bg-gray-100 rounded-lg text-gray-600">
                {{ $pago->FechaPago?->format('d/m/Y H:i') }}
            </p>
        </div>

        {{-- Requiere factura --}}
        <div class="mb-6">
            <label class="inline-flex items-center gap-2 text-gray-700 font-semibold">
                <input type="hidden" name="RequerirFactura" value="0">
                <input type="checkbox" name="RequerirFactura" value="1"
                    class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-400"
                    {{ old('RequerirFactura', $pago->RequerirFactura) ? 'checked' : '' }}>
                Requiere factura
            </label>

            @error('RequerirFactura')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('pagos.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Cancelar
            </a>

            <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-800 text-white font-semibold px-4 py-2 rounded-lg shadow">
                💾 Guardar cambios
            </button>
        </div>

    </form>

</x-card>

@endsection
