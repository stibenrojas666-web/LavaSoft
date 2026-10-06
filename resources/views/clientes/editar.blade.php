@extends('layouts.app')

@section('title')
    Clientes - Editar
@endsection

@section('content')

<x-card>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-cyan-700">
            ✏️ Editar Cliente
        </h1>
        <a href="{{ route('clientes.index') }}"
            class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
            ← Volver al listado
        </a>
    </div>

    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-4">
            <label for="nombreCliente" class="block text-gray-700 font-semibold mb-1">Nombre</label>
            <input type="text" name="nombreCliente" id="nombreCliente"
                   value="{{ old('nombreCliente', $cliente->nombreCliente) }}"
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
                   value="{{ old('apellidoCliente', $cliente->apellidoCliente) }}"
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
                   value="{{ old('telefonoCliente', $cliente->telefonoCliente) }}"
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
                   value="{{ old('emailCliente', $cliente->emailCliente) }}"
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
                💾 Guardar cambios
            </button>
        </div>
    </form>
</x-card>

@endsection