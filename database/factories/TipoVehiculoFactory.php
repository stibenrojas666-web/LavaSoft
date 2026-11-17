<?php

namespace Database\Factories;

use App\Models\TipoVehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoVehiculoFactory extends Factory
{
    protected $model = TipoVehiculo::class;

    public function definition(): array
    {
        return ['nombre' => fake()->randomElement(['carro', 'moto', 'turbo'])];
    }
}
