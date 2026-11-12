@php
    $rutaLogo = $configuracionLavadero->mostrar_logo && $configuracionLavadero->logo_path
        ? storage_path('app/public/' . ltrim($configuracionLavadero->logo_path, '/'))
        : null;
    $logoCorreo = $rutaLogo && file_exists($rutaLogo) ? $message->embed($rutaLogo) : null;
@endphp
<div style="font-family:Arial,Helvetica,sans-serif;color:#1e293b;max-width:560px;margin:0 auto;">
    <p style="font-size:16px;">Hola {{ $agenda->cliente->nombreCliente ?? 'cliente' }},</p>
    <p style="font-size:14px;line-height:1.6;">
        Gracias por visitarnos. Aquí tienes la factura de tu servicio. Si tienes alguna duda, responde a este correo o comunícate con nosotros.
    </p>

    @include('emails.partials.factura', ['logoSrc' => $logoCorreo])

    <p style="font-size:12px;color:#64748b;text-align:center;margin-top:16px;">Este es un comprobante de servicio.</p>
</div>