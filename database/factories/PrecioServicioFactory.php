<?php

namespace Database\Factories;

use App\Models\Servicio;
use App\Models\TipoVehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrecioServicioFactory extends Factory
{
    protected $model = \App\Models\PrecioServicio::class;

    public function definition(): array
    {
        return [
            'servicio_id' => Servicio::factory(),
            'tipo_vehiculo_id' => TipoVehiculo::query()->inRandomOrder()->value('id') ?? TipoVehiculo::factory(),
            'precio' => fake()->numberBetween(10000, 150000),
            'modalidad' => 'fijo',
        ];
    }
}
