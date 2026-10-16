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
            ['lunes', 'mañana'],
            ['miercoles', 'tarde'],
            ['viernes', 'mañana'],
        ] as [$dia, $jornada]) {
            turno::firstOrCreate([
                'empleado_id' => $empleado->id,
                'dia' => $dia,
                'jornada' => $jornada,
            ]);
        }
    }
}
