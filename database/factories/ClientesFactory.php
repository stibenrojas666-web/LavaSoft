<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientesFactory extends Factory
{
    protected $model = \App\Models\clientes::class;

    public function definition(): array
    {
        return [
            'nombreCliente' => fake()->firstName(),
            'apellidoCliente' => fake()->lastName(),
            'telefonoCliente' => fake()->numerify('3#########'),
            'emailCliente' => fake()->unique()->safeEmail(),
        ];
    }
}
