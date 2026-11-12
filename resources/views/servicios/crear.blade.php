
@extends('layouts.app')

@section('title', 'Crear Servicio')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-7">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Gestión de servicios</p>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Registrar servicio</h1>
            <p class="text-sm text-slate-500 mt-1">Agrega un servicio al catálogo del negocio.</p>
        </div>
        <a href="{{ route('servicios.index') }}" class="text-sm font-medium text-slate-500 hover:text-cyan-600">← Volver</a>
    </div>

    <form action="{{ route('servicios.store') }}" method="POST" novalidate
          class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        @csrf

        <div class="px-6 py-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-600">🧽</div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información del servicio</h2>
                    <p class="text-xs text-slate-400">Nombre, descripción y disponibilidad</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="nombre" class="block text-xs font-semibold text-slate-600 mb-1.5">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('nombre') border-red-500 @enderror">
                    @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="estado" class="block text-xs font-semibold text-slate-600 mb-1.5">Estado</label>
                    <select name="estado" id="estado"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('estado') border-red-500 @enderror">
                        <option value="1" @selected(old('estado', 1) == 1)>Activo</option>
                        <option value="0" @selected(old('estado') == '0')>Inactivo</option>
                    </select>
                    @error('estado') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="duracionMinutos" class="block text-xs font-semibold text-slate-600 mb-1.5">Duración (minutos)</label>
                    <input type="number" name="duracionMinutos" id="duracionMinutos" min="5" max="480" step="5"
                        value="{{ old('duracionMinutos', 30) }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('duracionMinutos') border-red-500 @enderror">
                    @error('duracionMinutos') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="sm:col-span-2">
                    <label for="descripcion" class="block text-xs font-semibold text-slate-600 mb-1.5">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                              class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
            <a href="{{ route('servicios.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">Cancelar</a>
            <button type="submit" class="px-5 py-2 rounded-lg bg-cyan-600 text-white text-sm font-semibold hover:bg-cyan-700 transition">Guardar servicio</button>
        </div>
    </form>
</div>
@endsection
