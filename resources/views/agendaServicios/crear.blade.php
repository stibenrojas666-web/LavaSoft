@extends('layouts.app')

@section('title')
    Agregar Servicio a Cita
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            🧽 Agregar Servicio a una Cita
        </h1>

        <a href="{{ route('agendaServicio.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('agendaServicio.store') }}" method="POST">
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

        {{-- Servicio --}}
        <div class="mb-4">
            <label for="servicioId" class="block text-gray-700 font-semibold mb-1">Servicio</label>

            <select name="servicioId" id="servicioId"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('servicioId') border-red-500 @enderror">

                <option value="">Seleccione un servicio</option>

                @foreach($servicio as $s)
                    <option value="{{ $s->id }}" {{ old('servicioId') == $s->id ? 'selected' : '' }}>
                        {{ $s->nombre }}
                    </option>
                @endforeach
            </select>

            @error('servicioId')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Precio --}}
        <div class="mb-6">
            <label for="Precio" class="block text-gray-700 font-semibold mb-1">Precio</label>

            <input type="number" step="0.01" min="0" name="Precio" id="Precio"
                value="{{ old('Precio') }}"
                placeholder="0.00"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('Precio') border-red-500 @enderror">

            @error('Precio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('agendaServicio.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Cancelar
            </a>

            <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-800 text-white font-semibold px-4 py-2 rounded-lg shadow">
                💾 Guardar
            </button>
        </div>

    </form>

</x-card>

@endsection