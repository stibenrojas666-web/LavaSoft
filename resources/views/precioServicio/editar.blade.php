@extends('layouts.app')

@section('title')
    Precio de Servicio - Editar
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            ✏️ Editar Precio de Servicio
        </h1>
        <a href="{{ route('precioServicio.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('precioServicio.update', $precioServicio->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- servicio --}}
        <div class="mb-4">
            <label for="servicio_id" class="block text-gray-700 font-semibold mb-1">Servicio</label>
            <select name="servicio_id" id="servicio_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('servicio_id') border-red-500 @enderror">
                @foreach($servicios as $servicio)
                    <option value="{{ $servicio->id }}"
                        {{ old('servicio_id', $precioServicio->servicio_id) == $servicio->id ? 'selected' : '' }}>
                        {{ $servicio->nombre }}
                    </option>
                @endforeach
            </select>
            @error('servicio_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- tipo de vehiculo --}}
        <div class="mb-4">
            <label for="tipo_vehiculo_id" class="block text-gray-700 font-semibold mb-1">Tipo de vehículo</label>
            <select name="tipo_vehiculo_id" id="tipo_vehiculo_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('tipo_vehiculo_id') border-red-500 @enderror">
                @foreach($tipoVehiculos as $tipoVehiculo)
                    <option value="{{ $tipoVehiculo->id }}"
                        {{ old('tipo_vehiculo_id', $precioServicio->tipo_vehiculo_id) == $tipoVehiculo->id ? 'selected' : '' }}>
                        {{ $tipoVehiculo->nombre }}
                    </option>
                @endforeach
            </select>
            @error('tipo_vehiculo_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- precio --}}
        <div class="mb-6">
            <label for="precio" class="block text-gray-700 font-semibold mb-1">Precio</label>
            <input type="number" step="0.01" min="0" name="precio" id="precio"
                   value="{{ old('precio', $precioServicio->precio) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('precio') border-red-500 @enderror">
            @error('precio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('precioServicio.index') }}"
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