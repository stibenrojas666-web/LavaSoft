<?php

namespace Database\Seeders;

use App\Models\agenda;
use App\Models\agendaServicios;
use App\Models\pago;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        $cita = agenda::query()
            ->where('fecha', now()->toDateString())
            ->where('hora', '08:00:00')
            ->whereHas('vehiculo', fn ($query) => $query->where('placa', 'DEMO001'))
            ->firstOrFail();

        DB::transaction(function () use ($cita) {
            $monto = agendaServicios::where('agendaId', $cita->id)->sum('Precio');

            if ($monto <= 0) {
                return;
            }

            pago::firstOrCreate([
                'agendaId' => $cita->id,
            ], [
                'Monto' => $monto,
                'MetodoPago' => 'Efectivo',
                'FechaPago' => now(),
                'RequerirFactura' => false,
            ]);

            // La regla del proyecto es confirmar la cita al registrar un pago.
            $cita->update(['estado' => 'confirmada']);
        });
    }
}
