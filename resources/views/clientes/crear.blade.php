@extends('layouts.app')

@section('title')
    Crear Cliente
@endsection

@section('content')

<x-card>
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Nuevo Cliente</h1>

    <form action="{{ route('clientes.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Nombre --}}
        <div>
            <label for="nombreCliente" class="block text-gray-700 font-semibold mb-1">Nombre</label>
            <input type="text" name="nombreCliente" id="nombreCliente"
                   value="{{ old('nombreCliente') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('nombreCliente') border-red-500 @enderror">
            @error('nombreCliente')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Apellido --}}
        <div>
            <label for="apellidoCliente" class="block text-gray-700 font-semibold mb-1">Apellido</label>
            <input type="text" name="apellidoCliente" id="apellidoCliente"
                   value="{{ old('apellidoCliente') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('apellidoCliente') border-red-500 @enderror">
            @error('apellidoCliente')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Teléfono --}}
        <div>
            <label for="telefonoCliente" class="block text-gray-700 font-semibold mb-1">Teléfono</label>
            <input type="text" name="telefonoCliente" id="telefonoCliente"
                   value="{{ old('telefonoCliente') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('telefonoCliente') border-red-500 @enderror">
            @error('telefonoCliente')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="emailCliente" class="block text-gray-700 font-semibold mb-1">Email</label>
            <input type="email" name="emailCliente" id="emailCliente"
                   value="{{ old('emailCliente') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300
                          @error('emailCliente') border-red-500 @enderror">
            @error('emailCliente')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit"
                class="bg-pink-300 hover:bg-pink-400 text-white font-semibold px-4 py-2 rounded shadow">
                💾 Guardar
            </button>
            <a href="{{ route('clientes.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-4 py-2 rounded shadow">
               ↩️ Cancelar
            </a>
        </div>
    </form>
</x-card>

@endsection