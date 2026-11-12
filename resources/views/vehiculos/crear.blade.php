
@extends('layouts.app')

@section('title', 'Crear Vehículo')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-7">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Gestión de vehículos</p>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Registrar vehículo</h1>
            <p class="text-sm text-slate-500 mt-1">Registra un nuevo vehículo en el sistema.</p>
        </div>
        <a href="{{ route('vehiculos.index') }}" class="text-sm font-medium text-slate-500 hover:text-cyan-600">← Volver</a>
    </div>

    <form action="{{ route('vehiculos.store') }}" method="POST" novalidate
          class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        @csrf

        <div class="px-6 py-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-600">🚗</div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información del vehículo</h2>
                    <p class="text-xs text-slate-400">Datos de identificación y características</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="clienteID" class="block text-xs font-semibold text-slate-600 mb-1.5">Cliente</label>
                    <select name="clienteID" id="clienteID" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('clienteID') border-red-500 @enderror">
                        <option value="">Seleccione un cliente</option>
                        @foreach($cliente as $c)
                            <option value="{{ $c->id }}" {{ old('clienteID') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombreCliente }} {{ $c->apellidoCliente }}
                            </option>
                        @endforeach
                    </select>
                    @error('clienteID') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tipoVehiculoId" class="block text-xs font-semibold text-slate-600 mb-1.5">Tipo de vehículo</label>
                    <select name="tipoVehiculoId" id="tipoVehiculoId" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('tipoVehiculoId') border-red-500 @enderror">
                        <option value="">Seleccione un tipo</option>
                        @foreach($tipoVehiculo as $tv)
                            <option value="{{ $tv->id }}" {{ old('tipoVehiculoId') == $tv->id ? 'selected' : '' }}>
                                {{ $tv->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipoVehiculoId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="placa" class="block text-xs font-semibold text-slate-600 mb-1.5">Placa</label>
                    <input type="text" name="placa" id="placa" value="{{ old('placa') }}" placeholder="ABC123"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('placa') border-red-500 @enderror">
                    @error('placa') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="modelo" class="block text-xs font-semibold text-slate-600 mb-1.5">Modelo</label>
                    <input type="text" name="modelo" id="modelo" value="{{ old('modelo') }}" placeholder="Toyota Corolla"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('modelo') border-red-500 @enderror">
                    @error('modelo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="color" class="block text-xs font-semibold text-slate-600 mb-1.5">Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color') }}" placeholder="Rojo"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('color') border-red-500 @enderror">
                    @error('color') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="estado" class="block text-xs font-semibold text-slate-600 mb-1.5">Estado</label>
                    <select name="estado" id="estado" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('estado') border-red-500 @enderror">
                        <option value="">Seleccione un estado</option>
                        <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
            <a href="{{ route('vehiculos.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">Cancelar</a>
            <button type="submit" class="px-5 py-2 rounded-lg bg-cyan-600 text-white text-sm font-semibold hover:bg-cyan-700 transition">Guardar vehículo</button>
        </div>
    </form>
</div>
@endsection
