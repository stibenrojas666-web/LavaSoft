<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de servicio #{{ $agenda->id }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @page { margin: 5mm; }
        @media print {
            .no-imprimir { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="bg-slate-100 py-6">
    <div class="mx-auto w-full max-w-[34rem] px-3">
        <div class="no-imprimir mb-3 flex justify-end">
            <button type="button" onclick="window.print()" class="rounded-lg bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-800">Imprimir o guardar PDF</button>
        </div>
        <div class="rounded-lg bg-white shadow">
            @include('pagos.partials.detalleFactura')
        </div>
    </div>
</body>
</html>