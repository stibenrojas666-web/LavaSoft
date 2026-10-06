@extends('layouts.app')

@section('title')
    Registrar Pago
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            💰 Registrar Nuevo Pago
        </h1>

        <a href="{{ route('pagos.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('pagos.store') }}" method="POST">
        @csrf

        {{-- Cita --}}
        <div class="mb-4">
            <label for="agendaId" class="block text-gray-700 font-semibold mb-1">Cita</label>

            <select name="agendaId" id="agendaId"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('agendaId') border-red-500 @enderror">

                <option value="">Seleccione una cita</option>

                @foreach($agenda as $a)
                    <option value="{{ $a->id }}" {{ old('agendaId') == $a->id ? 'selected' : '' }}>
                        #{{ $a->id }} - {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                        {{ substr($a->hora, 0, 5) }} - {{ $a->cliente->nombreCliente ?? 'N/A' }}
                    </option>
                @endforeach
            </select>

            @error('agendaId')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Monto --}}
        <div class="mb-4">
            <label for="Monto" class="block text-gray-700 font-semibold mb-1">Monto</label>

            <input type="number" step="0.01" min="0" name="Monto" id="Monto"
                value="{{ old('Monto') }}"
                placeholder="0.00"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('Monto') border-red-500 @enderror">

            @error('Monto')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Método de pago --}}
        <div class="mb-4">
            <label for="MetodoPago" class="block text-gray-700 font-semibold mb-1">Método de pago</label>

            <select name="MetodoPago" id="MetodoPago"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('MetodoPago') border-red-500 @enderror">

                <option value="">Seleccione un método</option>
                @foreach(['Efectivo', 'Tarjeta', 'Transferencia'] as $metodo)
                    <option value="{{ $metodo }}" {{ old('MetodoPago') == $metodo ? 'selected' : '' }}>
                        {{ $metodo }}
                    </option>
                @endforeach
            </select>

            @error('MetodoPago')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Requiere factura --}}
        <div class="mb-6">
            <label class="inline-flex items-center gap-2 text-gray-700 font-semibold">
                {{-- El hidden asegura que se envíe 0 cuando el checkbox está desmarcado --}}
                <input type="hidden" name="RequerirFactura" value="0">
                <input type="checkbox" name="RequerirFactura" value="1"
                    class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-400"
                    {{ old('RequerirFactura') ? 'checked' : '' }}>
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
                💾 Guardar Pago
            </button>
        </div>

    </form>

</x-card>

@endsection