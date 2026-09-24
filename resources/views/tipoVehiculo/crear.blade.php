@extends('layouts.app')

@section('title')
    Crear Tipo de Vehiculo
@endsection

@section('content')

<x-card>
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Nuevo Tipo de Vehiculo</h1>

    <form action="{{ route('tipoVehiculo.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- nombre --}}
        <div>
            <label for="nombre" class="block text-gray-700 font-semibold mb-1">Nombre</label>
            <input type="text" name="nombre" id="nombre"
                   value="{{ old('nombre') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('nombre') border-red-500 @enderror">
            @error('nombre')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        <div class="flex gap-3 mt-6">
            <button type="submit"
                class="bg-pink-300 hover:bg-pink-400 text-white font-semibold px-4 py-2 rounded shadow">
                💾 Guardar
            </button>
            <a href="{{ route('tipoVehiculo.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-4 py-2 rounded shadow">
               ↩️ Cancelar
            </a>
        </div>
    </form>
</x-card>

@endsection