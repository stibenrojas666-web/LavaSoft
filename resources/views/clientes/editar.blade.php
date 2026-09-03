@extends('layouts.app')

@section('title')
    Clientes - Editar
@endsection

@section('content')
    <x-card>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-blue-700">
                Editar Cliente
            </h1>
            <a href="{{ route('clientes.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                ← Volver al listado
            </a>
        </div>

        <form action="{{ route('clientes.update', $cliente) }}" method="POST" class="space-y-5">
 
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div>
                <label for="nombreCliente" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre
                </label>
                <input type="text" 
                       name="nombreCliente" 
                       id="nombreCliente"
                       value="{{ old('nombreCliente', $cliente->nombreCliente) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('nombreCliente')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Apellido --}}
            <div>
                <label for="apellidoCliente" class="block text-sm font-medium text-gray-700 mb-1">
                    Apellido
                </label>
                <input type="text" 
                       name="apellidoCliente" 
                       id="apellidoCliente"
                       value="{{ old('apellidoCliente', $cliente->apellidoCliente) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('apellidoCliente')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Teléfono --}}
            <div>
                <label for="telefonoCliente" class="block text-sm font-medium text-gray-700 mb-1">
                    Teléfono
                </label>
                <input type="text" 
                       name="telefonoCliente" 
                       id="telefonoCliente"
                       value="{{ old('telefonoCliente', $cliente->telefonoCliente) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       required>
                @error('telefonoCliente')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="emailCliente" class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input type="email" 
                       name="emailCliente" 
                       id="emailCliente"
                       value="{{ old('emailCliente', $cliente->emailCliente) }}"
                       class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                @error('emailCliente')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition duration-200">
                    Guardar cambios
                </button>

                <a href="{{ route('clientes.index') }}" 
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition duration-200">
                    Cancelar
                </a>
            </div>
        </form>
    </x-card>
@endsection