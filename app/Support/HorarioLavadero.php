<?php

namespace App\Support;

use App\Services\ConfiguracionLavaderoService;
use Carbon\Carbon;

class HorarioLavadero
{
    public static function permite(
        string $fecha,
        string $hora,
        int $duracionMinutos = 30
    ): bool {
        $dia = Carbon::parse($fecha);

        $esDomingoOFestivo = $dia->isSunday()
            || in_array(
                $dia->toDateString(),
                config('lavadero.festivos', []),
                true
            );

        $configuracion = app(ConfiguracionLavaderoService::class)->obtener();
        $inicio = $esDomingoOFestivo
            ? ($configuracion->apertura_domingo_festivo ?: config('lavadero.apertura_domingo_festivo', '08:00'))
            : ($configuracion->apertura_regular ?: config('lavadero.apertura_regular', '07:00'));

        $fin = $esDomingoOFestivo
            ? ($configuracion->cierre_domingo_festivo ?: config('lavadero.cierre_domingo_festivo', '13:00'))
            : ($configuracion->cierre_regular ?: config('lavadero.cierre_regular', '18:00'));

        $horaInicio = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dia->toDateString().' '.substr($hora, 0, 5)
        );

        $horaFin = $horaInicio->copy()->addMinutes($duracionMinutos);

        $apertura = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dia->toDateString().' '.substr($inicio, 0, 5)
        );

        $cierre = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dia->toDateString().' '.substr($fin, 0, 5)
        );

        return $horaInicio->greaterThanOrEqualTo($apertura)
            && $horaInicio->lessThan($cierre)
            && $horaFin->lessThanOrEqualTo($cierre);
    }
}
