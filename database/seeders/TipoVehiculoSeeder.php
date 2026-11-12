<?php

namespace Database\Seeders;

use App\Models\TipoVehiculo;
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
            TipoVehiculo::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
