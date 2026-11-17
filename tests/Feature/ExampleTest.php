<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_with_seeded_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $adminEmail = config('seguridad.admin.email');
        $response = $this->actingAs(User::where('email', $adminEmail)->firstOrFail())
            ->get('/dashboard');

        $response->assertOk()
            ->assertSee('Clientes')
            ->assertSee('Vehículos')
            ->assertSee('Citas de hoy');
    }
}
