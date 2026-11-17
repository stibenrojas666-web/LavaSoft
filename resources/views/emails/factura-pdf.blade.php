
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de servicio</title>
</head>
<body>
    @php
        $rutaLogo = ($configuracionLavadero->mostrar_logo ?? false) && $configuracionLavadero->logo_path
            ? storage_path('app/public/' . ltrim($configuracionLavadero->logo_path, '/'))
            : null;

        $logoPdf = $rutaLogo && file_exists($rutaLogo) ? $rutaLogo : null;
    @endphp

    @include('emails.partials.factura', ['logoSrc' => $logoPdf])
</body>
</html>
