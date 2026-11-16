@extends('layouts.app')

@section('title', 'Editar tipo de vehículo')
@section('page-title', 'Editar tipo de vehículo')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6">
    <x-module-header
        title="Editar tipo de vehículo"
        description="Actualiza la categoría del vehículo."
        icon="box"
    />

    <form
        action="{{ route('tipoVehiculo.update', $tipoVehiculo->id) }}"
        method="POST"
        novalidate
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-50 text-lg">
                    🚙
                </span>

                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información del tipo</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Modifica el nombre de esta categoría.</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <label for="nombre" class="mb-1.5 block text-xs font-semibold text-slate-600">
                Nombre del tipo de vehículo
            </label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                value="{{ old('nombre', $tipoVehiculo->nombre) }}"
                placeholder="Ej. Automóvil, motocicleta..."
                autocomplete="off"
                class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('nombre') border-red-400 @enderror"
            >

            @error('nombre')
                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4 sm:flex-row">
            <a href="{{ route('tipoVehiculo.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Cancelar</a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-cyan-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-500/20"
            >
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
