@extends('layouts.app')

@section('title', 'Editar Cliente')
@section('page-title', 'Editar cliente')

@section('content')
@include('clientes.partials.estilos')

<div class="cliente-page">
    <div class="mb-5">
        <div class="mb-2 flex flex-wrap items-center gap-2 text-xs font-medium text-slate-400">
            <a href="{{ route('dashboard.index') }}" class="hover:text-cyan-700">Inicio</a>
            <span>/</span>
            <a href="{{ route('clientes.index') }}" class="hover:text-cyan-700">Clientes</a>
            <span>/</span>
            <span class="font-semibold text-cyan-700">Editar cliente</span>
        </div>

        <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">Editar cliente</h1>
        <p class="mt-1 text-sm text-slate-500">Actualiza la información del cliente seleccionado.</p>
    </div>

    @include('clientes.partials.formulario')
</div>
@endsection