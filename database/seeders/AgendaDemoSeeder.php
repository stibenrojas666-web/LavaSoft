<?php

namespace Database\Seeders;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\clientes;
use App\Models\precioServicio;
use App\Models\servicio;
use App\Models\vehiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AgendaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = clientes::where('emailCliente', 'cliente.demo@lavasoft.test')->firstOrFail();
        $carro = vehiculo::where('placa', 'DEMO001')->firstOrFail();
        $moto = vehiculo::where('placa', 'DEMO002')->firstOrFail();
        $carroPendiente = vehiculo::where('placa', 'DEMO004')->firstOrFail();

        DB::transaction(function () use ($cliente, $carro, $moto, $carroPendiente) {
            $citaConDosVehiculos = agenda::firstOrCreate([
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

            $citaPendiente = agenda::firstOrCreate([
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

    private function agregarLinea(agenda $agenda, vehiculo $vehiculo, string $nombreServicio, ?float $precioAcordado = null): void
    {
        $servicio = servicio::where('nombre', $nombreServicio)->firstOrFail();
        $configuracion = precioServicio::where('servicio_id', $servicio->id)
            ->where('tipo_vehiculo_id', $vehiculo->tipoVehiculoId)
            ->firstOrFail();

        $precio = $configuracion->modalidad === 'convenir'
            ? $precioAcordado
            : $configuracion->precio;

        if ($precio === null) {
            throw new \RuntimeException("Falta un precio para {$nombreServicio} en el seeder de agenda.");
        }

        agendaServicios::firstOrCreate([
            'agendaId' => $agenda->id,
            'vehiculoId' => $vehiculo->id,
            'servicioId' => $servicio->id,
        ], ['Precio' => $precio]);
    }
}
