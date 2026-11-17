<?php

namespace Database\Seeders;

use App\Models\Empleado;
use Illuminate\Database\Seeder;

class EmpleadosSeeder extends Seeder
{
    public function run(): void
    {
        Empleado::updateOrCreate(['identificacion' => 'DEMO-EMP-001'], [
            'nombre' => 'Empleado',
            'apellido' => 'Demo',
            'telefono' => '3000000001',
            'rh' => 'O+',
            'eps' => 'EPS Demo',
            'estado' => true,
            'porcentajeComision' => 10,
        ]);

        foreach (range(1, 3) as $numero) {
            Empleado::firstOrCreate(
                ['identificacion' => "DEMO-EMP-00{$numero}"],
                Empleado::factory()->make([
                    'identificacion' => "DEMO-EMP-00{$numero}",
                ])->getAttributes()
            );
        }
    }
}
