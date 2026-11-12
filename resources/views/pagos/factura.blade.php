@extends('layouts.app')

@section('title', 'Factura de cita #' . $agenda->id)
@section('page-title', 'Factura de servicio')

@section('content')
<div class="mx-auto w-full max-w-6xl">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between no-imprimir">
            <div>
                <a href="{{ route('agenda.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-cyan-800 hover:text-cyan-950">← Volver a la agenda</a>
                <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Factura de servicio</h1>
                <p class="mt-1 text-sm text-slate-500">Revisa los datos y elige cómo compartir el comprobante.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @if($agenda->pago)
                    @if($agenda->cliente?->emailCliente)
                        <form action="{{ route('agenda.factura.correo', $agenda->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-cyan-800 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-cyan-950 focus:outline-none focus:ring-4 focus:ring-cyan-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                                Enviar al correo
                            </button>
                        </form>
                    @endif
                    @if($urlWhatsApp)
                        <a href="{{ $urlWhatsApp }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.5 11.5a8.5 8.5 0 0 1-12.6 7.4L3 20l1.2-4.7A8.5 8.5 0 1 1 20.5 11.5Z"/><path d="M8.5 8.5c.4 2.8 2.2 4.6 5 5l1.1-1.1 2 .8c-.2 1.4-1.2 2.2-2.6 2.1-3.8-.4-6.8-3.4-7.2-7.2-.1-1.4.7-2.4 2.1-2.6l.8 2-1.2 1Z"/></svg>
                            Preparar WhatsApp
                        </a>
                    @endif
                @endif
                <button type="button" onclick="window.print()" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-200">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-4a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v4a2 2 0 0 1-2 2h-2"/><path d="M7 14h10v7H7z"/></svg>
                    Imprimir / Guardar PDF
                </button>
            </div>
        </div>
        @if($agenda->pago && ($agenda->cliente?->emailCliente || $urlWhatsApp))
            <p class="mt-2 text-right text-xs text-slate-500 no-imprimir">El correo se envía al dato registrado. WhatsApp abre el chat con el mensaje listo; confirma el envío allí y adjunta el PDF si lo necesitas.</p>
        @endif

        @if($agenda->pago && !$agenda->cliente?->emailCliente && !$telefonoWhatsApp)
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 no-imprimir">
                Este cliente no tiene correo ni teléfono registrados. Puedes imprimir la factura; agrega sus datos desde Clientes para habilitar el envío.
            </div>
        @elseif($agenda->pago && !$agenda->cliente?->emailCliente && !$telefonoWhatsAppValido)
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 no-imprimir">No hay un correo ni un teléfono válido para WhatsApp. Actualiza los datos del cliente o imprime la factura.</div>
        @elseif($agenda->pago && !$agenda->cliente?->emailCliente)
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 no-imprimir">No hay correo registrado. Puedes usar WhatsApp o imprimir la factura.</div>
        @elseif($agenda->pago && !$telefonoWhatsApp)
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 no-imprimir">No hay teléfono registrado. Puedes usar el correo o imprimir la factura.</div>
        @elseif($agenda->pago && !$telefonoWhatsAppValido)
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 no-imprimir">El teléfono no tiene un indicativo internacional válido para WhatsApp. Actualízalo desde Clientes; también puedes usar el correo o imprimir la factura.</div>
        @endif

        @if(session('success'))
            <p class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm font-medium text-emerald-800 no-imprimir">{{ session('success') }}</p>
        @endif
        @error('pago')
            <p class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800 no-imprimir">{{ $message }}</p>
        @enderror
        @error('correo')
            <p class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800 no-imprimir">{{ $message }}</p>
        @enderror

        <div class="mx-auto max-w-4xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_20px_60px_rgba(11,42,58,0.10)] factura-papel">
            @include('pagos.partials.detalleFactura')

            @if($agenda->pago)
                <section class="mx-6 mb-6 rounded-2xl border border-emerald-100 bg-emerald-50/70 px-5 py-4 text-sm text-emerald-950 sm:mx-9">
                    <div class="mb-2 flex items-center gap-2 font-bold"><span class="grid h-7 w-7 place-items-center rounded-full bg-emerald-100">✓</span>Pago registrado</div>
                    <div class="grid gap-1 text-xs text-emerald-900 sm:grid-cols-3">
                        <p>Método: <strong>{{ $agenda->pago->MetodoPago }}</strong></p>
                        <p>Fecha: <strong>{{ $agenda->pago->FechaPago?->format('d/m/Y H:i') }}</strong></p>
                        <p>Valor pagado: <strong>${{ number_format((float) $agenda->pago->Monto, 0, ',', '.') }}</strong></p>
                    </div>
                </section>
            @elseif($agenda->estado === 'confirmada')
                <form action="{{ route('agenda.pago.registrar', $agenda->id) }}" method="POST" class="mx-6 mb-7 rounded-2xl border border-slate-200 bg-slate-50 p-5 no-imprimir sm:mx-9 sm:p-6">
                    @csrf
                    <h2 class="text-lg font-extrabold text-slate-900">Registrar pago</h2>
                    <p class="mb-4 mt-1 text-sm text-slate-500">Se cobrará el total calculado en la factura. No se permiten abonos.</p>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="MetodoPago" class="mb-1.5 block text-sm font-semibold">Método de pago</label>
                            <select id="MetodoPago" name="MetodoPago" class="w-full rounded-xl border px-3 py-2.5 @error('MetodoPago') border-red-500 @enderror">
                                <option value="">Selecciona un método</option>
                                @foreach(['Efectivo', 'Transferencia', 'Tarjeta'] as $metodo)
                                    <option value="{{ $metodo }}" @selected(old('MetodoPago') === $metodo)>{{ $metodo }}</option>
                                @endforeach
                            </select>
                            @error('MetodoPago')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 sm:mt-6">
                            <input type="hidden" name="RequerirFactura" value="0">
                            <input type="checkbox" name="RequerirFactura" value="1" @checked(old('RequerirFactura', false)) class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">
                            El cliente solicita factura
                        </label>
                    </div>

                    @error('RequerirFactura')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    <button type="submit" class="mt-5 min-h-11 rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-200">Registrar pago</button>
                </form>
            @else
                <p class="mx-6 mb-7 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 no-imprimir sm:mx-9">Solo se puede registrar el pago cuando la cita está confirmada. Estado actual: {{ ucfirst($agenda->estado) }}.</p>
            @endif
        </div>
    </div>

    <style>
        @page { size: A4; margin: 14mm; }
        @media print {
            .no-imprimir,
            aside[aria-label="Menú principal"],
            header.sticky,
            body > div > div > footer {
                display: none !important;
            }

            html, body {
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

            .factura-contenido { max-width: none !important; padding: 0 0 8mm !important; }
            .factura-contenido table, .factura-contenido section { page-break-inside: avoid; }
            .factura-contenido header { border-color: #cbd5e1 !important; }
            * { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
@endsection
