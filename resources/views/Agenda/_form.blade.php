
@php($esEdicion = isset($agenda))

<div class="mx-auto max-w-5xl px-4 py-6">

    <x-module-header
        :title="$esEdicion ? 'Editar cita' : 'Agendar nueva cita'"
        :description="$esEdicion ? 'Actualiza la información de la cita.' : 'Ingresa los datos para registrar una nueva cita.'"
        icon="calendar"
        :back-url="route('agenda.index')"
    />

    <form action="{{ $esEdicion ? route('agenda.update', $agenda->id) : route('agenda.store') }}"
          method="POST"
          novalidate
          id="formularioAgenda"
          class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        @csrf

        @if($esEdicion)
            @method('PUT')
        @endif

        {{-- Información de la cita --}}
        <div class="border-b border-slate-100 px-6 py-5">

            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyan-50 text-cyan-600">
                    📅
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Información de la cita</h2>
                    <p class="text-xs text-slate-400">Datos del cliente, fecha y empleado</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">

                <div class="sm:col-span-2">
                    <label for="clienteId" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Cliente
                    </label>

                    <select name="clienteId" id="clienteId"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('clienteId') border-red-500 @enderror">
                        <option value="">Seleccione un cliente</option>

                        @foreach($cliente as $c)
                            <option value="{{ $c->id }}"
                                @selected((string) old('clienteId', $agenda->clienteId ?? '') === (string) $c->id)>
                                {{ $c->nombreCliente }} {{ $c->apellidoCliente }}
                            </option>
                        @endforeach
                    </select>

                    @error('clienteId')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fecha" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Fecha
                    </label>

                    <input type="date" name="fecha" id="fecha"
                           value="{{ old('fecha', $agenda->fecha ?? request('fecha', '')) }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('fecha') border-red-500 @enderror">

                    @error('fecha')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hora" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Hora
                    </label>

                    <input type="time" name="hora" id="hora"
                           value="{{ old('hora', isset($agenda) ? substr($agenda->hora, 0, 5) : '') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('hora') border-red-500 @enderror">

                    @error('hora')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="empleadoId" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Empleado
                        <span class="font-normal text-slate-400">(opcional)</span>
                    </label>

                    <select name="empleadoId" id="empleadoId"
                            data-url="{{ route('agenda.empleadosDisponibles') }}"
                            data-agenda-id="{{ $agenda->id ?? '' }}"
                            data-selected="{{ old('empleadoId', $agenda->empleadoId ?? '') }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('empleadoId') border-red-500 @enderror">

                        <option value="">Sin asignar</option>

                        @foreach($empleado as $e)
                            <option value="{{ $e->id }}">
                                {{ $e->nombre }} {{ $e->apellido }}
                            </option>
                        @endforeach
                    </select>

                    @error('empleadoId')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <p id="mensajeEmpleados" class="mt-1.5 text-xs text-slate-400">
                        Elige fecha y hora para consultar disponibilidad.
                    </p>
                </div>

            </div>
        </div>

        {{-- Vehículos y servicios --}}
        <div class="px-6 py-5">

            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyan-50 text-cyan-600">
                    🚘
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Vehículos y servicios</h2>
                    <p class="text-xs text-slate-400">Selecciona los vehículos y los servicios solicitados</p>
                </div>
            </div>

            <div id="bloquesVehiculos" class="space-y-4">

                @foreach($bloquesIniciales as $indiceVehiculo => $bloque)
                    <section class="rounded-xl border border-slate-200 bg-slate-50/60 p-4"
                             data-bloque-vehiculo
                             data-indice="{{ $indiceVehiculo }}">

                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-700">
                                Vehículo <span data-numero-bloque>{{ $loop->iteration }}</span>
                            </h3>

                            <button type="button"
                                    data-quitar-vehiculo
                                    title="Quitar vehículo"
                                    aria-label="Quitar vehículo"
                                    class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                <span class="h-4 w-4">
                                    @include('layouts.partials.icons', ['icon' => 'x'])
                                </span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 items-start gap-4 md:grid-cols-2">

                            <div class="min-w-0">
                                <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                                    Vehículo
                                </label>

                                <select name="vehiculos[{{ $indiceVehiculo }}][vehiculoId]"
                                        data-select-vehiculo
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">

                                    <option value="">Selecciona un vehículo</option>

                                    @foreach($vehiculo as $v)
                                        <option value="{{ $v->id }}"
                                                data-cliente-id="{{ $v->clienteID }}"
                                                data-tipo="{{ $v->tipoVehiculoId }}"
                                                @selected((string) ($bloque['vehiculoId'] ?? '') === (string) $v->id)>
                                            {{ $v->tipoVehiculo->nombre ?? 'Vehículo' }} · {{ $v->placa }} · {{ $v->modelo }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('vehiculos.' . $indiceVehiculo . '.vehiculoId')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="min-w-0">
                                <div data-lista-servicios class="space-y-3">

                                    @foreach(($bloque['servicios'] ?? []) as $indiceServicio => $detalle)
                                        <div class="rounded-lg border border-slate-200 bg-white p-3"
                                             data-fila-servicio
                                             data-indice-servicio="{{ $indiceServicio }}">

                                            <div class="flex items-end gap-2">
                                                <div class="min-w-0 flex-1">
                                                    <label data-etiqueta-servicio class="mb-1.5 block text-xs font-semibold text-slate-600">
                                                        Servicio {{ $loop->iteration }}
                                                    </label>

                                                    <select name="vehiculos[{{ $indiceVehiculo }}][servicios][{{ $indiceServicio }}][servicioId]"
                                                            data-select-servicio
                                                            data-selected="{{ $detalle['servicioId'] ?? '' }}"
                                                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                                                        <option value="">Selecciona un servicio</option>
                                                    </select>

                                                    @error('vehiculos.' . $indiceVehiculo . '.servicios.' . $indiceServicio . '.servicioId')
                                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <button type="button"
                                                        data-quitar-servicio
                                                        title="Quitar servicio"
                                                        aria-label="Quitar servicio"
                                                        class="grid h-9 w-9 shrink-0 place-items-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                                    <span class="h-4 w-4">
                                                        @include('layouts.partials.icons', ['icon' => 'x'])
                                                    </span>
                                                </button>
                                            </div>

                                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">

                                                <label data-precio-acordado-opcion
                                                       class="hidden items-center gap-2 text-xs text-slate-600">
                                                    <input type="checkbox"
                                                           name="vehiculos[{{ $indiceVehiculo }}][servicios][{{ $indiceServicio }}][precioAcordadoActivo]"
                                                           value="1"
                                                           data-precio-acordado-toggle
                                                           class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-500"
                                                           @checked($detalle['precioAcordadoActivo'] ?? false)>
                                                    Precio a convenir
                                                </label>

                                                <div data-precio-acordado-wrap class="hidden w-full sm:max-w-56">
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                        Valor acordado
                                                    </label>

                                                    <input type="number"
                                                           min="0.01"
                                                           step="0.01"
                                                           name="vehiculos[{{ $indiceVehiculo }}][servicios][{{ $indiceServicio }}][precioAcordado]"
                                                           value="{{ $detalle['precioAcordado'] ?? '' }}"
                                                           data-precio-acordado
                                                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">

                                                    @error('vehiculos.' . $indiceVehiculo . '.servicios.' . $indiceServicio . '.precioAcordado')
                                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <p data-precio-servicio class="ml-auto text-right text-sm font-bold text-cyan-700"></p>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                                @error('vehiculos.' . $indiceVehiculo . '.servicios')
                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror

                                <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                                    <button type="button"
                                            data-agregar-servicio
                                            class="rounded-lg border border-cyan-200 bg-white px-3 py-2 text-xs font-semibold text-cyan-700 transition hover:border-cyan-400 hover:bg-cyan-50">
                                        + Agregar servicio
                                    </button>

                                    <p class="text-sm font-bold text-slate-700">
                                        Subtotal:
                                        <span data-subtotal class="text-cyan-700">$0</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                @endforeach

            </div>

            @error('vehiculos')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror

            <button type="button"
                    id="agregarVehiculo"
                    class="mt-4 rounded-lg border border-cyan-200 bg-white px-4 py-2 text-sm font-semibold text-cyan-700 transition hover:border-cyan-400 hover:bg-cyan-50">
                + Agregar otro vehículo
            </button>

            {{-- Total --}}
            <div class="mt-5 flex items-center justify-between rounded-xl border border-cyan-100 bg-cyan-50/70 px-5 py-4">
                <span class="text-sm font-semibold text-slate-700">Total de la cita</span>
                <span id="totalCita" class="text-xl font-bold text-cyan-800">$0</span>
            </div>
        </div>

        {{-- Estado y atención --}}
        <div class="border-t border-slate-100 px-6 py-5">

            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyan-50 text-cyan-600">
                    ⚙️
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Configuración de la cita</h2>
                    <p class="text-xs text-slate-400">Define el tipo de atención y el estado</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label for="tipoDeAtencion" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Tipo de atención
                    </label>

                    <select name="tipoDeAtencion" id="tipoDeAtencion"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('tipoDeAtencion') border-red-500 @enderror">

                        @foreach(['Por orden de llegada', 'Por cita agendada'] as $tipoAtencion)
                            <option value="{{ $tipoAtencion }}"
                                @selected(old('tipoDeAtencion', $agenda->tipoDeAtencion ?? '') === $tipoAtencion)>
                                {{ $tipoAtencion }}
                            </option>
                        @endforeach
                    </select>

                    @error('tipoDeAtencion')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="mb-1.5 block text-xs font-semibold text-slate-600">
                        Estado
                    </label>

                    <select name="estado" id="estado"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 @error('estado') border-red-500 @enderror">

                        @foreach(['pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'cancelada' => 'Cancelada'] as $valor => $etiqueta)
                            <option value="{{ $valor }}"
                                @selected(old('estado', $agenda->estado ?? 'pendiente') === $valor)>
                                {{ $etiqueta }}
                            </option>
                        @endforeach
                    </select>

                    @error('estado')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Botones --}}
        <div class="flex flex-col-reverse justify-end gap-2 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row">

            <a href="{{ route('agenda.index') }}"
               class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                Cancelar
            </a>

            <button type="submit"
                    class="rounded-lg bg-cyan-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-cyan-700">
                {{ $esEdicion ? 'Guardar cambios' : 'Guardar cita' }}
            </button>
        </div>

    </form>
</div>

<template id="plantillaVehiculo">
    <section class="rounded-xl border border-slate-200 bg-slate-50/60 p-4"
             data-bloque-vehiculo
             data-indice="__BLOQUE__">

        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-700">
                Vehículo <span data-numero-bloque></span>
            </h3>

            <button type="button"
                    data-quitar-vehiculo
                    title="Quitar vehículo"
                    aria-label="Quitar vehículo"
                    class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                <span class="h-4 w-4">
                    @include('layouts.partials.icons', ['icon' => 'x'])
                </span>
            </button>
        </div>

        <div class="grid grid-cols-1 items-start gap-4 md:grid-cols-2">
            <div class="min-w-0">
                <label class="mb-1.5 block text-xs font-semibold text-slate-600">Vehículo</label>

                <select name="vehiculos[__BLOQUE__][vehiculoId]"
                        data-select-vehiculo
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">

                    <option value="">Selecciona un vehículo</option>

                    @foreach($vehiculo as $v)
                        <option value="{{ $v->id }}"
                                data-cliente-id="{{ $v->clienteID }}"
                                data-tipo="{{ $v->tipoVehiculoId }}">
                            {{ $v->tipoVehiculo->nombre ?? 'Vehículo' }} · {{ $v->placa }} · {{ $v->modelo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-0">
                <div data-lista-servicios class="space-y-3">
                    <div class="rounded-lg border border-slate-200 bg-white p-3"
                         data-fila-servicio
                         data-indice-servicio="0">

                        <div class="flex items-end gap-2">
                            <div class="min-w-0 flex-1">
                                <label data-etiqueta-servicio class="mb-1.5 block text-xs font-semibold text-slate-600">
                                    Servicio 1
                                </label>

                                <select name="vehiculos[__BLOQUE__][servicios][0][servicioId]"
                                        data-select-servicio
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                                    <option value="">Selecciona un servicio</option>
                                </select>
                            </div>

                            <button type="button"
                                    data-quitar-servicio
                                    title="Quitar servicio"
                                    aria-label="Quitar servicio"
                                    class="grid h-9 w-9 shrink-0 place-items-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                <span class="h-4 w-4">
                                    @include('layouts.partials.icons', ['icon' => 'x'])
                                </span>
                            </button>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                            <label data-precio-acordado-opcion class="hidden items-center gap-2 text-xs text-slate-600">
                                <input type="checkbox"
                                       name="vehiculos[__BLOQUE__][servicios][0][precioAcordadoActivo]"
                                       value="1"
                                       data-precio-acordado-toggle
                                       class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-500">
                                Precio a convenir
                            </label>

                            <div data-precio-acordado-wrap class="hidden w-full sm:max-w-56">
                                <label class="mb-1 block text-xs font-semibold text-slate-600">Valor acordado</label>

                                <input type="number"
                                       min="0.01"
                                       step="0.01"
                                       name="vehiculos[__BLOQUE__][servicios][0][precioAcordado]"
                                       data-precio-acordado
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                            </div>

                            <p data-precio-servicio class="ml-auto text-right text-sm font-bold text-cyan-700"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                    <button type="button"
                            data-agregar-servicio
                            class="rounded-lg border border-cyan-200 bg-white px-3 py-2 text-xs font-semibold text-cyan-700 transition hover:border-cyan-400 hover:bg-cyan-50">
                        + Agregar servicio
                    </button>

                    <p class="text-sm font-bold text-slate-700">
                        Subtotal: <span data-subtotal class="text-cyan-700">$0</span>
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<template id="plantillaServicio">
    <div class="rounded-lg border border-slate-200 bg-white p-3"
         data-fila-servicio
         data-indice-servicio="__SERVICIO__">

        <div class="flex items-end gap-2">
            <div class="min-w-0 flex-1">
                <label data-etiqueta-servicio class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Servicio
                </label>

                <select name="vehiculos[__BLOQUE__][servicios][__SERVICIO__][servicioId]"
                        data-select-servicio
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
                    <option value="">Primero selecciona un vehículo</option>
                </select>
            </div>

            <button type="button"
                    data-quitar-servicio
                    title="Quitar servicio"
                    aria-label="Quitar servicio"
                    class="grid h-9 w-9 shrink-0 place-items-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                <span class="h-4 w-4">
                    @include('layouts.partials.icons', ['icon' => 'x'])
                </span>
            </button>
        </div>

        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
            <label data-precio-acordado-opcion class="hidden items-center gap-2 text-xs text-slate-600">
                <input type="checkbox"
                       name="vehiculos[__BLOQUE__][servicios][__SERVICIO__][precioAcordadoActivo]"
                       value="1"
                       data-precio-acordado-toggle
                       class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-500">
                Precio a convenir
            </label>

            <div data-precio-acordado-wrap class="hidden w-full sm:max-w-56">
                <label class="mb-1 block text-xs font-semibold text-slate-600">Valor acordado</label>

                <input type="number"
                       min="0.01"
                       step="0.01"
                       name="vehiculos[__BLOQUE__][servicios][__SERVICIO__][precioAcordado]"
                       data-precio-acordado
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100">
            </div>

            <p data-precio-servicio class="ml-auto text-right text-sm font-bold text-cyan-700"></p>
        </div>
    </div>
</template>

<script>
(() => {
    const cliente = document.getElementById('clienteId');
    const contenedor = document.getElementById('bloquesVehiculos');
    const plantillaVehiculo = document.getElementById('plantillaVehiculo');
    const plantillaServicio = document.getElementById('plantillaServicio');
    const catalogoServicios = @js($serviciosPrecios);
    const formatoPesos = new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    });
    const totalCita = document.getElementById('totalCita');

    function bloques() {
        return [...contenedor.querySelectorAll('[data-bloque-vehiculo]')];
    }

    function buscarPrecio(servicioId, tipoId) {
        const servicio = catalogoServicios.find(item => String(item.id) === String(servicioId));
        return servicio?.precios?.[tipoId] ?? null;
    }

    function calcularDuracionTotal() {
    let totalMinutos = 0;

    bloques().forEach(bloque => {
        const tipoId = bloque.querySelector('[data-select-vehiculo]')
            .selectedOptions[0]?.dataset.tipo;

        if (!tipoId) return;

        bloque.querySelectorAll('[data-select-servicio]').forEach(select => {
            if (!select.value) return;

            const configuracion = buscarPrecio(select.value, tipoId);

            if (configuracion) {
                totalMinutos += Number(configuracion.duracionMinutos || 0);
            }
        });
    });

    return Math.max(30, totalMinutos);
}

    function actualizarPrecio(fila, tipoId) {
        const select = fila.querySelector('[data-select-servicio]');
        const config = buscarPrecio(select.value, tipoId);
        const contenedorPrecio = fila.querySelector('[data-precio-acordado-wrap]');
        const entradaPrecio = fila.querySelector('[data-precio-acordado]');
        const opcionPrecioAcordado = fila.querySelector('[data-precio-acordado-opcion]');
        const precioAcordadoActivo = fila.querySelector('[data-precio-acordado-toggle]');
        const textoPrecio = fila.querySelector('[data-precio-servicio]');

        if (!config) {
            contenedorPrecio.classList.add('hidden');
            entradaPrecio.disabled = true;
            opcionPrecioAcordado.classList.add('hidden');
            textoPrecio.textContent = '';
            return;
        }

        if (config.modalidad === 'convenir') {
            opcionPrecioAcordado.classList.add('hidden');
            contenedorPrecio.classList.remove('hidden');
            entradaPrecio.disabled = false;
            textoPrecio.textContent = 'El precio se acuerda con el cliente.';
        } else {
            opcionPrecioAcordado.classList.remove('hidden');
            contenedorPrecio.classList.toggle('hidden', !precioAcordadoActivo.checked);
            entradaPrecio.disabled = !precioAcordadoActivo.checked;
            textoPrecio.textContent = precioAcordadoActivo.checked
                ? 'Se usará el valor acordado para esta cita.'
                : config.modalidad === 'desde'
                ? `Desde ${formatoPesos.format(Number(config.precio))}`
                : formatoPesos.format(Number(config.precio));
        }
    }

    function actualizarServicios(bloque) {
        const tipoId = bloque.querySelector('[data-select-vehiculo]').selectedOptions[0]?.dataset.tipo;

        bloque.querySelectorAll('[data-fila-servicio]').forEach(fila => {
            const select = fila.querySelector('[data-select-servicio]');
            const seleccionado = select.value || select.dataset.selected || '';

            select.replaceChildren(new Option(
                tipoId ? 'Seleccione un servicio' : 'Primero selecciona un vehículo',
                ''
            ));

            if (tipoId) {
                catalogoServicios.forEach(servicio => {
                    if (buscarPrecio(servicio.id, tipoId)) {
                        select.add(new Option(servicio.nombre, servicio.id));
                    }
                });
            }

            if ([...select.options].some(opcion => opcion.value === seleccionado)) {
                select.value = seleccionado;
            }

            select.dataset.selected = '';
            actualizarPrecio(fila, tipoId);
        });

        actualizarOpcionesServicios(bloque);
        recalcularTotales();
    }

    function actualizarOpcionesServicios(bloque) {
        const filas = [...bloque.querySelectorAll('[data-fila-servicio]')];

        filas.forEach(fila => {
            const select = fila.querySelector('[data-select-servicio]');
            const seleccionadosEnOtrasFilas = new Set(
                filas
                    .filter(otraFila => otraFila !== fila)
                    .map(otraFila => otraFila.querySelector('[data-select-servicio]').value)
                    .filter(Boolean)
            );

            [...select.options].forEach(opcion => {
                if (!opcion.value) return;
                opcion.disabled = seleccionadosEnOtrasFilas.has(opcion.value);
            });
        });
    }

    function filtrarVehiculos() {
        const bloquesActuales = bloques();
        const clienteId = cliente.value;

        bloquesActuales.forEach(bloque => {
            const select = bloque.querySelector('[data-select-vehiculo]');
            const opcionActual = select.selectedOptions[0];

            if (opcionActual?.value && opcionActual.dataset.clienteId !== clienteId) {
                select.value = '';

                bloque.querySelectorAll('[data-select-servicio]').forEach(servicio => {
                    servicio.value = '';
                    servicio.dataset.selected = '';
                });

                bloque.querySelectorAll('[data-precio-acordado-toggle]').forEach(toggle => {
                    toggle.checked = false;
                });

                bloque.querySelectorAll('[data-precio-acordado]').forEach(precio => {
                    precio.value = '';
                });
            }
        });

        bloquesActuales.forEach(bloque => {
            const select = bloque.querySelector('[data-select-vehiculo]');
            const seleccionadoEnOtroBloque = new Set(
                bloquesActuales
                    .filter(otro => otro !== bloque)
                    .map(otro => otro.querySelector('[data-select-vehiculo]').value)
                    .filter(Boolean)
            );

            [...select.options].forEach(opcion => {
                if (!opcion.value) return;

                const esDelCliente = Boolean(clienteId) && opcion.dataset.clienteId === clienteId;
                const yaUsado = seleccionadoEnOtroBloque.has(opcion.value);

                opcion.hidden = !esDelCliente || yaUsado;
                opcion.disabled = !esDelCliente || yaUsado;
            });

            actualizarServicios(bloque);
        });
    }

    function recalcularTotales() {
        let totalGeneral = 0;

        bloques().forEach((bloque, indice) => {
            bloque.querySelector('[data-numero-bloque]').textContent = indice + 1;

            const tipoId = bloque.querySelector('[data-select-vehiculo]').selectedOptions[0]?.dataset.tipo;
            let subtotal = 0;

            bloque.querySelectorAll('[data-fila-servicio]').forEach((fila, indiceServicio) => {
                fila.querySelector('[data-etiqueta-servicio]').textContent = `Servicio ${indiceServicio + 1}`;

                const select = fila.querySelector('[data-select-servicio]');
                const config = buscarPrecio(select.value, tipoId);
                const entradaPrecio = fila.querySelector('[data-precio-acordado]');

                if (!config) return;

                subtotal += config.modalidad === 'convenir' || fila.querySelector('[data-precio-acordado-toggle]').checked
                    ? Number(entradaPrecio.value || 0)
                    : Number(config.precio || 0);
            });

            bloque.querySelector('[data-subtotal]').textContent = formatoPesos.format(subtotal);
            totalGeneral += subtotal;
        });

        totalCita.textContent = formatoPesos.format(totalGeneral);
    }

    function agregarServicio(bloque) {
        const indices = [...bloque.querySelectorAll('[data-fila-servicio]')]
            .map(fila => Number(fila.dataset.indiceServicio));

        const siguiente = Math.max(-1, ...indices) + 1;
        const indiceBloque = bloque.dataset.indice;
        const fila = plantillaServicio.content.firstElementChild.cloneNode(true);

        fila.innerHTML = fila.innerHTML
            .replaceAll('__BLOQUE__', indiceBloque)
            .replaceAll('__SERVICIO__', String(siguiente));

        fila.querySelector('[data-etiqueta-servicio]').textContent =
            `Servicio ${bloque.querySelectorAll('[data-fila-servicio]').length + 1}`;

        bloque.querySelector('[data-lista-servicios]').appendChild(fila);
        actualizarServicios(bloque);
    }

    function agregarVehiculo() {
        const indices = bloques().map(bloque => Number(bloque.dataset.indice));
        const siguiente = Math.max(-1, ...indices) + 1;
        const bloque = plantillaVehiculo.content.firstElementChild.cloneNode(true);

        bloque.innerHTML = bloque.innerHTML.replaceAll('__BLOQUE__', String(siguiente));

        contenedor.appendChild(bloque);
        filtrarVehiculos();
    }

    contenedor.addEventListener('change', event => {
        if (event.target.matches('[data-select-vehiculo]')) {
            const bloqueActual = event.target.closest('[data-bloque-vehiculo]');
            const seleccionado = event.target.value;

            const duplicado = bloques().some(bloque =>
                bloque.querySelector('[data-select-vehiculo]') !== event.target
                && bloque.querySelector('[data-select-vehiculo]').value === seleccionado
                && seleccionado
            );

            if (duplicado) {
                event.target.value = '';
                window.alert('Ese vehículo ya está en otro bloque de la cita.');
            } else {
                bloqueActual.querySelectorAll('[data-fila-servicio]').forEach(fila => {
                    fila.querySelector('[data-select-servicio]').value = '';
                    fila.querySelector('[data-precio-acordado-toggle]').checked = false;
                    fila.querySelector('[data-precio-acordado]').value = '';
                });
            }

            filtrarVehiculos();
        }

        if (event.target.matches('[data-select-servicio]')) {
            const filaActual = event.target.closest('[data-fila-servicio]');
            const bloque = event.target.closest('[data-bloque-vehiculo]');

            filaActual.querySelector('[data-precio-acordado-toggle]').checked = false;
            filaActual.querySelector('[data-precio-acordado]').value = '';

            const servicioRepetido = [...bloque.querySelectorAll('[data-select-servicio]')]
                .some(select =>
                    select !== event.target
                    && select.value === event.target.value
                    && event.target.value
                );

            if (servicioRepetido) {
                event.target.value = '';
                window.alert('Ese servicio ya está agregado para este vehículo.');
            }

            actualizarPrecio(
                filaActual,
                bloque.querySelector('[data-select-vehiculo]').selectedOptions[0]?.dataset.tipo
            );

            actualizarOpcionesServicios(bloque);
            recalcularTotales();
        }

        if (event.target.matches('[data-precio-acordado-toggle]')) {
            const fila = event.target.closest('[data-fila-servicio]');
            const bloque = event.target.closest('[data-bloque-vehiculo]');
            const tipoId = bloque.querySelector('[data-select-vehiculo]').selectedOptions[0]?.dataset.tipo;
            const entradaPrecio = fila.querySelector('[data-precio-acordado]');

            if (!event.target.checked) entradaPrecio.value = '';

            actualizarPrecio(fila, tipoId);
            recalcularTotales();
        }
    });

    contenedor.addEventListener('input', event => {
        if (event.target.matches('[data-precio-acordado]')) {
            recalcularTotales();
        }
    });

    contenedor.addEventListener('click', event => {
        const agregar = event.target.closest('[data-agregar-servicio]');

        if (agregar) {
            agregarServicio(agregar.closest('[data-bloque-vehiculo]'));
            return;
        }

        const quitarServicio = event.target.closest('[data-quitar-servicio]');

        if (quitarServicio) {
            const bloque = quitarServicio.closest('[data-bloque-vehiculo]');
            quitarServicio.closest('[data-fila-servicio]').remove();

            actualizarOpcionesServicios(bloque);
            recalcularTotales();
            return;
        }

        const quitarVehiculo = event.target.closest('[data-quitar-vehiculo]');

        if (quitarVehiculo) {
            quitarVehiculo.closest('[data-bloque-vehiculo]').remove();
            filtrarVehiculos();
        }
    });

    document.getElementById('agregarVehiculo').addEventListener('click', agregarVehiculo);
    cliente.addEventListener('change', filtrarVehiculos);

    filtrarVehiculos();

    const fecha = document.getElementById('fecha');
    const hora = document.getElementById('hora');
    const empleado = document.getElementById('empleadoId');
    const mensajeEmpleados = document.getElementById('mensajeEmpleados');

    let temporizadorDisponibilidad;
    let consultaDisponibilidad;

    async function cargarEmpleadosDisponibles() {
        consultaDisponibilidad?.abort();
        consultaDisponibilidad = new AbortController();

        const seleccionado = empleado.value || empleado.dataset.selected || '';

        empleado.replaceChildren(new Option('Sin asignar', ''));

        if (!fecha.value || !hora.value) {
            mensajeEmpleados.textContent = 'Elige fecha y hora para consultar disponibilidad.';
            return;
        }

        mensajeEmpleados.textContent = 'Buscando empleados disponibles...';

        const parametros = new URLSearchParams({
            fecha: fecha.value,
            hora: hora.value,
            duracionMinutos: calcularDuracionTotal()
        });

        if (empleado.dataset.agendaId) {
            parametros.set('agendaId', empleado.dataset.agendaId);
        }

        try {
            const respuesta = await fetch(`${empleado.dataset.url}?${parametros}`, {
                headers: { Accept: 'application/json' },
                signal: consultaDisponibilidad.signal,
            });

            if (!respuesta.ok) {
                throw new Error('No fue posible consultar la disponibilidad.');
            }

            const personas = await respuesta.json();

            personas.forEach(persona => {
                const opcion = new Option(
                    `${persona.nombre} (${persona.jornada})`,
                    persona.id
                );

                opcion.selected = String(persona.id) === String(seleccionado);
                empleado.add(opcion);
            });

            mensajeEmpleados.textContent = personas.length
                ? 'Solo aparecen empleados con turno y sin otra cita en ese horario.'
                : 'No hay empleados disponibles para esa fecha y hora.';

            empleado.dataset.selected = '';

        } catch (error) {
            if (error.name !== 'AbortError') {
                mensajeEmpleados.textContent = error.message;
            }
        }
    }

    function programarConsultaDisponibilidad() {
        window.clearTimeout(temporizadorDisponibilidad);
        temporizadorDisponibilidad = window.setTimeout(cargarEmpleadosDisponibles, 250);
    }

    fecha.addEventListener('change', programarConsultaDisponibilidad);
    hora.addEventListener('change', programarConsultaDisponibilidad);

    // Actualizar disponibilidad cuando cambien vehículos o servicios.
    contenedor.addEventListener('change', programarConsultaDisponibilidad);
    contenedor.addEventListener('click', programarConsultaDisponibilidad);

    cargarEmpleadosDisponibles();
})();
</script>
