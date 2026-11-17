@extends('layouts.app')

@section('title', 'Editar servicio de cita')
@section('page-title', 'Editar servicio de cita')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6">

    <x-module-header
        title="Editar servicio de la cita"
        description="Actualiza la información del servicio asociado a una cita."
    />

    <form
        action="{{ route('agendaServicio.update', $agendaServicio->id) }}"
        method="POST"
        novalidate
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-50 text-cyan-800">
                    @include('layouts.partials.icons', ['icon' => 'clipboard-list'])
                </span>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información del registro</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Modifica la cita, el vehículo, el servicio o el precio.</p>
                </div>
            </div>
        </div>

        <div class="space-y-5 px-6 py-6">

            <div>
                <label for="agendaId" class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Cita
                </label>

                <select
                    name="agendaId"
                    id="agendaId"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('agendaId') border-red-400 @enderror"
                >
                    <option value="">Seleccione una cita</option>

                    @foreach($agenda as $a)
                        <option value="{{ $a->id }}" {{ old('agendaId', $agendaServicio->agendaId) == $a->id ? 'selected' : '' }}>
                            #{{ $a->referencia }} - {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                            {{ substr($a->hora, 0, 5) }} - {{ $a->cliente->nombreCliente ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>

                @error('agendaId')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="vehiculoId" class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Vehículo de la cita
                </label>

                <select
                    name="vehiculoId"
                    id="vehiculoId"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('vehiculoId') border-red-400 @enderror"
                >
                    <option value="">Seleccione un vehículo</option>

                    @foreach($vehiculo as $v)
                        <option value="{{ $v->id }}" @selected(old('vehiculoId', $agendaServicio->vehiculoId) == $v->id)>
                            {{ $v->placa }} · {{ $v->tipoVehiculo->nombre ?? 'Vehículo' }} · {{ $v->cliente->nombreCliente ?? 'Cliente' }}
                        </option>
                    @endforeach
                </select>

                @error('vehiculoId')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="servicioId" class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Servicio
                </label>

                <select
                    name="servicioId"
                    id="servicioId"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('servicioId') border-red-400 @enderror"
                >
                    <option value="">Seleccione un servicio</option>

                    @foreach($servicio as $s)
                        <option value="{{ $s->id }}" {{ old('servicioId', $agendaServicio->servicioId) == $s->id ? 'selected' : '' }}>
                            {{ $s->nombre }}
                        </option>
                    @endforeach
                </select>

                @error('servicioId')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="Precio" class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Precio acordado
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0.01"
                    max="99999999.99"
                    name="Precio"
                    id="Precio"
                    value="{{ old('Precio', $agendaServicio->Precio) }}"
                    placeholder="Déjalo vacío para precios del catálogo"
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 @error('Precio') border-red-400 @enderror"
                >

                <p class="mt-1.5 text-xs leading-relaxed text-slate-500">
                    Para precio fijo o “Desde”, el sistema usa el catálogo según el vehículo.
                    Si es “A convenir”, escribe aquí el valor acordado.
                </p>

                @error('Precio')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4 sm:flex-row">
            <a href="{{ route('agendaServicio.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Cancelar</a>

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
