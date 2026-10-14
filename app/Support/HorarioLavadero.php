<?php

namespace App\Support;

use Carbon\Carbon;

class HorarioLavadero
{
    public static function permite(string $fecha, string $hora): bool
    {
        $dia = Carbon::parse($fecha);
        $esDomingoOFestivo = $dia->isSunday()
            || in_array($dia->toDateString(), config('lavadero.festivos', []), true);

        $inicio = $esDomingoOFestivo
            ? config('lavadero.apertura_domingo_festivo', '08:00')
            : config('lavadero.apertura_regular', '07:00');
        $fin = $esDomingoOFestivo
            ? config('lavadero.cierre_domingo_festivo', '13:00')
            : config('lavadero.cierre_regular', '18:00');

        $hora = substr($hora, 0, 5);

        // La hora de cierre se trata como límite exclusivo: a esa hora ya no se agenda.
        return $hora >= $inicio && $hora < $fin;
    }
}
