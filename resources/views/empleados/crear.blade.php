@extends('layouts.app')

@section('title')
    Crear Empleados
@endsection

@section('content')

<x-card>
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Nuevo Empleado</h1>

    <form action="{{ route('empleados.store') }}" method="POST" class="space-y-4">
        @csrf


         {{-- IDENTIFICACION --}}
        <div>
            <label for="identificacion" class="block text-gray-700 font-semibold mb-1">identificacion</label>
            <input type="text" name="identificacion" id="identificacion"
            inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                   value="{{ old('identificacion') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('identificacion') border-red-500 @enderror">
            @error('identificacion')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Telefono --}}
        <div>
            <label for="telefono" class="block text-gray-700 font-semibold mb-1">Telefono</label>
            <input type="text" name="telefono" id="telefono"
            inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                   value="{{ old('telefono') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('telefono') border-red-500 @enderror">
            @error('telefono')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Rh --}}
        <div>
            <label for="rh" class="block text-gray-700 font-semibold mb-1">rh</label>
            <input type="text" name="rh" id="rh"
                   value="{{ old('rh') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('rh') border-red-500 @enderror">
            @error('rh')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- EPS --}}
        <div>
            <label for="eps" class="block text-gray-700 font-semibold mb-1">EPS</label>
            <input type="text" name="eps" id="eps"
                   value="{{ old('eps') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('eps') border-red-500 @enderror">
            @error('eps')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- EPS --}}
        <div>
            <label for="estado" class="block text-gray-700 font-semibold mb-1">estado</label>
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
            <a href="{{ route('empleados.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-4 py-2 rounded shadow">
               ↩️ Cancelar
            </a>
        </div>
    </form>
</x-card>

@endsection