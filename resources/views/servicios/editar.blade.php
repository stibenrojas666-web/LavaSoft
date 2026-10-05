@extends('layouts.app')

@section('title')
    servicios - Editar
@endsection

@section('content')
    <x-card>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-blue-700">
                Editar Servicio
            </h1>
            <a href="{{ route('servicios.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                ← Volver al listado
            </a>
        </div>

        <form action="{{ route('servicios.update', $servicio->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- nombre --}}
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                    nombre
                </label>
                <input type="text" 
                       name="nombre" 
                       id="nombre"
                       value="{{ old('nombre', $servicio->nombre) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripcion --}}
            <div>
            <label for="descripcion" class="block text-gray-700 font-semibold mb-1">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3"
                      class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300
                             @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $servicio->descripcion) }}</textarea>
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
                <option value="1" @selected(old('estado', $servicio->estado) == 1)>Activo</option>
                <option value="0" @selected(old('estado', $servicio->estado) == 0)>Inactivo</option>
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

                <a href="{{ route('servicios.index') }}" 
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition duration-200">
                    Cancelar
                </a>
            </div>
        </form>
    </x-card>
@endsection