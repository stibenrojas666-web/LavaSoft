<?php

namespace Database\Seeders;

use App\Models\PrecioServicio;
use App\Models\Servicio;
use App\Models\TipoVehiculo;
use Illuminate\Database\Seeder;

class PrecioServicioSeeder extends Seeder
{
    public function run(): void
    {
        $catalogo = [
            'carro' => [
                'Lavada solo por fuera' => ['fijo', 15000],
                'Lavada por dentro y por fuera' => ['fijo', 25000],
                'Lavada por dentro, por fuera y por debajo' => ['fijo', 30000],
                'Lavada básica (limpiando motor)' => ['fijo', 40000],
                'Lavado de motor en seco' => ['fijo', 20000],
                'Lavado de motor a vapor' => ['desde', 30000],
                'Enceradas' => ['desde', 30000],
                'Polichado / Quita rayones' => ['fijo', 50000],
                'Desarme de cojinería y tapicería' => ['fijo', 350000],
                'Lavada general con todo incluido' => ['convenir', null],
            ],
            'moto' => [
                'Lavada' => ['fijo', 12000],
                'Lavada con encerada' => ['fijo', 15000],
            ],
            'turbo' => [
                'Juagada' => ['fijo', 50000],
                'Todo menos motor' => ['fijo', 60000],
                'General' => ['fijo', 70000],
            ],
        ];

        foreach ($catalogo as $tipoNombre => $servicios) {
            $tipo = TipoVehiculo::where('nombre', $tipoNombre)->firstOrFail();

            foreach ($servicios as $nombre => [$modalidad, $precio]) {
                $servicio = Servicio::where('nombre', $nombre)->firstOrFail();

                $configuracion = PrecioServicio::firstOrNew([
                    'servicio_id' => $servicio->id,
                    'tipo_vehiculo_id' => $tipo->id,
                ]);

                $configuracion->precio = $precio;
                $configuracion->modalidad = $modalidad;

                // Conserva la duración existente.
                // Si no existe, utiliza la duración general del servicio.
                if (empty($configuracion->duracionMinutos)) {
                    $configuracion->duracionMinutos =
                        $servicio->duracionMinutos ?: 30;
                }

                $configuracion->save();
            }
        }
    }
}