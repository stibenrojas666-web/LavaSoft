<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Repositories\AgendaRepository;
use App\Repositories\Contracts\AgendaRepositoryInterface;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArquitecturaYAccesoTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_inicio_de_sesion_esta_disponible(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Iniciar sesión');
    }

    public function test_el_dashboard_requiere_autenticacion(): void
    {
        $this->get('/dashboard')->assertRedirect('/');
    }

    public function test_el_contenedor_resuelve_el_contrato_del_repositorio_de_agenda(): void
    {
        $this->assertInstanceOf(
            AgendaRepository::class,
            $this->app->make(AgendaRepositoryInterface::class)
        );
    }

    public function test_el_modelo_agenda_declara_sus_campos_asignables(): void
    {
        $this->assertContains('clienteId', (new Agenda())->getFillable());
        $this->assertContains('fecha', (new Agenda())->getFillable());
    }

    public function test_el_seeder_crea_el_administrador_configurado(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => config('seguridad.admin.email'),
        ]);
    }
}
