<?php

namespace Database\Seeders;

use App\Models\servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        // Se conservan las filas anteriores para no alterar citas históricas,
        // pero se ocultan del formulario de nuevas citas.
        servicio::whereIn('nombre', ['lavado', 'jabón', 'cera para brillar autos'])
            ->update(['estado' => false]);

        foreach ([
            'Lavada solo por fuera' => 'Lavado exterior del vehículo.',
            'Lavada por dentro y por fuera' => 'Limpieza interior y exterior.',
            'Lavada por dentro, por fuera y por debajo' => 'Limpieza interior, exterior y parte inferior.',
            'Lavada básica (limpiando motor)' => 'Lavado básico con limpieza de motor.',
            'Lavado de motor en seco' => 'Limpieza de motor sin agua.',
            'Lavado de motor a vapor' => 'Lavado de motor con vapor. Precio desde el valor indicado.',
            'Enceradas' => 'Encerado del vehículo. Precio desde el valor indicado.',
            'Polichado / Quita rayones' => 'Polichado y tratamiento para quitar rayones.',
            'Desarme de cojinería y tapicería' => 'Desarme y limpieza de cojinería y tapicería.',
            'Lavada general con todo incluido' => 'Servicio integral. Valor acordado con el cliente.',
            'Lavada' => 'Lavado de motocicleta.',
            'Lavada con encerada' => 'Lavado y encerado de motocicleta.',
            'Juagada' => 'Juagada de turbo.',
            'Todo menos motor' => 'Lavado completo de turbo, excepto el motor.',
            'General' => 'Lavado general de turbo.',
        ] as $nombre => $descripcion) {
            servicio::firstOrCreate(['nombre' => $nombre], [
                'descripcion' => $descripcion,
                'estado' => true,
            ]);
        }

        servicio::whereIn('nombre', [
            'Lavada solo por fuera', 'Lavada por dentro y por fuera',
            'Lavada por dentro, por fuera y por debajo', 'Lavada básica (limpiando motor)',
            'Lavado de motor en seco', 'Lavado de motor a vapor', 'Enceradas',
            'Polichado / Quita rayones', 'Desarme de cojinería y tapicería',
            'Lavada general con todo incluido', 'Lavada', 'Lavada con encerada',
            'Juagada', 'Todo menos motor', 'General',
        ])->update(['estado' => true]);
    }
}
