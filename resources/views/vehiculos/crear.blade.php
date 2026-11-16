@extends('layouts.app')

@section('title', 'Crear vehículo')
@section('page-title', 'Registrar vehículo')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6">

    <x-module-header
        title="Registrar vehículo"
        description="Registra un vehículo y asígnalo a uno de tus clientes."
        icon="car"
    />

    <form
        action="{{ route('vehiculos.store') }}"
        method="POST"
        novalidate
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >
        @csrf

        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-50 text-lg">
                    🚗
                </span>

                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información del vehículo</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Datos de identificación y características generales.</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">

                <div class="sm:col-span-2">
                    <label for="clienteID" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Cliente propietario
                    </label>

                    <select
                        name="clienteID"
                        id="clienteID"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('clienteID') border-red-400 @enderror"
                    >
                        <option value="">Seleccione un cliente</option>

                        @foreach($cliente as $c)
                            <option value="{{ $c->id }}" {{ old('clienteID') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombreCliente }} {{ $c->apellidoCliente }}
                            </option>
                        @endforeach
                    </select>

                    @error('clienteID')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipoVehiculoId" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Tipo de vehículo
                    </label>

                    <select
                        name="tipoVehiculoId"
                        id="tipoVehiculoId"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('tipoVehiculoId') border-red-400 @enderror"
                    >
                        <option value="">Seleccione un tipo</option>

                        @foreach($tipoVehiculo as $tv)
                            <option value="{{ $tv->id }}" {{ old('tipoVehiculoId') == $tv->id ? 'selected' : '' }}>
                                {{ $tv->nombre }}
                            </option>
                        @endforeach
                    </select>

                    @error('tipoVehiculoId')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="placa" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Placa
                    </label>

                    <input
                        type="text"
                        name="placa"
                        id="placa"
                        value="{{ old('placa') }}"
                        placeholder="ABC123"
                        autocomplete="off"
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm uppercase text-slate-700 outline-none transition placeholder:normal-case placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('placa') border-red-400 @enderror"
                    >

                    @error('placa')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="modelo" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Modelo
                    </label>

                    <input
                        type="text"
                        name="modelo"
                        id="modelo"
                        value="{{ old('modelo') }}"
                        placeholder="Ej. Toyota Corolla"
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('modelo') border-red-400 @enderror"
                    >

                    @error('modelo')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="color" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Color
                    </label>

                    <input
                        type="text"
                        name="color"
                        id="color"
                        value="{{ old('color') }}"
                        placeholder="Ej. Rojo"
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('color') border-red-400 @enderror"
                    >

                    @error('color')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Estado del vehículo
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('estado') border-red-400 @enderror"
                    >
                        <option value="">Seleccione un estado</option>
                        <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>

                    @error('estado')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4 sm:flex-row">
            <a href="{{ route('vehiculos.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Cancelar</a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-500/20"
            >
                Guardar vehículo
            </button>
        </div>
    </form>
</div>
@endsection
