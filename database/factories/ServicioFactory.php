<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServicioFactory extends Factory
{
    protected $model = \App\Models\servicio::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->words(2, true),
            'descripcion' => fake()->optional()->sentence(8),
            'estado' => true,
        ];
    }
}
