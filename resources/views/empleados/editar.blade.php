
@extends('layouts.app')



@section('title', 'Empleados - Editar')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-7">
        <div>
            
            <h1 class="text-2xl font-bold text-slate-800">
                Editar empleado
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Actualiza la información del empleado registrado.
            </p>
        </div>

        <a href="{{ route('empleados.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-cyan-600 transition">
            <span class="text-lg">←</span>
            Volver
        </a>
    </div>

    {{-- Formulario --}}
    <form action="{{ route('empleados.update', $empleado->id) }}"
          method="POST"
          novalidate
          class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">

        @csrf
        @method('PUT')

        {{-- Información personal --}}
        <div class="px-6 py-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-600">
                    <span class="text-lg">👤</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información personal</h2>
                    <p class="text-xs text-slate-400">Datos básicos del empleado</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                <div>
                    <label for="nombre" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Nombre
                    </label>
                    <input type="text" id="nombre" name="nombre"
                           value="{{ old('nombre', $empleado->nombre) }}"
                           class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                    @error('nombre')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="apellido" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Apellido
                    </label>
                    <input type="text" id="apellido" name="apellido"
                           value="{{ old('apellido', $empleado->apellido) }}"
                           class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                    @error('apellido')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="identificacion" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Identificación
                    </label>
                    <input type="text" id="identificacion" name="identificacion"
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           value="{{ old('identificacion', (int) $empleado->identificacion) }}"
                           class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                    @error('identificacion')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefono" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Teléfono
                    </label>
                    <input type="text" id="telefono" name="telefono"
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           value="{{ old('telefono', (int) $empleado->telefono) }}"
                           class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                    @error('telefono')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <div class="border-t border-slate-100"></div>

        {{-- Información complementaria --}}
        <div class="px-6 py-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-600">
                    <span class="text-lg">📋</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información complementaria</h2>
                    <p class="text-xs text-slate-400">Datos adicionales y estado</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                <div>
                    <label for="rh" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Tipo de sangre (RH)
                    </label>
                    <select id="rh" name="rh"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                        @foreach(config('empleado.tiposSangre') as $tipoSangre)
                            <option value="{{ $tipoSangre }}"
                                {{ old('rh', $empleado->rh) === $tipoSangre ? 'selected' : '' }}>
                                {{ $tipoSangre }}
                            </option>
                        @endforeach
                    </select>
                    @error('rh')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="eps" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        EPS
                    </label>
                    <input type="text" id="eps" name="eps"
                           value="{{ old('eps', $empleado->eps) }}"
                           class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                    @error('eps')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Estado del empleado
                    </label>
                    <select id="estado" name="estado"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                        <option value="1" {{ old('estado', $empleado->estado) == 1 ? 'selected' : '' }}>
                            Activo
                        </option>
                        <option value="0" {{ old('estado', $empleado->estado) == 0 ? 'selected' : '' }}>
                            Inactivo
                        </option>
                    </select>
                    @error('estado')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
        <div>
    <label for="porcentajeComision" class="block text-xs font-semibold text-slate-600 mb-1.5">
        Porcentaje de comisión (%)
    </label>

    <input
        type="number"
        name="porcentajeComision"
        id="porcentajeComision"
        min="0"
        max="100"
        step="0.01"
        value="{{ old('porcentajeComision', $empleado->porcentajeComision ?? 0) }}"
        placeholder="Ejemplo: 10"
        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('porcentajeComision') border-red-500 @enderror"
    >

    @error('porcentajeComision')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror

    <p class="text-xs text-slate-400 mt-1">
        Ingresa 0 si el empleado no recibe comisión.
    </p>
</div>

        {{-- Acciones --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
            <a href="{{ route('empleados.index') }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">
                Cancelar
            </a>

            <button type="submit"
                    class="px-5 py-2 rounded-lg bg-cyan-600 text-white text-sm font-semibold shadow-sm hover:bg-cyan-700 transition">
                Guardar cambios
            </button>
        </div>

    </form>
</div>
@endsection
