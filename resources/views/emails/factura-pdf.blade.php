<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"></head>
<body>
@php
    $rutaLogo = $configuracionLavadero->mostrar_logo && $configuracionLavadero->logo_path
        ? storage_path('app/public/' . ltrim($configuracionLavadero->logo_path, '/'))
        : null;
    $logoPdf = $rutaLogo && file_exists($rutaLogo) ? $rutaLogo : null;
@endphp
@include('emails.partials.factura', ['logoSrc' => $logoPdf])
</body>
</html>