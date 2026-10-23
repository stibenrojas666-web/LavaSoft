@extends('layouts.app')

@section('title')
    Crear Cliente
@endsection

@section('content')

<x-card>
    <h1 class="text-3xl font-bold mb-6 text-cyan-700">
        🧑‍🤝‍🧑 Registrar Nuevo Cliente
    </h1>

    <form action="{{ route('clientes.store') }}" method="POST" novalidate>
        @csrf

        {{-- Nombre --}}
        <x-input
           name="nombreCliente"
           label="Nombre"
           :value="old('nombreCliente')"
            class="mb-4"
        />

        {{-- Apellido --}}
        <x-input
            name="apellidoCliente"
            label="Apellido"
            :value="old('apellidoCliente')"
            class="mb-4"
        />

        {{-- Teléfono --}}
        <x-input
            name="telefonoCliente"
            label="Teléfono"
            type="tel"
            :value="old('telefonoCliente')"
            inputmode="numeric"
            class="mb-4"
        />

        {{-- Email --}}
        <x-input
            name="emailCliente"
            label="Email"
            type="email"
            :value="old('emailCliente')"
            class="mb-6"
        />

        <div class="flex justify-end gap-2">
            <x-button variant="neutral" :href="route('clientes.index')">
                Cancelar
            </x-button>
            <x-button variant="cyan" type="submit">
                💾 Guardar Cliente
            </x-button>
        </div>
    </form>
</x-card>

@endsection
