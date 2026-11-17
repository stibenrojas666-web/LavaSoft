@extends('layouts.app')

@section('title', 'Crear servicio')
@section('page-title', 'Registrar servicio')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6">

    <x-module-header
        title="Registrar servicio"
        description="Agrega un servicio al catálogo del negocio."
        icon="sparkles"
    />

    <form
        action="{{ route('servicios.store') }}"
        method="POST"
        novalidate
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >
        @csrf

        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-50 text-lg">
                    🧽
                </span>

                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información del servicio</h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Nombre, descripción y disponibilidad.
                    </p>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">

                <div>
                    <label for="nombre" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Nombre del servicio
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        value="{{ old('nombre') }}"
                        placeholder="Ej. Lavado general"
                        autocomplete="off"
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('nombre') border-red-400 @enderror"
                    >

                    @error('nombre')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Estado
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('estado') border-red-400 @enderror"
                    >
                        <option value="1" @selected(old('estado', 1) == 1)>Activo</option>
                        <option value="0" @selected(old('estado') == '0')>Inactivo</option>
                    </select>

                    @error('estado')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duracionMinutos" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Duración (minutos)
                    </label>

                    <input
                        type="number"
                        name="duracionMinutos"
                        id="duracionMinutos"
                        min="5"
                        max="480"
                        step="5"
                        value="{{ old('duracionMinutos', 30) }}"
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('duracionMinutos') border-red-400 @enderror"
                    >

                    @error('duracionMinutos')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-1.5 text-xs text-slate-400">
                        Tiempo estimado para realizar el servicio.
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <label for="descripcion" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        id="descripcion"
                        rows="4"
                        placeholder="Describe brevemente en qué consiste el servicio..."
                        class="w-full resize-y rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('descripcion') border-red-400 @enderror"
                    >{{ old('descripcion') }}</textarea>

                    @error('descripcion')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4 sm:flex-row">
            <a href="{{ route('servicios.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Cancelar</a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-cyan-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-500/20"
            >
                Guardar servicio
            </button>
        </div>
    </form>
</div>
@endsection
