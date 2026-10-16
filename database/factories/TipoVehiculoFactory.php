<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TipoVehiculoFactory extends Factory
{
    protected $model = \App\Models\tipoVehiculo::class;

    public function definition(): array
    {
        return ['nombre' => fake()->randomElement(['carro', 'moto', 'turbo'])];
    }
}
