
@php
    $nombreCliente = $agenda->cliente->nombreCliente ?? 'cliente';
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de servicio - LavaSoft</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#183445;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:30px 12px;background:#f1f5f9;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:720px;background:#ffffff;border:1px solid #dce7ed;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="padding:27px 24px;background:#082b3b;text-align:center;">
                            <p style="margin:0 0 7px;color:#67e8f9;font-size:11px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;">
                                LavaSoft
                            </p>
                            <h1 style="margin:0;color:#ffffff;font-size:23px;line-height:1.3;">
                                Comprobante de servicio
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:27px 24px 20px;">
                            <p style="margin:0 0 9px;color:#102f40;font-size:19px;font-weight:bold;">
                                Hola {{ $nombreCliente }},
                            </p>
                            <p style="margin:0;color:#526b79;font-size:14px;line-height:1.8;">
                                Gracias por visitarnos. Te compartimos el comprobante de tu servicio.
                                También encontrarás una copia en PDF adjunta a este correo.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 16px 22px;">
                            @include('emails.partials.factura', ['logoSrc' => null])
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:19px 24px;background:#f8fbfc;border-top:1px solid #e5edf1;text-align:center;">
                            <p style="margin:0 0 6px;color:#315563;font-size:13px;font-weight:bold;">
                                Gracias por preferirnos
                            </p>
                            <p style="margin:0;color:#718795;font-size:12px;line-height:1.7;">
                                Si tienes alguna pregunta, responde a este correo o comunícate con nosotros.
                            </p>
                            <p style="margin:12px 0 0;color:#94a3b8;font-size:11px;">
                                Mensaje generado automáticamente por LavaSoft.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
