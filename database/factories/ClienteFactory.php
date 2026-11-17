<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

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
