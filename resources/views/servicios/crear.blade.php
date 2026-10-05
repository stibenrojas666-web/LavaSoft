@extends('layouts.app')

@section('title')
    Crear Servicio
@endsection

@section('content')

<x-card>
    <h1 class="text-3xl font-bold mb-6 text-cyan-700">
        🧽 Registrar Nuevo Servicio
    </h1>

    <form action="{{ route('servicios.store') }}" method="POST">
        @csrf

        {{-- nombre --}}
        <div class="mb-4">
            <label for="nombre" class="block text-gray-700 font-semibold mb-1">Nombre</label>
            <input type="text" name="nombre" id="nombre"
                   value="{{ old('nombre') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('nombre') border-red-500 @enderror">
            @error('nombre')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- descripcion --}}
        <div class="mb-4">
            <label for="descripcion" class="block text-gray-700 font-semibold mb-1">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3"
                      class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                             @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Estado --}}
        <div class="mb-6">
            <label for="estado" class="block text-gray-700 font-semibold mb-1">Estado</label>
            <select name="estado" id="estado"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('estado') border-red-500 @enderror">
                <option value="1" @selected(old('estado') == 1)>Activo</option>
                <option value="0" @selected(old('estado') == 0)>Inactivo</option>
            </select>
            @error('estado')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('servicios.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Cancelar
            </a>
            <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-800 text-white font-semibold px-4 py-2 rounded-lg shadow">
                💾 Guardar Servicio
            </button>
        </div>
    </form>
</x-card>

@endsection