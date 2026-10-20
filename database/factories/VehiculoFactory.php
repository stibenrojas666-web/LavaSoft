<?php

namespace Database\Factories;

use App\Models\clientes;
use App\Models\tipoVehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehiculoFactory extends Factory
{
    protected $model = \App\Models\vehiculo::class;

    public function definition(): array
    {
        return [
            'clienteID' => clientes::factory(),
            'tipoVehiculoId' => tipoVehiculo::query()->inRandomOrder()->value('id') ?? tipoVehiculo::factory(),
            'placa' => strtoupper(fake()->unique()->bothify('???####')),
            'modelo' => fake()->word(),
            'color' => fake()->safeColorName(),
            'estado' => 'Activo',
        ];
    }
}
