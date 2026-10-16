<?php

namespace Database\Seeders;

use App\Models\clientes;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        clientes::firstOrCreate(['emailCliente' => 'cliente.demo@lavasoft.test'], [
            'nombreCliente' => 'Cliente',
            'apellidoCliente' => 'Demo',
            'telefonoCliente' => '3000000000',
        ]);

        foreach (range(1, 3) as $numero) {
            clientes::firstOrCreate(
                ['emailCliente' => "cliente.demo{$numero}@lavasoft.test"],
                clientes::factory()->make([
                    'emailCliente' => "cliente.demo{$numero}@lavasoft.test",
                ])->getAttributes()
            );
        }
    }
}
