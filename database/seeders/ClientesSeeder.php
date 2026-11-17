<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::firstOrCreate(['emailCliente' => 'cliente.demo@lavasoft.test'], [
            'nombreCliente' => 'Cliente',
            'apellidoCliente' => 'Demo',
            'telefonoCliente' => '3000000000',
        ]);

        foreach (range(1, 3) as $numero) {
            Cliente::firstOrCreate(
                ['emailCliente' => "cliente.demo{$numero}@lavasoft.test"],
                Cliente::factory()->make([
                    'emailCliente' => "cliente.demo{$numero}@lavasoft.test",
                ])->getAttributes()
            );
        }
    }
}
