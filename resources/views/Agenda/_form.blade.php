@php
    $esEdicion = isset($agenda);
    $bloquesIniciales = old('vehiculos');

    if ($bloquesIniciales === null && $esEdicion) {
        $bloquesIniciales = $agenda->agendaServicios
            ->groupBy('vehiculoId')
            ->map(fn ($lineas) => [
                'vehiculoId' => $lineas->first()->vehiculoId,
                'servicios' => $lineas->map(fn ($linea) => [
                    'servicioId' => $linea->servicioId,
                    'precioAcordado' => $linea->Precio,
                    'precioAcordadoActivo' => (function () use ($linea, $serviciosPrecios) {
                        $config = collect($serviciosPrecios)->firstWhere('id', $linea->servicioId)['precios'][$linea->vehiculo->tipoVehiculoId] ?? null;
                        return ($config['modalidad'] ?? null) !== 'convenir'
                            && isset($config['precio'])
                            && (float) $linea->Precio !== (float) $config['precio'];
                    })(),
                ])->values()->all(),
            ])
            ->values()
            ->all();
    }

    $bloquesIniciales = $bloquesIniciales ?: [[
        'vehiculoId' => '',
        'servicios' => [['servicioId' => '', 'precioAcordado' => '']],
    ]];
@endphp

<x-card>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-cyan-700">
            {{ $esEdicion ? '✏️ Editar Cita' : '📅 Agendar Nueva Cita' }}
        </h1>
        <a href="{{ route('agenda.index') }}" class="text-sm font-medium text-cyan-600 hover:text-cyan-800">← Volver al listado</a>
    </div>

    <form action="{{ $esEdicion ? route('agenda.update', $agenda->id) : route('agenda.store') }}" method="POST" novalidate id="formularioAgenda">
        @csrf
        @if($esEdicion)
            @method('PUT')
        @endif

        <div class="mb-4">
            <label for="clienteId" class="mb-1 block font-semibold text-gray-700">Cliente</label>
            <select name="clienteId" id="clienteId" class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('clienteId') border-red-500 @enderror">
                <option value="">Seleccione un cliente</option>
                @foreach($cliente as $c)
                    <option value="{{ $c->id }}" @selected((string) old('clienteId', $agenda->clienteId ?? '') === (string) $c->id)>
                        {{ $c->nombreCliente }} {{ $c->apellidoCliente }}
                    </option>
                @endforeach
            </select>
            @error('clienteId') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="fecha" class="mb-1 block font-semibold text-gray-700">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $agenda->fecha ?? '') }}"
                    class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('fecha') border-red-500 @enderror">
                @error('fecha') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="hora" class="mb-1 block font-semibold text-gray-700">Hora</label>
                <input type="time" name="hora" id="hora" value="{{ old('hora', isset($agenda) ? substr($agenda->hora, 0, 5) : '') }}"
                    class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('hora') border-red-500 @enderror">
                @error('hora') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="empleadoId" class="mb-1 block font-semibold text-gray-700">Empleado <span class="font-normal text-gray-400">(opcional)</span></label>
            <select name="empleadoId" id="empleadoId" data-url="{{ route('agenda.empleadosDisponibles') }}"
                data-agenda-id="{{ $agenda->id ?? '' }}" data-selected="{{ old('empleadoId', $agenda->empleadoId ?? '') }}"
                class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('empleadoId') border-red-500 @enderror">
                <option value="">Sin asignar</option>
                @foreach($empleado as $e)
                    <option value="{{ $e->id }}">{{ $e->nombre }} {{ $e->apellido }}</option>
                @endforeach
            </select>
            @error('empleadoId') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            <p id="mensajeEmpleados" class="mt-1 text-sm text-gray-500">Elige fecha y hora para consultar disponibilidad.</p>
        </div>

        <div class="mb-3">
            <h2 class="text-lg font-semibold text-slate-800">Vehículos y servicios</h2>
        </div>

        <div id="bloquesVehiculos" class="space-y-4">
            @foreach($bloquesIniciales as $indiceVehiculo => $bloque)
                <section class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm" data-bloque-vehiculo data-indice="{{ $indiceVehiculo }}">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700">Vehículo <span data-numero-bloque>{{ $loop->iteration }}</span></h3>
                        <button type="button" data-quitar-vehiculo title="Quitar vehículo" aria-label="Quitar vehículo" class="grid h-8 w-8 place-items-center rounded-full text-slate-500 hover:bg-red-50 hover:text-red-600">
                            <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'x'])</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 items-start gap-3 md:grid-cols-2">
                        <div class="min-w-0">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Vehículo</label>
                            <select name="vehiculos[{{ $indiceVehiculo }}][vehiculoId]" data-select-vehiculo
                                class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                                <option value="">Selecciona un vehículo</option>
                                @foreach($vehiculo as $v)
                                    <option value="{{ $v->id }}" data-cliente-id="{{ $v->clienteID }}" data-tipo="{{ $v->tipoVehiculoId }}"
                                        @selected((string) ($bloque['vehiculoId'] ?? '') === (string) $v->id)>
                                        {{ $v->tipoVehiculo->nombre ?? 'Vehículo' }} · {{ $v->placa }} · {{ $v->modelo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehiculos.' . $indiceVehiculo . '.vehiculoId') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="min-w-0">
                            <div data-lista-servicios class="space-y-2">
                                @foreach(($bloque['servicios'] ?? []) as $indiceServicio => $detalle)
                                    <div class="rounded-lg bg-slate-50 p-2" data-fila-servicio data-indice-servicio="{{ $indiceServicio }}">
                                        <div class="flex items-end gap-2">
                                            <div class="min-w-0 flex-1">
                                                <label data-etiqueta-servicio class="mb-1 block text-xs font-medium text-gray-600">Servicio {{ $loop->iteration }}</label>
                                                <select name="vehiculos[{{ $indiceVehiculo }}][servicios][{{ $indiceServicio }}][servicioId]"
                                                    data-select-servicio data-selected="{{ $detalle['servicioId'] ?? '' }}"
                                                    class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                                                    <option value="">Selecciona un servicio</option>
                                                </select>
                                                @error('vehiculos.' . $indiceVehiculo . '.servicios.' . $indiceServicio . '.servicioId') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <button type="button" data-quitar-servicio title="Quitar servicio" aria-label="Quitar servicio" class="grid h-9 w-9 shrink-0 place-items-center rounded-full text-slate-500 hover:bg-red-50 hover:text-red-600">
                                                <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'x'])</span>
                                            </button>
                                        </div>
                                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                                            <label data-precio-acordado-opcion class="hidden items-center gap-2 text-xs text-slate-600">
                                                <input type="checkbox" name="vehiculos[{{ $indiceVehiculo }}][servicios][{{ $indiceServicio }}][precioAcordadoActivo]" value="1" data-precio-acordado-toggle class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-600" @checked($detalle['precioAcordadoActivo'] ?? false)>
                                                Precio a convenir
                                            </label>
                                            <div data-precio-acordado-wrap class="hidden w-full sm:max-w-56">
                                                <label class="mb-1 block text-xs font-medium text-slate-600">Valor acordado</label>
                                                <input type="number" min="0.01" step="0.01"
                                                    name="vehiculos[{{ $indiceVehiculo }}][servicios][{{ $indiceServicio }}][precioAcordado]"
                                                    value="{{ $detalle['precioAcordado'] ?? '' }}" data-precio-acordado
                                                    class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                                                @error('vehiculos.' . $indiceVehiculo . '.servicios.' . $indiceServicio . '.precioAcordado') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <p data-precio-servicio class="ml-auto text-right text-sm font-semibold text-cyan-800"></p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('vehiculos.' . $indiceVehiculo . '.servicios') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                            <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                                <button type="button" data-agregar-servicio class="rounded-lg border border-cyan-600 px-3 py-2 text-sm font-semibold text-cyan-700 hover:bg-cyan-50">+ Servicio</button>
                                <p class="text-sm font-semibold text-slate-800">Subtotal: <span data-subtotal>$0</span></p>
                            </div>
                        </div>
                    </div>
                </section>
            @endforeach
        </div>

        @error('vehiculos') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        <button type="button" id="agregarVehiculo" class="mt-4 rounded-lg border border-cyan-600 px-4 py-2 font-semibold text-cyan-700 hover:bg-cyan-50">+ Agregar otro vehículo</button>

        <div class="mt-5 rounded-lg bg-cyan-50 p-4 text-right">
            <span class="mr-2 font-semibold text-cyan-900">Total de la cita:</span>
            <span id="totalCita" class="text-xl font-bold text-cyan-900">$0</span>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="tipoDeAtencion" class="mb-1 block font-semibold text-gray-700">Tipo de atención</label>
                <select name="tipoDeAtencion" id="tipoDeAtencion" class="w-full rounded-lg border px-3 py-2 @error('tipoDeAtencion') border-red-500 @enderror">
                    @foreach(['Por orden de llegada', 'Por cita agendada'] as $tipoAtencion)
                        <option value="{{ $tipoAtencion }}" @selected(old('tipoDeAtencion', $agenda->tipoDeAtencion ?? '') === $tipoAtencion)>{{ $tipoAtencion }}</option>
                    @endforeach
                </select>
                @error('tipoDeAtencion') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="estado" class="mb-1 block font-semibold text-gray-700">Estado</label>
                <select name="estado" id="estado" class="w-full rounded-lg border px-3 py-2 @error('estado') border-red-500 @enderror">
                    @foreach(['pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'cancelada' => 'Cancelada', 'terminada' => 'Terminada'] as $valor => $etiqueta)
                        <option value="{{ $valor }}" @selected(old('estado', $agenda->estado ?? 'pendiente') === $valor)>{{ $etiqueta }}</option>
                    @endforeach
                </select>
                @error('estado') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('agenda.index') }}" class="rounded-lg bg-gray-500 px-4 py-2 font-semibold text-white shadow hover:bg-gray-700">Cancelar</a>
            <button type="submit" class="rounded-lg bg-cyan-600 px-4 py-2 font-semibold text-white shadow hover:bg-cyan-800">
                {{ $esEdicion ? '💾 Guardar cambios' : '💾 Guardar Cita' }}
            </button>
        </div>
    </form>
</x-card>

<template id="plantillaVehiculo">
    <section class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm" data-bloque-vehiculo data-indice="__BLOQUE__">
        <div class="mb-2 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-700">Vehículo <span data-numero-bloque></span></h3>
            <button type="button" data-quitar-vehiculo title="Quitar vehículo" aria-label="Quitar vehículo" class="grid h-8 w-8 place-items-center rounded-full text-slate-500 hover:bg-red-50 hover:text-red-600">
                <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'x'])</span>
            </button>
        </div>
        <div class="grid grid-cols-1 items-start gap-3 md:grid-cols-2">
            <div class="min-w-0">
                <label class="mb-1 block text-sm font-medium text-gray-700">Vehículo</label>
                <select name="vehiculos[__BLOQUE__][vehiculoId]" data-select-vehiculo class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecciona un vehículo</option>
                    @foreach($vehiculo as $v)
                        <option value="{{ $v->id }}" data-cliente-id="{{ $v->clienteID }}" data-tipo="{{ $v->tipoVehiculoId }}">
                            {{ $v->tipoVehiculo->nombre ?? 'Vehículo' }} · {{ $v->placa }} · {{ $v->modelo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-0">
                <div data-lista-servicios class="space-y-2">
                    <div class="rounded-lg bg-slate-50 p-2" data-fila-servicio data-indice-servicio="0">
                        <div class="flex items-end gap-2">
                            <div class="min-w-0 flex-1">
                                <label data-etiqueta-servicio class="mb-1 block text-xs font-medium text-gray-600">Servicio 1</label>
                                <select name="vehiculos[__BLOQUE__][servicios][0][servicioId]" data-select-servicio class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                                    <option value="">Selecciona un servicio</option>
                                </select>
                            </div>
                            <button type="button" data-quitar-servicio title="Quitar servicio" aria-label="Quitar servicio" class="grid h-9 w-9 shrink-0 place-items-center rounded-full text-slate-500 hover:bg-red-50 hover:text-red-600">
                                <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'x'])</span>
                            </button>
                        </div>
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                            <label data-precio-acordado-opcion class="hidden items-center gap-2 text-xs text-slate-600">
                                <input type="checkbox" name="vehiculos[__BLOQUE__][servicios][0][precioAcordadoActivo]" value="1" data-precio-acordado-toggle class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">
                                Precio a convenir
                            </label>
                            <div data-precio-acordado-wrap class="hidden w-full sm:max-w-56">
                                <label class="mb-1 block text-xs font-medium text-slate-600">Valor acordado</label>
                                <input type="number" min="0.01" step="0.01" name="vehiculos[__BLOQUE__][servicios][0][precioAcordado]" data-precio-acordado class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                            </div>
                            <p data-precio-servicio class="ml-auto text-right text-sm font-semibold text-cyan-800"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                    <button type="button" data-agregar-servicio class="rounded-lg border border-cyan-600 px-3 py-2 text-sm font-semibold text-cyan-700 hover:bg-cyan-50">+ Servicio</button>
                    <p class="text-sm font-semibold text-slate-800">Subtotal: <span data-subtotal>$0</span></p>
                </div>
            </div>
        </div>
    </section>
</template>

<template id="plantillaServicio">
    <div class="rounded-lg bg-slate-50 p-2" data-fila-servicio data-indice-servicio="__SERVICIO__">
        <div class="flex items-end gap-2">
            <div class="min-w-0 flex-1">
                <label data-etiqueta-servicio class="mb-1 block text-xs font-medium text-gray-600">Servicio</label>
                <select name="vehiculos[__BLOQUE__][servicios][__SERVICIO__][servicioId]" data-select-servicio class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Primero selecciona un vehículo</option>
                </select>
            </div>
            <button type="button" data-quitar-servicio title="Quitar servicio" aria-label="Quitar servicio" class="grid h-9 w-9 place-items-center rounded-full text-slate-500 hover:bg-red-50 hover:text-red-600">
                <span class="h-4 w-4">@include('layouts.partials.icons', ['icon' => 'x'])</span>
            </button>
        </div>
        <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
            <label data-precio-acordado-opcion class="hidden items-center gap-2 text-xs text-slate-600">
                <input type="checkbox" name="vehiculos[__BLOQUE__][servicios][__SERVICIO__][precioAcordadoActivo]" value="1" data-precio-acordado-toggle class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">
                Precio a convenir
            </label>
            <div data-precio-acordado-wrap class="hidden w-full sm:max-w-56">
                <label class="mb-1 block text-xs font-medium text-slate-600">Valor acordado</label>
                <input type="number" min="0.01" step="0.01" name="vehiculos[__BLOQUE__][servicios][__SERVICIO__][precioAcordado]" data-precio-acordado class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <p data-precio-servicio class="ml-auto text-right text-sm font-semibold text-cyan-800"></p>
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
    const formatoPesos = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 });
    const totalCita = document.getElementById('totalCita');

    function bloques() {
        return [...contenedor.querySelectorAll('[data-bloque-vehiculo]')];
    }

    function buscarPrecio(servicioId, tipoId) {
        const servicio = catalogoServicios.find(item => String(item.id) === String(servicioId));
        return servicio?.precios?.[tipoId] ?? null;
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
            select.replaceChildren(new Option(tipoId ? 'Seleccione un servicio' : 'Primero selecciona un vehículo', ''));

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
            const seleccionadosEnOtrasFilas = new Set(filas
                .filter(otraFila => otraFila !== fila)
                .map(otraFila => otraFila.querySelector('[data-select-servicio]').value)
                .filter(Boolean));

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
                bloque.querySelectorAll('[data-precio-acordado-toggle]').forEach(toggle => toggle.checked = false);
                bloque.querySelectorAll('[data-precio-acordado]').forEach(precio => precio.value = '');
            }
        });

        bloquesActuales.forEach(bloque => {
            const select = bloque.querySelector('[data-select-vehiculo]');
            const seleccionadoEnOtroBloque = new Set(bloquesActuales
                .filter(otro => otro !== bloque)
                .map(otro => otro.querySelector('[data-select-vehiculo]').value)
                .filter(Boolean));

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
        fila.querySelector('[data-etiqueta-servicio]').textContent = `Servicio ${bloque.querySelectorAll('[data-fila-servicio]').length + 1}`;
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
            const duplicado = bloques().some(bloque => bloque.querySelector('[data-select-vehiculo]') !== event.target
                && bloque.querySelector('[data-select-vehiculo]').value === seleccionado && seleccionado);

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
                .some(select => select !== event.target && select.value === event.target.value && event.target.value);

            if (servicioRepetido) {
                event.target.value = '';
                window.alert('Ese servicio ya está agregado para este vehículo.');
            }

            actualizarPrecio(filaActual, bloque.querySelector('[data-select-vehiculo]').selectedOptions[0]?.dataset.tipo);
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
        if (event.target.matches('[data-precio-acordado]')) recalcularTotales();
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
        const parametros = new URLSearchParams({ fecha: fecha.value, hora: hora.value });
        if (empleado.dataset.agendaId) parametros.set('agendaId', empleado.dataset.agendaId);

        try {
            const respuesta = await fetch(`${empleado.dataset.url}?${parametros}`, {
                headers: { Accept: 'application/json' },
                signal: consultaDisponibilidad.signal,
            });
            if (!respuesta.ok) throw new Error('No fue posible consultar la disponibilidad.');
            const personas = await respuesta.json();
            personas.forEach(persona => {
                const opcion = new Option(`${persona.nombre} (${persona.jornada})`, persona.id);
                opcion.selected = String(persona.id) === String(seleccionado);
                empleado.add(opcion);
            });
            mensajeEmpleados.textContent = personas.length
                ? 'Solo aparecen empleados con turno y sin otra cita en ese horario.'
                : 'No hay empleados disponibles para esa fecha y hora.';
            empleado.dataset.selected = '';
        } catch (error) {
            if (error.name !== 'AbortError') mensajeEmpleados.textContent = error.message;
        }
    }

    function programarConsultaDisponibilidad() {
        window.clearTimeout(temporizadorDisponibilidad);
        temporizadorDisponibilidad = window.setTimeout(cargarEmpleadosDisponibles, 250);
    }

    fecha.addEventListener('change', programarConsultaDisponibilidad);
    hora.addEventListener('change', programarConsultaDisponibilidad);
    cargarEmpleadosDisponibles();
})();
</script>
