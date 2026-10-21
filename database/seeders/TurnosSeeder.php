<?php

namespace Database\Seeders;

use App\Models\empleado;
use App\Models\turno;
use Illuminate\Database\Seeder;

class TurnosSeeder extends Seeder
{
    public function run(): void
    {
        $turnosPrueba = [
            ['lunes', 'mañana', '07:00:00', '12:00:00'],
            ['miercoles', 'tarde', '12:00:00', '18:00:00'],
            ['viernes', 'mañana', '07:00:00', '12:00:00'],
        ];

        empleado::query()->each(function (empleado $empleado) use ($turnosPrueba) {
            foreach ($turnosPrueba as [$dia, $jornada, $horaInicio, $horaFin]) {
                turno::updateOrCreate([
                    'empleado_id' => $empleado->id,
                    'dia' => $dia,
                    'jornada' => $jornada,
                ], [
                    'horaInicio' => $horaInicio,
                    'horaFin' => $horaFin,
                ]);
            }
        });
    }
}
