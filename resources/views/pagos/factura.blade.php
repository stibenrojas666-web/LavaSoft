@extends('layouts.app')

@section('title', 'Factura de cita #' . $agenda->id)
@section('page-title', 'Factura de servicio')

@section('content')
    <div class="mx-auto w-full max-w-[44rem]">
    <x-card class="factura-papel mt-4 overflow-hidden" :padding="false">
        <div class="flex flex-wrap items-center justify-between gap-3 px-1 py-2 no-imprimir">
            <a href="{{ route('agenda.index') }}" class="text-sm font-medium text-cyan-700 hover:text-cyan-900">← Volver a la agenda</a>
            <div class="flex flex-wrap gap-2">
                @if($agenda->pago?->RequerirFactura)
                    <form action="{{ route('agenda.factura.correo', $agenda->id) }}" method="POST" novalidate>
                        @csrf
                        <button type="submit" class="rounded-lg bg-cyan-700 px-3 py-1.5 text-sm font-semibold text-white hover:bg-cyan-800">Enviar por correo</button>
                    </form>
                @endif
                @if($agenda->pago?->RequerirFactura)
                    <button type="button" onclick="window.print()" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Imprimir</button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <p class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-800 no-imprimir">{{ session('success') }}</p>
        @endif
        @error('pago')
            <p class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-800 no-imprimir">{{ $message }}</p>
        @enderror
        @error('correo')
            <p class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-800 no-imprimir">{{ $message }}</p>
        @enderror

        @include('pagos.partials.detalleFactura')

        @if($agenda->pago)
            <section class="mx-auto mb-5 max-w-[34rem] border-t border-dashed border-slate-300 px-5 pt-3 text-xs text-slate-600 sm:px-7">
                <h2 class="mb-1 font-semibold text-slate-800">Pago registrado</h2>
                <p>Método: {{ $agenda->pago->MetodoPago }} · Fecha: {{ $agenda->pago->FechaPago?->format('d/m/Y H:i') }}</p>
                <p>Monto: ${{ number_format((float) $agenda->pago->Monto, 0, ',', '.') }} · Requiere factura: {{ $agenda->pago->RequerirFactura ? 'Sí' : 'No' }}</p>
            </section>
        @elseif($agenda->estado === 'confirmada')
            <form action="{{ route('agenda.pago.registrar', $agenda->id) }}" method="POST" novalidate class="mx-auto mb-5 max-w-[34rem] border-t border-slate-200 px-5 pt-4 no-imprimir sm:px-7">
                @csrf
                <h2 class="mb-3 text-lg font-semibold text-slate-800">Registrar pago único</h2>
                <p class="mb-4 text-sm text-slate-600">Se cobrará el total calculado arriba. No se permiten abonos.</p>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="MetodoPago" class="mb-1 block font-medium text-slate-700">Método de pago</label>
                        <select id="MetodoPago" name="MetodoPago" class="w-full rounded-lg border px-3 py-2 @error('MetodoPago') border-red-500 @enderror">
                            <option value="">Selecciona un método</option>
                            @foreach(['Efectivo', 'Transferencia', 'Tarjeta'] as $metodo)
                                <option value="{{ $metodo }}" @selected(old('MetodoPago') === $metodo)>{{ $metodo }}</option>
                            @endforeach
                        </select>
                        @error('MetodoPago')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 pb-2 text-sm text-slate-700">
                            <input type="hidden" name="RequerirFactura" value="0">
                            <input type="checkbox" name="RequerirFactura" value="1" @checked(old('RequerirFactura', false)) class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">
                            El cliente requiere factura
                        </label>
                    </div>
                </div>

                @error('RequerirFactura')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <button type="submit" class="mt-5 rounded-lg bg-green-700 px-4 py-2 font-semibold text-white hover:bg-green-800">Registrar pago</button>
            </form>
        @else
            <p class="mt-6 rounded-lg bg-amber-50 p-4 text-sm text-amber-800 no-imprimir">
                Solo se puede registrar el pago cuando la cita está confirmada. Estado actual: {{ ucfirst($agenda->estado) }}.
            </p>
        @endif
    </x-card>
    </div>

    <style>
        @page { margin: 5mm; }
        @media print {
            .no-imprimir,
            aside[aria-label="Menú principal"],
            body > div > div > header,
            body > div > div > footer {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            main {
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .factura-papel {
                width: 100% !important;
                max-width: 78mm !important;
                border: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            .factura-contenido { max-width: none !important; padding: 0 !important; }
            .factura-contenido table { page-break-inside: avoid; }
        }
    </style>
@endsection
