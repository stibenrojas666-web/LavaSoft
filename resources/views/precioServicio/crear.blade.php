@extends('layouts.app')

@section('title')
    Crear Precio de Servicio
@endsection

@section('content')

<x-card>
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Nuevo Precio de Servicio</h1>

    <form action="{{ route('precioServicio.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- servicio --}}
        <div>
            <label for="servicio_id" class="block text-gray-700 font-semibold mb-1">Servicio</label>
            <select name="servicio_id" id="servicio_id"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                           @error('servicio_id') border-red-500 @enderror">
                <option value="">Selecciona un servicio</option>
                @foreach($servicios as $servicio)
                    <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                        {{ $servicio->nombre }}
                    </option>
                @endforeach
            </select>
            @error('servicio_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- tipo de vehiculo --}}
        <div>
            <label for="tipo_vehiculo_id" class="block text-gray-700 font-semibold mb-1">Tipo de vehículo</label>
            <select name="tipo_vehiculo_id" id="tipo_vehiculo_id"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                           @error('tipo_vehiculo_id') border-red-500 @enderror">
                <option value="">Selecciona un tipo de vehículo</option>
                @foreach($tipoVehiculos as $tipoVehiculo)
                    <option value="{{ $tipoVehiculo->id }}" {{ old('tipo_vehiculo_id') == $tipoVehiculo->id ? 'selected' : '' }}>
                        {{ $tipoVehiculo->nombre }}
                    </option>
                @endforeach
            </select>
            @error('tipo_vehiculo_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- precio --}}
        <div>
            <label for="precio" class="block text-gray-700 font-semibold mb-1">Precio</label>
            <input type="number" step="0.01" min="0" name="precio" id="precio"
                   value="{{ old('precio') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('precio') border-red-500 @enderror">
            @error('precio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit"
                class="bg-pink-300 hover:bg-pink-400 text-white font-semibold px-4 py-2 rounded shadow">
                💾 Guardar
            </button>
            <a href="{{ route('precioServicio.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-4 py-2 rounded shadow">
               ↩️ Cancelar
            </a>
        </div>
    </form>
</x-card>

@endsection