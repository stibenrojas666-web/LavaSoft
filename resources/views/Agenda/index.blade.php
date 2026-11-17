
@extends('layouts.app')

@section('title', 'Agenda de citas')
@section('page-title', 'Agenda de citas')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-6">

    {{-- Encabezado --}}
    <section class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-cyan-950 to-cyan-800 p-6 text-white shadow-lg sm:p-8">
        <div class="pointer-events-none absolute -right-12 -top-20 -z-10 h-64 w-64 rounded-full border-[35px] border-white/5"></div>
        <div class="pointer-events-none absolute -bottom-28 right-40 -z-10 h-64 w-64 rounded-full bg-cyan-400/10 blur-2xl"></div>

        <div class="relative flex flex-wrap items-center justify-between gap-5">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold text-cyan-100">
                    <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                    Organización y programación
                </span>

                <h1 class="mt-5 text-2xl font-extrabold tracking-tight sm:text-3xl">
                    Agenda de citas
                </h1>

                <p class="mt-2 max-w-xl text-sm leading-6 text-cyan-100">
                    Consulta, organiza y administra las citas de tus clientes desde un solo lugar.
                </p>
            </div>

            <div class="min-w-36 rounded-2xl border border-white/20 bg-white/10 px-6 py-4 text-center backdrop-blur">
                <p class="text-xs font-semibold text-cyan-100">Citas encontradas</p>
                <p class="mt-1 text-3xl font-extrabold tabular-nums">
                    {{ $vista === 'calendario' ? $citasCalendario->count() : $agenda->total() }}
                </p>
            </div>
        </div>
    </section>

    {{-- Búsqueda y filtros --}}
    <section class="overflow-visible rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-cyan-50 text-cyan-800">
                @include('layouts.partials.icons', ['icon' => 'search'])
            </span>

            <div>
                <h2 class="text-sm font-bold text-slate-800">Buscar y filtrar citas</h2>
                <p class="mt-0.5 text-xs text-slate-500">
                    Encuentra citas por cliente, placa, empleado, servicio o estado.
                </p>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <x-list-search
                :action="route('agenda.index')"
                :value="$buscar"
                placeholder="Cliente, placa, empleado, servicio o estado"
                :hidden="['mes' => $mes, 'vista' => $vista, 'fecha' => $fechaCalendario]"
                :filters="[
                    ['name' => 'estado', 'label' => 'Estado de la cita', 'options' => ['' => 'Todos', 'pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'terminada' => 'Terminada', 'cancelada' => 'Cancelada'], 'value' => $filtros['estado'] ?? ''],
                    ['name' => 'desde', 'label' => 'Desde', 'type' => 'date', 'value' => $filtros['desde'] ?? ''],
                    ['name' => 'hasta', 'label' => 'Hasta', 'type' => 'date', 'value' => $filtros['hasta'] ?? ''],
                    ['name' => 'orden', 'label' => 'Ordenar citas', 'options' => ['' => 'Predeterminado', 'fecha_desc' => 'Fecha: recientes primero', 'fecha_asc' => 'Fecha: antiguas primero', 'hora_asc' => 'Hora: temprano primero', 'hora_desc' => 'Hora: tarde primero'], 'value' => $filtros['orden'] ?? ''],
                ]"
            />
        </div>
    </section>

    {{-- Selector de vista --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500">
            <span class="font-bold text-slate-800">
                {{ $vista === 'calendario' ? $citasCalendario->count() : $agenda->total() }}
            </span>
            citas encontradas
        </p>

        <div class="inline-flex rounded-xl border border-slate-200 bg-slate-100 p-1.5 shadow-sm">
            <a
                href="{{ route('agenda.index', array_merge(request()->query(), ['vista' => 'lista', 'mes' => $mes])) }}"
                class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-bold transition
                    {{ $vista === 'lista' ? 'bg-cyan-700 text-white shadow-sm' : 'text-slate-500 hover:bg-white hover:text-cyan-800' }}"
                @if($vista === 'lista') aria-current="page" @endif
            >
                Lista
            </a>

            <a
                href="{{ route('agenda.index', array_merge(request()->query(), ['vista' => 'calendario', 'mes' => $mes])) }}"
                class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-bold transition
                    {{ $vista === 'calendario' ? 'bg-cyan-700 text-white shadow-sm' : 'text-slate-500 hover:bg-white hover:text-cyan-800' }}"
                @if($vista === 'calendario') aria-current="page" @endif
            >
                Calendario
            </a>
        </div>
    </div>

    @if($vista === 'lista')

        {{-- Tabla --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Listado de citas</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Información y acciones de cada cita registrada.</p>
                </div>

                <span class="rounded-full bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-800">
                    {{ $agenda->total() }} registros
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="lavasoft-table min-w-[1100px] text-left text-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Empleado</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Total servicios</th>
                            <th>Atención</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($agenda as $a)
                            <tr class="transition hover:bg-cyan-50/40">
                                <td class="whitespace-nowrap font-semibold text-slate-400">
                                    #{{ $a->id }}
                                </td>

                                <td>
                                    <span class="font-bold text-slate-800">
                                        {{ $a->cliente->nombreCliente ?? 'N/A' }}
                                        {{ $a->cliente->apellidoCliente ?? '' }}
                                    </span>
                                </td>

                                <td>
                                    @forelse($a->vehiculosParaVista as $vehiculo)
                                        <span class="mb-1 block whitespace-nowrap text-xs font-semibold text-slate-700">
                                            {{ ucfirst($vehiculo->tipoVehiculo->nombre ?? 'Vehículo') }}
                                            <span class="text-slate-400">·</span>
                                            {{ $vehiculo->placa }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400">Sin vehículos</span>
                                    @endforelse
                                </td>

                                <td class="text-slate-600">
                                    {{ $a->empleado ? $a->empleado->nombre . ' ' . $a->empleado->apellido : 'Sin asignar' }}
                                </td>

                                <td class="whitespace-nowrap text-slate-600">
                                    {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                                </td>

                                <td class="whitespace-nowrap font-semibold tabular-nums text-slate-700">
                                    {{ substr($a->hora, 0, 5) }}
                                </td>

                                <td class="whitespace-nowrap font-extrabold tabular-nums text-slate-800">
                                    ${{ number_format($a->agenda_servicios_sum_precio ?? 0, 2) }}
                                </td>

                                <td>
                                    <span class="text-xs text-slate-600">
                                        {{ $a->tipoDeAtencion }}
                                    </span>
                                </td>

                                <td>
                                    <span class="inline-flex whitespace-nowrap rounded-full px-3 py-1.5 text-xs font-bold
                                        @if($a->estado === 'confirmada') bg-emerald-50 text-emerald-700
                                        @elseif($a->estado === 'cancelada') bg-rose-50 text-rose-700
                                        @elseif($a->estado === 'terminada') bg-sky-50 text-sky-700
                                        @else bg-amber-50 text-amber-700
                                        @endif">
                                        {{ ucfirst($a->estado) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <a
                                            href="{{ route('agenda.factura', array_merge(request()->query(), ['agendaId' => $a->id])) }}"
                                            class="grid h-9 w-9 place-items-center rounded-xl border border-cyan-100 bg-cyan-50 text-cyan-800 transition hover:border-cyan-300 hover:bg-cyan-100"
                                            title="Ver factura"
                                            aria-label="Ver factura"
                                        >
                                            <span class="h-4 w-4">
                                                @include('layouts.partials.icons', ['icon' => 'receipt'])
                                            </span>
                                        </a>

                                        <a
                                            href="{{ route('agenda.edit', array_merge(request()->query(), ['agenda' => $a->id])) }}"
                                            class="grid h-9 w-9 place-items-center rounded-xl border border-blue-100 bg-blue-50 text-blue-700 transition hover:border-blue-300 hover:bg-blue-100"
                                            title="Editar"
                                            aria-label="Editar"
                                        >
                                            <span class="h-4 w-4">
                                                @include('layouts.partials.icons', ['icon' => 'pencil'])
                                            </span>
                                        </a>

                                        <form
                                            action="{{ route('agenda.destroy', array_merge(request()->query(), ['agenda' => $a->id])) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('¿Desea eliminar esta cita?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="grid h-9 w-9 place-items-center rounded-xl border border-rose-100 bg-rose-50 text-rose-600 transition hover:border-rose-300 hover:bg-rose-100"
                                                title="Eliminar"
                                                aria-label="Eliminar"
                                            >
                                                <span class="h-4 w-4">
                                                    @include('layouts.partials.icons', ['icon' => 'trash'])
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="!py-14 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-cyan-50 text-cyan-700">
                                            <span class="h-6 w-6">
                                                @include('layouts.partials.icons', ['icon' => 'calendar'])
                                            </span>
                                        </span>

                                        <p class="mt-4 font-bold text-slate-700">
                                            {{ $buscar !== '' || collect($filtros)->contains(fn ($valor) => $valor !== '') ? 'No encontramos citas con esos criterios.' : 'No hay citas registradas.' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Las citas registradas aparecerán aquí.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @if($agenda->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                {{ $agenda->links() }}
            </div>
        @endif

    @else

        {{-- Calendario mensual --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-7">
                <a
                    href="{{ route('agenda.index', array_merge($parametrosCalendario, ['mes' => $mesAnterior, 'fecha' => $primerDia->copy()->subMonth()->startOfMonth()->toDateString()])) }}"
                    class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 text-xl text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700"
                    aria-label="Mes anterior"
                >
                    ‹
                </a>

                <div class="text-center">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Calendario mensual
                    </p>
                    <h2 class="mt-1 text-lg font-extrabold capitalize text-slate-800 sm:text-xl">
                        {{ $primerDia->copy()->locale('es')->translatedFormat('F Y') }}
                    </h2>
                </div>

                <a
                    href="{{ route('agenda.index', array_merge($parametrosCalendario, ['mes' => $mesSiguiente, 'fecha' => $primerDia->copy()->addMonth()->startOfMonth()->toDateString()])) }}"
                    class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 text-xl text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700"
                    aria-label="Mes siguiente"
                >
                    ›
                </a>
            </div>

            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center text-[10px] font-extrabold uppercase tracking-wider text-slate-500 sm:text-xs">
                @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                    <div class="px-1 py-3">{{ $dia }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7">
                @for($diaCalendario = $inicioCalendario->copy(); $diaCalendario->lte($finCalendario); $diaCalendario->addDay())
                    <div class="min-h-24 border-b border-r border-slate-100 p-1 sm:min-h-28 sm:p-2 {{ $diaCalendario->month !== $primerDia->month ? 'bg-slate-50/70' : 'bg-white' }}">
                        <div class="flex items-center justify-between">
                            <a
                                href="{{ route('agenda.index', array_merge(request()->query(), ['vista' => 'lista', 'mes' => $diaCalendario->format('Y-m'), 'desde' => $diaCalendario->format('Y-m-d'), 'hasta' => $diaCalendario->format('Y-m-d')])) }}"
                                class="inline-grid h-8 w-8 place-items-center rounded-full text-xs font-bold transition
                                    {{ $diaCalendario->format('Y-m-d') === $fechaCalendario ? 'bg-cyan-700 text-white shadow-md' : ($diaCalendario->month === $primerDia->month ? 'text-slate-700 hover:bg-cyan-50' : 'text-slate-400 hover:bg-slate-100') }}"
                            >
                                {{ $diaCalendario->day }}
                            </a>

                            @if($diaCalendario->gte(today()))
                                <a
                                    href="{{ route('agenda.create', ['fecha' => $diaCalendario->format('Y-m-d'), 'vista' => 'calendario', 'mes' => $diaCalendario->format('Y-m')]) }}"
                                    title="Agendar para {{ $diaCalendario->format('d/m/Y') }}"
                                    aria-label="Agendar cita para {{ $diaCalendario->format('d/m/Y') }}"
                                    class="grid h-7 w-7 place-items-center rounded-lg text-lg font-semibold leading-none text-cyan-700 transition hover:bg-cyan-50 hover:text-cyan-900"
                                >
                                    +
                                </a>
                            @endif
                        </div>

                        <div class="mt-1 space-y-1">
                            @foreach($citasPorDia->get($diaCalendario->format('Y-m-d'), collect())->take(3) as $citaCalendario)
                                <a
                                    href="{{ route('agenda.factura', ['agendaId' => $citaCalendario->id, 'vista' => 'calendario', 'mes' => $primerDia->format('Y-m'), 'fecha' => \Carbon\Carbon::parse($citaCalendario->fecha)->toDateString()]) }}"
                                    title="{{ substr($citaCalendario->hora, 0, 5) }} · {{ $citaCalendario->cliente->nombreCliente ?? 'Cliente' }}"
                                    class="block truncate rounded-lg px-1.5 py-1.5 text-[9px] font-semibold leading-tight shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:text-[10px]
                                        {{ $citaCalendario->estado === 'confirmada' ? 'bg-emerald-50 text-emerald-800' : ($citaCalendario->estado === 'cancelada' ? 'bg-rose-50 text-rose-700' : ($citaCalendario->estado === 'terminada' ? 'bg-sky-50 text-sky-800' : 'bg-amber-50 text-amber-800')) }}"
                                >
                                    <span class="font-extrabold">{{ substr($citaCalendario->hora, 0, 5) }}</span>
                                    {{ $citaCalendario->cliente->nombreCliente ?? 'Cliente' }}
                                </a>
                            @endforeach

                            @if($citasPorDia->get($diaCalendario->format('Y-m-d'), collect())->count() > 3)
                                <span class="block px-1 text-[9px] font-semibold text-slate-500 sm:text-[10px]">
                                    +{{ $citasPorDia->get($diaCalendario->format('Y-m-d'), collect())->count() - 3 }} más
                                </span>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>

            <div class="flex flex-wrap gap-x-5 gap-y-2 border-t border-slate-100 px-5 py-4 text-xs text-slate-600 sm:px-7">
                <span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-emerald-400"></i>Confirmada</span>
                <span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-amber-400"></i>Pendiente</span>
                <span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-sky-400"></i>Terminada</span>
                <span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-rose-400"></i>Cancelada</span>
            </div>
        </section>

        {{-- Citas del día seleccionado --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-7">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h3 class="font-extrabold text-slate-800">
                            Citas del {{ $fechaSeleccionada->locale('es')->translatedFormat('j \\d\\e F') }}
                        </h3>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $citasDiaSeleccionado->count() }} citas · Ordenadas por hora
                        </p>
                    </div>

                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-50 text-cyan-800">
                        @include('layouts.partials.icons', ['icon' => 'calendar'])
                    </span>
                </div>
            </div>

            @if($citasDiaSeleccionado->isEmpty())
                <div class="flex flex-col items-center px-5 py-12 text-center">
                    <span class="grid h-12 w-12 place-items-center rounded-xl bg-slate-100 text-slate-400">
                        @include('layouts.partials.icons', ['icon' => 'calendar'])
                    </span>

                    <p class="mt-3 text-sm font-semibold text-slate-700">
                        No hay citas para este día
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        No se encontraron citas con los filtros actuales.
                    </p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($citasDiaSeleccionado as $citaDia)
                        <a
                            href="{{ route('agenda.factura', $citaDia->id) }}"
                            class="flex flex-wrap items-center gap-x-4 gap-y-2 px-5 py-4 transition hover:bg-cyan-50/40 sm:px-7"
                        >
                            <span class="w-14 shrink-0 text-sm font-extrabold tabular-nums text-cyan-700">
                                {{ substr($citaDia->hora, 0, 5) }}
                            </span>

                            <span class="min-w-40 flex-1 text-sm font-bold text-slate-800">
                                {{ trim(($citaDia->cliente->nombreCliente ?? '') . ' ' . ($citaDia->cliente->apellidoCliente ?? '')) ?: 'Cliente' }}
                            </span>

                            <span class="text-xs text-slate-500">
                                {{ $citaDia->placasVehiculosParaVista ?: 'Sin vehículo' }}
                            </span>

                            <span class="rounded-full px-3 py-1.5 text-[11px] font-bold
                                {{ $citaDia->estado === 'confirmada' ? 'bg-emerald-50 text-emerald-700' : ($citaDia->estado === 'cancelada' ? 'bg-rose-50 text-rose-700' : ($citaDia->estado === 'terminada' ? 'bg-sky-50 text-sky-700' : 'bg-amber-50 text-amber-700')) }}">
                                {{ ucfirst($citaDia->estado) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
</div>

<x-floating-action
    :href="route('agenda.create', array_merge(request()->query(), ['fecha' => $vista === 'calendario' ? $fechaCalendario : (request('desde') ?: today()->toDateString())]))"
    label="Nueva cita"
    icon="calendar"
/>
@endsection
