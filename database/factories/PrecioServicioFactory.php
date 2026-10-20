<?php

namespace Database\Factories;

use App\Models\servicio;
use App\Models\tipoVehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrecioServicioFactory extends Factory
{
    protected $model = \App\Models\precioServicio::class;

    public function definition(): array
    {
        return [
            'servicio_id' => servicio::factory(),
            'tipo_vehiculo_id' => tipoVehiculo::query()->inRandomOrder()->value('id') ?? tipoVehiculo::factory(),
            'precio' => fake()->numberBetween(10000, 150000),
            'modalidad' => 'fijo',
        ];
    }
}
