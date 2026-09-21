@extends('layouts.app')

@section('title')
    Crear Servicio
@endsection

@section('content')

<x-card>
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Nuevo Servicio</h1>

    <form action="{{ route('servicios.store') }}" method="POST" class="space-y-4">
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

        {{-- descripcion --}}
        <div>
            <label for="descripcion" class="block text-gray-700 font-semibold mb-1">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3"
                      class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300
                             @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

       {{-- Estado --}}
        <div>
            <label for="estado" class="block text-gray-700 font-semibold mb-1">Estado</label>
            <select name="estado" id="estado"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300
                           @error('estado') border-red-500 @enderror">
                <option value="1" @selected(old('estado') == 1)>Activo</option>
                <option value="0" @selected(old('estado') == 0)>Inactivo</option>
            </select>
            @error('estado')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        <div class="flex gap-3 mt-6">
            <button type="submit"
                class="bg-pink-300 hover:bg-pink-400 text-white font-semibold px-4 py-2 rounded shadow">
                💾 Guardar
            </button>
            <a href="{{ route('servicios.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-4 py-2 rounded shadow">
               ↩️ Cancelar
            </a>
        </div>
    </form>
</x-card>

@endsection