<?php

namespace App\Support;

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

        $inicio = $esDomingoOFestivo
            ? config('lavadero.apertura_domingo_festivo', '08:00')
            : config('lavadero.apertura_regular', '07:00');

        $fin = $esDomingoOFestivo
            ? config('lavadero.cierre_domingo_festivo', '13:00')
            : config('lavadero.cierre_regular', '18:00');

        $horaInicio = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dia->toDateString() . ' ' . substr($hora, 0, 5)
        );

        $horaFin = $horaInicio->copy()->addMinutes($duracionMinutos);

        $apertura = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dia->toDateString() . ' ' . $inicio
        );

        $cierre = Carbon::createFromFormat(
            'Y-m-d H:i',
            $dia->toDateString() . ' ' . $fin
        );

        return $horaInicio->greaterThanOrEqualTo($apertura)
            && $horaInicio->lessThan($cierre)
            && $horaFin->lessThanOrEqualTo($cierre);
    }
}