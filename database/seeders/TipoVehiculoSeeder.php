<?php

namespace Database\Seeders;

use App\Models\tipoVehiculo;
use Illuminate\Database\Seeder;

class TipoVehiculoSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'carro',
            'moto',
            'camión',
            'turbo',
            'bus',
            'carro grande',
            'otros',
        ] as $nombre) {
            tipoVehiculo::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
