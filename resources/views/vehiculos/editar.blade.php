@extends('layouts.app')

@section('title')
    Editar Vehículo
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            ✏️ Editar Vehículo
        </h1>

        <a href="{{ route('vehiculos.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Cliente --}}
        <div class="mb-4">
            <label for="clienteID" class="block text-gray-700 font-semibold mb-1">Cliente</label>

            <select name="clienteID" id="clienteID"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('clienteID') border-red-500 @enderror">

                <option value="">Seleccione un cliente</option>

                @foreach($cliente as $c)
                    <option value="{{ $c->id }}"
                        {{ old('clienteID', $vehiculo->clienteID) == $c->id ? 'selected' : '' }}>
                        {{ $c->nombreCliente }} {{ $c->apellidoCliente }}
                    </option>
                @endforeach
            </select>

            @error('clienteID')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tipo de vehículo --}}
        <div class="mb-4">
            <label for="tipoVehiculoId" class="block text-gray-700 font-semibold mb-1">Tipo de Vehículo</label>

            <select name="tipoVehiculoId" id="tipoVehiculoId"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('tipoVehiculoId') border-red-500 @enderror">

                <option value="">Seleccione un tipo</option>

                @foreach($tipoVehiculo as $tv)
                    <option value="{{ $tv->id }}"
                        {{ old('tipoVehiculoId', $vehiculo->tipoVehiculoId) == $tv->id ? 'selected' : '' }}>
                        {{ $tv->nombre }}
                    </option>
                @endforeach
            </select>

            @error('tipoVehiculoId')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Placa --}}
        <div class="mb-4">
            <label for="placa" class="block text-gray-700 font-semibold mb-1">Placa</label>

            <input type="text" name="placa" id="placa"
                value="{{ old('placa', $vehiculo->placa) }}"
                placeholder="ABC123"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('placa') border-red-500 @enderror">

            @error('placa')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Modelo --}}
        <div class="mb-4">
            <label for="modelo" class="block text-gray-700 font-semibold mb-1">Modelo</label>

            <input type="text" name="modelo" id="modelo"
                value="{{ old('modelo', $vehiculo->modelo) }}"
                placeholder="Toyota Corolla"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('modelo') border-red-500 @enderror">

            @error('modelo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Color --}}
        <div class="mb-4">
            <label for="color" class="block text-gray-700 font-semibold mb-1">Color</label>

            <input type="text" name="color" id="color"
                value="{{ old('color', $vehiculo->color) }}"
                placeholder="Rojo"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('color') border-red-500 @enderror">

            @error('color')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Estado --}}
        <div class="mb-6">
            <label for="estado" class="block text-gray-700 font-semibold mb-1">Estado</label>

            <select name="estado" id="estado"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('estado') border-red-500 @enderror">

                <option value="Activo"
                    {{ old('estado', $vehiculo->estado) == 'Activo' ? 'selected' : '' }}>
                    Activo
                </option>

                <option value="Inactivo"
                    {{ old('estado', $vehiculo->estado) == 'Inactivo' ? 'selected' : '' }}>
                    Inactivo
                </option>
            </select>

            @error('estado')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('vehiculos.index') }}"
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