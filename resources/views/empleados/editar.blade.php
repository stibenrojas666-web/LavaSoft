@extends('layouts.app')

@section('title')
    Empleados - Editar
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            ✏️ Editar Empleado
        </h1>
        <a href="{{ route('empleados.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
        @csrf
        @method('PUT')

                {{-- Nombre --}}
        <div class="mb-4">
            <label for="nombre" class="block text-gray-700 font-semibold mb-1">Nombre</label>
            <input type="text" name="nombre" id="nombre"
                value="{{ old('nombre', $empleado->nombre) }}"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                        @error('nombre') border-red-500 @enderror">
            @error('nombre')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    
        {{-- Apellido --}}
        <div class="mb-4">
            <label for="apellido" class="block text-gray-700 font-semibold mb-1">Apellido</label>
            <input type="text" name="apellido" id="apellido"
                value="{{ old('apellido', $empleado->apellido) }}"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                        @error('apellido') border-red-500 @enderror">
            @error('apellido')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- IDENTIFICACION --}}
        <div class="mb-4">
            <label for="identificacion" class="block text-gray-700 font-semibold mb-1">Identificación</label>
            <input type="text" name="identificacion" id="identificacion"
                   inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                   value="{{ old('identificacion', (int) $empleado->identificacion) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('identificacion') border-red-500 @enderror">
            @error('identificacion')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Telefono --}}
        <div class="mb-4">
            <label for="telefono" class="block text-gray-700 font-semibold mb-1">Teléfono</label>
            <input type="text" name="telefono" id="telefono"
                   inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                   value="{{ old('telefono', (int) $empleado->telefono) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('telefono') border-red-500 @enderror">
            @error('telefono')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Rh --}}
        <div class="mb-4">
            <label for="rh" class="block text-gray-700 font-semibold mb-1">Rh</label>
            <input type="text" name="rh" id="rh"
                   value="{{ old('rh', $empleado->rh) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('rh') border-red-500 @enderror">
            @error('rh')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- EPS --}}
        <div class="mb-4">
            <label for="eps" class="block text-gray-700 font-semibold mb-1">EPS</label>
            <input type="text" name="eps" id="eps"
                   value="{{ old('eps', $empleado->eps) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('eps') border-red-500 @enderror">
            @error('eps')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Estado --}}
        <div class="mb-6">
            <label for="estado" class="block text-gray-700 font-semibold mb-1">Estado</label>
            <select name="estado" id="estado"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                           @error('estado') border-red-500 @enderror">
                <option value="1" @selected(old('estado', $empleado->estado) == 1)>Activo</option>
                <option value="0" @selected(old('estado', $empleado->estado) == 0)>Inactivo</option>
            </select>
            @error('estado')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('empleados.index') }}"
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