<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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

    public function test_period_report_shows_employee_ranking_and_commission_rate(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', config('seguridad.admin.email'))->firstOrFail();
        $hoy = Carbon::today()->toDateString();

        $this->actingAs($admin)
            ->get(route('reportes.periodo', ['desde' => $hoy, 'hasta' => $hoy]))
            ->assertOk()
            ->assertSee('Rendimiento por empleado')
            ->assertSee('Empleado Demo')
            ->assertSee('Comisión efectiva')
            ->assertSee('Disponible antes de gastos');
    }

    public function test_agenda_calendar_renders_demo_events_from_the_reusable_partial(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', config('seguridad.admin.email'))->firstOrFail();

        $this->actingAs($admin)
            ->get(route('agenda.index', [
                'vista' => 'calendario',
                'mes' => Carbon::today()->format('Y-m'),
            ]))
            ->assertOk()
            ->assertSee('08:00')
            ->assertSee('10:00');
    }
}
