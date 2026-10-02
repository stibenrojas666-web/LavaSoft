@extends('layouts.app')

@section('title')
    Precio de Servicio - Editar
@endsection

@section('content')
    <x-card>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-blue-700">Editar Precio de Servicio</h1>
            <a href="{{ route('precioServicio.index') }}"
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                ← Volver al listado
            </a>
        </div>

        <form action="{{ route('precioServicio.update', $precioServicio->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- servicio --}}
            <div>
                <label for="servicio_id" class="block text-sm font-medium text-gray-700 mb-1">Servicio</label>
                <select name="servicio_id" id="servicio_id"
                        class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}"
                            {{ old('servicio_id', $precioServicio->servicio_id) == $servicio->id ? 'selected' : '' }}>
                            {{ $servicio->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('servicio_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- tipo de vehiculo --}}
            <div>
                <label for="tipo_vehiculo_id" class="block text-sm font-medium text-gray-700 mb-1">Tipo de vehículo</label>
                <select name="tipo_vehiculo_id" id="tipo_vehiculo_id"
                        class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    @foreach($tipoVehiculos as $tipoVehiculo)
                        <option value="{{ $tipoVehiculo->id }}"
                            {{ old('tipo_vehiculo_id', $precioServicio->tipo_vehiculo_id) == $tipoVehiculo->id ? 'selected' : '' }}>
                            {{ $tipoVehiculo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_vehiculo_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- precio --}}
            <div>
                <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                <input type="number" step="0.01" min="0"
                       name="precio"
                       id="precio"
                       value="{{ old('precio', $precioServicio->precio) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('precio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition duration-200">
                    Guardar cambios
                </button>
                <a href="{{ route('precioServicio.index') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition duration-200">
                    Cancelar
                </a>
            </div>
        </form>
    </x-card>
@endsection