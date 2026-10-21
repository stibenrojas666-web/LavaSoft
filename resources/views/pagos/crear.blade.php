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
                onchange="if(this.value){window.location.href='{{ route('pagos.create') }}?agendaId='+encodeURIComponent(this.value)}else{window.location.href='{{ route('pagos.create') }}'}"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('agendaId') border-red-500 @enderror">

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

            @error('agendaId')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        @if($agendaSeleccionada)
            <div class="mb-4 rounded-lg border border-cyan-100 bg-cyan-50 p-4">
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

        {{-- Total de solo lectura; el servidor vuelve a sumarlo al guardar. --}}
        <div class="mb-4">
            <label for="Monto" class="block text-gray-700 font-semibold mb-1">Total a pagar</label>
            <input type="text" id="Monto" readonly
                value="{{ $total === null ? '' : '$' . number_format($total, 2) }}"
                placeholder="Selecciona una cita para ver el total"
                class="w-full rounded-lg border bg-gray-100 px-3 py-2 text-gray-700">
            <p class="mt-1 text-xs text-gray-500">El total se calcula sumando los servicios. No se puede editar desde este formulario.</p>
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

            <button type="submit" {{ !$agendaSeleccionada || $total <= 0 ? 'disabled' : '' }}
                class="bg-cyan-600 hover:bg-cyan-800 text-white font-semibold px-4 py-2 rounded-lg shadow">
                💾 Guardar Pago
            </button>
        </div>

    </form>

</x-card>

@endsection
