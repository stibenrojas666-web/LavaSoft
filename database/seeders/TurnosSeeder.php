<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Turno;
use Illuminate\Database\Seeder;

class TurnosSeeder extends Seeder
{
    public function run(): void
    {
        $turnosPrueba = [['domingo', 'mañana', '08:00:00', '13:00:00']];
        foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'] as $dia) {
            $turnosPrueba[] = [$dia, 'mañana', '07:00:00', '12:00:00'];
            $turnosPrueba[] = [$dia, 'tarde', '12:00:00', '17:00:00'];
        }

        Empleado::query()->each(function (Empleado $empleado) use ($turnosPrueba) {
            foreach ($turnosPrueba as [$dia, $jornada, $horaInicio, $horaFin]) {
                Turno::updateOrCreate([
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
