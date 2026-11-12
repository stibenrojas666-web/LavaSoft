
@extends('layouts.app')

@section('title', 'Editar Tipo de Vehículo')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-7">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Configuración de vehículos</p>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Editar tipo de vehículo</h1>
            <p class="text-sm text-slate-500 mt-1">Actualiza el nombre de esta categoría.</p>
        </div>
        <a href="{{ route('tipoVehiculo.index') }}" class="text-sm font-medium text-slate-500 hover:text-cyan-600">← Volver</a>
    </div>

    <form action="{{ route('tipoVehiculo.update', $tipoVehiculo->id) }}" method="POST" novalidate
          class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        @csrf
        @method('PUT')

        <div class="px-6 py-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-600">🚙</div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información del tipo</h2>
                    <p class="text-xs text-slate-400">Nombre de la categoría</p>
                </div>
            </div>

            <div>
                <label for="nombre" class="block text-xs font-semibold text-slate-600 mb-1.5">Nombre</label>
                <input type="text" name="nombre" id="nombre"
                       value="{{ old('nombre', $tipoVehiculo->nombre) }}"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('nombre') border-red-500 @enderror">
                @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
            <a href="{{ route('tipoVehiculo.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">Cancelar</a>
            <button type="submit" class="px-5 py-2 rounded-lg bg-cyan-600 text-white text-sm font-semibold hover:bg-cyan-700 transition">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection
