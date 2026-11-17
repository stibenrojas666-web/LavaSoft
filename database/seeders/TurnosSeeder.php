<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Turno;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnosSeeder extends Seeder
{
    public function run(): void
    {
        $turnosPrueba = [['domingo', 'mañana', '08:00:00', '13:00:00']];
        foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'] as $dia) {
            $turnosPrueba[] = [$dia, 'mañana', '07:00:00', '12:00:00'];
            $turnosPrueba[] = [$dia, 'tarde', '12:00:00', '17:00:00'];
        }

        $empleadoIds = Empleado::query()->pluck('id');
        $turnosExistentes = Turno::query()
            ->whereIn('empleado_id', $empleadoIds)
            ->get()
            ->keyBy(fn (Turno $turno) => $turno->empleado_id.'|'.$turno->dia.'|'.$turno->jornada);

        $ahora = now();
        $filas = [];
        foreach ($empleadoIds as $empleadoId) {
            foreach ($turnosPrueba as [$dia, $jornada, $horaInicio, $horaFin]) {
                $clave = $empleadoId.'|'.$dia.'|'.$jornada;
                $turnoExistente = $turnosExistentes->get($clave);
                $filas[] = [
                    ...($turnoExistente ? ['id' => $turnoExistente->id] : []),
                    'empleado_id' => $empleadoId,
                    'dia' => $dia,
                    'jornada' => $jornada,
                    'horaInicio' => $horaInicio,
                    'horaFin' => $horaFin,
                    'created_at' => $turnoExistente?->created_at ?? $ahora,
                    'updated_at' => $ahora,
                ];
            }
        }

        if ($filas !== []) {
            DB::table('turnos')->upsert(
                $filas,
                ['id'],
                ['horaInicio', 'horaFin', 'updated_at']
            );
        }
    }
}
