<?php

namespace Database\Seeders;

use App\Models\empleado;
use App\Models\turno;
use Illuminate\Database\Seeder;

class TurnosSeeder extends Seeder
{
    public function run(): void
    {
        $empleado = empleado::where('identificacion', 'DEMO-EMP-001')->firstOrFail();

        foreach ([
            ['lunes', 'mañana', '07:00:00', '12:00:00'],
            ['miercoles', 'tarde', '12:00:00', '18:00:00'],
            ['viernes', 'mañana', '07:00:00', '12:00:00'],
        ] as [$dia, $jornada, $horaInicio, $horaFin]) {
            turno::updateOrCreate([
                'empleado_id' => $empleado->id,
                'dia' => $dia,
                'jornada' => $jornada,
            ], [
                'horaInicio' => $horaInicio,
                'horaFin' => $horaFin,
            ]);
        }
    }
}
