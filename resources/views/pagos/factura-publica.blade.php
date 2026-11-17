<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de servicio #{{ $agenda->referencia }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        @media print {
            .no-imprimir {
                display: none !important;
            }

            body {
                background: #fff !important;
                padding: 0 !important;
            }

            .factura-publica-contenedor {
                max-width: none !important;
                padding: 0 !important;
            }

            .factura-publica-papel {
                border: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 py-6 sm:py-10">
    <main class="factura-publica-contenedor mx-auto w-full max-w-2xl px-3 sm:px-5">
        <div class="no-imprimir mb-5 flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-bold text-slate-800">Comprobante de servicio</p>
                <p class="text-xs text-slate-500">Factura de la cita #{{ $agenda->referencia }}</p>
            </div>

            <button
                type="button"
                onclick="window.print()"
                class="rounded-xl bg-cyan-800 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-cyan-900"
            >
                Imprimir o guardar PDF
            </button>
        </div>

        <div class="factura-publica-papel overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
            @include('pagos.partials.detalleFactura')
        </div>
    </main>
</body>
</html>
