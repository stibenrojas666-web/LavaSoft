
@extends('layouts.app')

@section('title')
    Agregar Servicio a Cita
@endsection

@section('content')

<div class="mx-auto max-w-3xl px-4 py-6">

    {{-- Encabezado --}}
    <div class="mb-7 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Agregar servicio a una cita</h1>
            <p class="mt-1 text-sm text-slate-500">Asocia un servicio y un vehículo a una cita registrada.</p>
        </div>

        <a href="{{ route('agendaServicio.index') }}"
           class="shrink-0 text-sm font-medium text-slate-500 hover:text-cyan-600">
            ← Volver
        </a>
    </div>

    {{-- Formulario --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <form action="{{ route('agendaServicio.store') }}" method="POST" novalidate>
            @csrf

            <div class="space-y-5">

                {{-- Cita --}}
                <div>
                    <label for="agendaId" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Cita
                    </label>

                    <select name="agendaId" id="agendaId"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('agendaId') border-red-500 @enderror">

                        <option value="">Seleccione una cita</option>

                        @foreach($agenda as $a)
                            <option value="{{ $a->id }}" {{ old('agendaId') == $a->id ? 'selected' : '' }}>
                                #{{ $a->id }} - {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                                {{ substr($a->hora, 0, 5) }} - {{ $a->cliente->nombreCliente ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>

                    @error('agendaId')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Vehículo --}}
                <div>
                    <label for="vehiculoId" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Vehículo de la cita
                    </label>

                    <select name="vehiculoId" id="vehiculoId"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('vehiculoId') border-red-500 @enderror">

                        <option value="">Seleccione un vehículo</option>

                        @foreach($vehiculo as $v)
                            <option value="{{ $v->id }}" @selected(old('vehiculoId') == $v->id)>
                                {{ $v->placa }} · {{ $v->tipoVehiculo->nombre ?? 'Vehículo' }} · {{ $v->cliente->nombreCliente ?? 'Cliente' }}
                            </option>
                        @endforeach
                    </select>

                    @error('vehiculoId')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Servicio --}}
                <div>
                    <label for="servicioId" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Servicio
                    </label>

                    <select name="servicioId" id="servicioId"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('servicioId') border-red-500 @enderror">

                        <option value="">Seleccione un servicio</option>

                        @foreach($servicio as $s)
                            <option value="{{ $s->id }}" {{ old('servicioId') == $s->id ? 'selected' : '' }}>
                                {{ $s->nombre }}
                            </option>
                        @endforeach
                    </select>

                    @error('servicioId')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Precio --}}
                <div>
                    <label for="Precio" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Precio acordado
                    </label>

                    <input type="number"
                        step="0.01"
                        min="0.01"
                        max="99999999.99"
                        name="Precio"
                        id="Precio"
                        value="{{ old('Precio') }}"
                        placeholder="Déjalo vacío para precios del catálogo"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('Precio') border-red-500 @enderror">

                    <p class="mt-1.5 text-xs leading-relaxed text-slate-500">
                        Para precio fijo o “Desde”, el sistema usa el catálogo según el vehículo.
                        Si es “A convenir”, escribe aquí el valor acordado.
                    </p>

                    @error('Precio')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Botones --}}
            <div class="mt-7 flex flex-wrap justify-end gap-2 border-t border-slate-100 pt-5">

                <a href="{{ route('agendaServicio.index') }}"
                   class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                    Cancelar
                </a>

                <button type="submit"
                    class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                    Guardar
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
