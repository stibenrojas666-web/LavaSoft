@extends('layouts.app')

@section('title')
    Crear Cliente
@endsection

@section('content')

<x-card>
    <h1 class="text-3xl font-bold mb-6 text-cyan-700">
        🧑‍🤝‍🧑 Registrar Nuevo Cliente
    </h1>

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        {{-- Nombre --}}
        <div class="mb-4">
            <label for="nombreCliente" class="block text-gray-700 font-semibold mb-1">Nombre</label>
            <input type="text" name="nombreCliente" id="nombreCliente"
                   value="{{ old('nombreCliente') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('nombreCliente') border-red-500 @enderror">
            @error('nombreCliente')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Apellido --}}
        <div class="mb-4">
            <label for="apellidoCliente" class="block text-gray-700 font-semibold mb-1">Apellido</label>
            <input type="text" name="apellidoCliente" id="apellidoCliente"
                   value="{{ old('apellidoCliente') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('apellidoCliente') border-red-500 @enderror">
            @error('apellidoCliente')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Teléfono --}}
        <div class="mb-4">
            <label for="telefonoCliente" class="block text-gray-700 font-semibold mb-1">Teléfono</label>
            <input type="text" name="telefonoCliente" id="telefonoCliente"
                   value="{{ old('telefonoCliente') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('telefonoCliente') border-red-500 @enderror">
            @error('telefonoCliente')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-6">
            <label for="emailCliente" class="block text-gray-700 font-semibold mb-1">Email</label>
            <input type="email" name="emailCliente" id="emailCliente"
                   value="{{ old('emailCliente') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400
                          @error('emailCliente') border-red-500 @enderror">
            @error('emailCliente')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('clientes.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Cancelar
            </a>
            <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-800 text-white font-semibold px-4 py-2 rounded-lg shadow">
                💾 Guardar Cliente
            </button>
        </div>
    </form>
</x-card>

@endsection