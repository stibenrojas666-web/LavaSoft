<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\AgendaServicios;
use App\Models\Cliente;
use App\Models\PrecioServicio;
use App\Models\Servicio;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AgendaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = Cliente::where('emailCliente', 'cliente.demo@lavasoft.test')->firstOrFail();
        $carro = Vehiculo::where('placa', 'DEMO001')->firstOrFail();
        $moto = Vehiculo::where('placa', 'DEMO002')->firstOrFail();
        $carroPendiente = Vehiculo::where('placa', 'DEMO004')->firstOrFail();

        DB::transaction(function () use ($cliente, $carro, $moto, $carroPendiente) {
            $citaConDosVehiculos = Agenda::firstOrCreate([
                'clienteId' => $cliente->id,
                'fecha' => Carbon::today()->toDateString(),
                'hora' => '08:00:00',
            ], [
                'vehiculoId' => null,
                'empleadoId' => null,
                'estado' => 'pendiente',
                'tipoDeAtencion' => 'Por orden de llegada',
            ]);

            $citaConDosVehiculos->update(['vehiculoId' => null]);
            $this->agregarLinea($citaConDosVehiculos, $carro, 'Lavada general con todo incluido', 85000);
            $this->agregarLinea($citaConDosVehiculos, $moto, 'Lavada', 12000);

            $citaPendiente = Agenda::firstOrCreate([
                'clienteId' => $cliente->id,
                'fecha' => Carbon::tomorrow()->toDateString(),
                'hora' => '10:00:00',
            ], [
                'vehiculoId' => null,
                'empleadoId' => null,
                'estado' => 'pendiente',
                'tipoDeAtencion' => 'Por cita agendada',
            ]);

            $citaPendiente->update(['vehiculoId' => null]);
            $this->agregarLinea($citaPendiente, $carroPendiente, 'Lavada solo por fuera');
            $this->agregarLinea($citaPendiente, $carroPendiente, 'Lavado de motor en seco');
        });
    }

    private function agregarLinea(Agenda $agenda, Vehiculo $vehiculo, string $nombreServicio, ?float $precioAcordado = null): void
    {
        $servicio = Servicio::where('nombre', $nombreServicio)->firstOrFail();
        $configuracion = PrecioServicio::where('servicio_id', $servicio->id)
            ->where('tipo_vehiculo_id', $vehiculo->tipoVehiculoId)
            ->firstOrFail();

        $precio = $configuracion->modalidad === 'convenir'
            ? $precioAcordado
            : $configuracion->precio;

        if ($precio === null) {
            throw new \RuntimeException("Falta un precio para {$nombreServicio} en el seeder de agenda.");
        }

        AgendaServicios::firstOrCreate([
            'agendaId' => $agenda->id,
            'vehiculoId' => $vehiculo->id,
            'servicioId' => $servicio->id,
        ], ['Precio' => $precio]);
    }
}
