@extends('layouts.app')

@section('title')
    Empleados - Editar
@endsection

@section('content')
    <x-card>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-blue-700">
                Editar Empleado
            </h1>
            <a href="{{ route('empleados.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                ← Volver al listado
            </a>
        </div>

        <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- IDENTIFICACION --}}
            <div>
                <label for="identificacion" class="block text-sm font-medium text-gray-700 mb-1">
                    Identificacion
                </label>
                <input type="text" 
                       name="identificacion" 
                       id="identificacion"
                       inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       value="{{ old('identificacion', (int) $empleado->identificacion) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('identificacion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- TELEFONO --}}
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                    Teléfono
                </label>
                <input type="text" 
                       name="telefono" 
                       id="telefono"
                       inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                      value="{{ old('telefono', (int) $empleado->telefono) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('telefono')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- RH --}}
            <div>
                <label for="rh" class="block text-sm font-medium text-gray-700 mb-1">
                    RH
                </label>
                <input type="text" 
                       name="rh" 
                       id="rh"
                       value="{{ old('rh', $empleado->rh) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('rh')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- EPS --}}
            <div>
                <label for="eps" class="block text-sm font-medium text-gray-700 mb-1">
                    EPS
                </label>
                <input type="text" 
                       name="eps" 
                       id="eps"
                       value="{{ old('eps', $empleado->eps) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('eps')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ESTADO --}}
            <div>
            <label for="estado" class="block text-gray-700 font-semibold mb-1">Estado</label>
            <select name="estado" id="estado"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300
                           @error('estado') border-red-500 @enderror">
                <option value="1" @selected(old('estado', $empleado->estado) == 1)>Activo</option>
                <option value="0" @selected(old('estado', $empleado->estado) == 0)>Inactivo</option>
            </select>
            @error('estado')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

            {{-- Botones --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition duration-200">
                    Guardar cambios
                </button>

                <a href="{{ route('empleados.index') }}" 
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition duration-200">
                    Cancelar
                </a>
            </div>
        </form>
    </x-card>
@endsection