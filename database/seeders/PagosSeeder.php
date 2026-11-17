<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\AgendaServicios;
use App\Models\Pago;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        $cita = Agenda::query()
            ->where('fecha', now()->toDateString())
            ->where('hora', '08:00:00')
            ->whereHas('agendaServicios.vehiculo', fn ($query) => $query->where('placa', 'DEMO001'))
            ->firstOrFail();

        DB::transaction(function () use ($cita) {
            $monto = AgendaServicios::where('agendaId', $cita->id)->sum('Precio');

            if ($monto <= 0) {
                return;
            }

            Pago::firstOrCreate([
                'agendaId' => $cita->id,
            ], [
                'Monto' => $monto,
                'MetodoPago' => 'Efectivo',
                'FechaPago' => now(),
                'RequerirFactura' => false,
            ]);

            $cita->update(['estado' => 'terminada']);
        });
    }
}
