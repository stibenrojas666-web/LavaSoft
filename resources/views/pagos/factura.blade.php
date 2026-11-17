@extends('layouts.app')

@section('title', 'Factura de cita #' . $agenda->referencia)
@section('page-title', 'Factura de servicio')

@section('content')
<div class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6">

    <div class="no-imprimir flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                Factura de servicio
            </h1>
            <p class="mt-1 text-sm text-slate-500">Consulta los servicios, el total y el estado del pago de esta cita.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if($agenda->pago && $agenda->cliente?->emailCliente)
                <form action="{{ route('agenda.factura.correo', $agenda->id) }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        title="Enviar factura por correo"
                        aria-label="Enviar factura por correo"
                        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-cyan-800 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-cyan-900 focus:outline-none focus:ring-4 focus:ring-cyan-200"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path d="m3 7 9 6 9-6"/>
                        </svg>
                        Enviar factura
                    </button>
                </form>
            @endif

            <button
                type="button"
                onclick="window.print()"
                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-200"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-4a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v4a2 2 0 0 1-2 2h-2"/>
                    <path d="M7 14h10v7H7z"/>
                </svg>
                Imprimir
            </button>
        </div>
    </div>

    @if($agenda->pago && !$agenda->cliente?->emailCliente)
        <div class="no-imprimir rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            Este cliente no tiene un correo electrónico registrado. Puedes agregarlo desde el módulo Clientes o imprimir la factura.
        </div>
    @endif

    @if(session('success'))
        <div class="no-imprimir rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @error('pago')
        <div class="no-imprimir rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $message }}
        </div>
    @enderror

    @error('correo')
        <div class="no-imprimir rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ $message }}
        </div>
    @enderror

    <div class="factura-papel mx-auto max-w-4xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">
        @include('pagos.partials.detalleFactura')

        @if($agenda->pago)
            <section class="mx-5 mb-7 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-sm text-emerald-950 sm:mx-9">
                <div class="mb-3 flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-emerald-100 text-lg font-bold text-emerald-700">✓</span>
                    <div>
                        <p class="font-bold">Pago registrado</p>
                        <p class="text-xs text-emerald-800">Esta cita ya cuenta con un pago registrado.</p>
                    </div>
                </div>

                <div class="grid gap-3 border-t border-emerald-200 pt-3 sm:grid-cols-3">
                    <p class="text-xs text-emerald-800">
                        Método
                        <strong class="mt-1 block text-sm text-emerald-950">{{ $agenda->pago->MetodoPago }}</strong>
                    </p>
                    <p class="text-xs text-emerald-800">
                        Fecha
                        <strong class="mt-1 block text-sm text-emerald-950">{{ $agenda->pago->FechaPago?->format('d/m/Y H:i') }}</strong>
                    </p>
                    <p class="text-xs text-emerald-800">
                        Valor pagado
                        <strong class="mt-1 block text-sm text-emerald-950">${{ number_format((float) $agenda->pago->Monto, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </section>
        @elseif($agenda->estado === 'confirmada')
            <form
                action="{{ route('agenda.pago.registrar', $agenda->id) }}"
                method="POST"
                class="no-imprimir mx-5 mb-7 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:mx-9 sm:p-6"
            >
                @csrf

                <div class="mb-5">
                    <h2 class="text-lg font-extrabold text-slate-900">Registrar pago</h2>
                    <p class="mt-1 text-sm text-slate-500">Se cobrará el total calculado en la factura. No se permiten abonos.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="MetodoPago" class="mb-2 block text-sm font-semibold text-slate-700">Método de pago</label>
                        <select
                            id="MetodoPago"
                            name="MetodoPago"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100 @error('MetodoPago') border-red-500 @enderror"
                        >
                            <option value="">Selecciona un método</option>
                            @foreach(['Efectivo', 'Transferencia', 'Tarjeta'] as $metodo)
                                <option value="{{ $metodo }}" @selected(old('MetodoPago') === $metodo)>{{ $metodo }}</option>
                            @endforeach
                        </select>
                        @error('MetodoPago')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 sm:mt-7">
                        <input type="hidden" name="RequerirFactura" value="0">
                        <input
                            type="checkbox"
                            name="RequerirFactura"
                            value="1"
                            @checked(old('RequerirFactura', false))
                            class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600"
                        >
                        El cliente solicita factura
                    </label>
                </div>

                @error('RequerirFactura')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    class="mt-5 min-h-11 rounded-xl bg-emerald-700 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-200"
                >
                    Registrar pago
                </button>
            </form>
        @else
            <div class="no-imprimir mx-5 mb-7 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 sm:mx-9">
                Solo se puede registrar el pago cuando la cita está confirmada.
                Estado actual: <strong>{{ ucfirst($agenda->estado) }}</strong>.
            </div>
        @endif
    </div>
</div>

<style>
    @page {
        size: A4;
        margin: 14mm;
    }

    @media print {
        .no-imprimir,
        aside[aria-label="Menú principal"],
        header.sticky,
        body > div > div > footer {
            display: none !important;
        }

        html,
        body {
            background: #fff !important;
            height: auto !important;
        }

        main {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .factura-papel {
            width: 100% !important;
            max-width: none !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .factura-contenido {
            max-width: none !important;
            padding: 0 0 8mm !important;
        }

        .factura-contenido table,
        .factura-contenido section {
            page-break-inside: avoid;
        }

        .factura-contenido header {
            border-color: #cbd5e1 !important;
        }

        * {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endsection
