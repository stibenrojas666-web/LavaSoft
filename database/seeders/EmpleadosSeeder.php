<?php

namespace Database\Seeders;

use App\Models\empleado;
use Illuminate\Database\Seeder;

class EmpleadosSeeder extends Seeder
{
    public function run(): void
    {
        empleado::firstOrCreate(['identificacion' => 'DEMO-EMP-001'], [
            'nombre' => 'Empleado',
            'apellido' => 'Demo',
            'telefono' => '3000000001',
            'rh' => 'O+',
            'eps' => 'EPS Demo',
            'estado' => true,
        ]);

        foreach (range(1, 3) as $numero) {
            empleado::firstOrCreate(
                ['identificacion' => "DEMO-EMP-00{$numero}"],
                empleado::factory()->make([
                    'identificacion' => "DEMO-EMP-00{$numero}",
                ])->getAttributes()
            );
        }
    }
}
