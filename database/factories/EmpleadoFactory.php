<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
    protected $model = \App\Models\empleado::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'identificacion' => fake()->unique()->numerify('##########'),
            'telefono' => fake()->numerify('3#########'),
            'rh' => fake()->randomElement(config('empleado.tiposSangre')),
            'eps' => fake()->company(),
            'estado' => true,
        ];
    }
}
