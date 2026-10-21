@extends('layouts.app')

@section('title')
    Tipo de Vehiculo - Editar
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            ✏️ Editar Tipo de Vehículo
        </h1>
        <a href="{{ route('tipoVehiculo.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('tipoVehiculo.update', $tipoVehiculo->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- nombre --}}
        <div class="mb-4">
            <label for="nombre" class="block text-gray-700 font-semibold mb-1">Nombre</label>
            <input type="text" name="nombre" id="nombre"
                   value="{{ old('nombre', $tipoVehiculo->nombre) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('nombre') border-red-500 @enderror">
            @error('nombre')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('tipoVehiculo.index') }}"
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