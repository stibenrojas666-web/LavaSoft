
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar duración a la configuración de precios por tipo de vehículo.
        if (!Schema::hasColumn('precio_servicios', 'duracionMinutos')) {
            Schema::table('precio_servicios', function (Blueprint $table) {
                $table->unsignedSmallInteger('duracionMinutos')
                    ->default(30)
                    ->after('precio');
            });
        }

        // Agregar duración a cada servicio registrado en la agenda.
        if (!Schema::hasColumn('agenda_servicios', 'duracionMinutos')) {
            Schema::table('agenda_servicios', function (Blueprint $table) {
                $table->unsignedSmallInteger('duracionMinutos')
                    ->default(30)
                    ->after('Precio');
            });
        }

        // Inicializar la duración con la duración general del servicio.
        $duracionPorServicio = Schema::hasColumn('servicios', 'duracionMinutos')
            ? DB::table('servicios')->pluck('duracionMinutos', 'id')
            : collect();

        DB::table('precio_servicios')->orderBy('id')->chunk(200, function ($precios) use ($duracionPorServicio): void {
            foreach ($precios as $precio) {
                DB::table('precio_servicios')->where('id', $precio->id)->update([
                    'duracionMinutos' => $duracionPorServicio[$precio->servicio_id] ?? 30,
                ]);
            }
        });

        DB::table('agenda_servicios')->orderBy('id')->chunk(200, function ($lineas) use ($duracionPorServicio): void {
            foreach ($lineas as $linea) {
                DB::table('agenda_servicios')->where('id', $linea->id)->update([
                    'duracionMinutos' => $duracionPorServicio[$linea->servicioId] ?? 30,
                ]);
            }
        });

        // Aplicar la duración específica según el tipo de vehículo.
        $duracionesPorTipo = DB::table('precio_servicios')
            ->get(['servicio_id', 'tipo_vehiculo_id', 'duracionMinutos'])
            ->keyBy(fn ($precio) => $precio->servicio_id.'-'.$precio->tipo_vehiculo_id);
        $tipoVehiculoPorId = DB::table('vehiculos')->pluck('tipoVehiculoId', 'id');

        DB::table('agenda_servicios')->orderBy('id')->chunk(200, function ($lineas) use ($duracionesPorTipo, $tipoVehiculoPorId): void {
            foreach ($lineas as $linea) {
                $tipoVehiculoId = $tipoVehiculoPorId[$linea->vehiculoId] ?? null;
                $duracion = $duracionesPorTipo[$linea->servicioId.'-'.$tipoVehiculoId]->duracionMinutos ?? null;

                if ($duracion !== null) {
                    DB::table('agenda_servicios')->where('id', $linea->id)
                        ->update(['duracionMinutos' => $duracion]);
                }
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('agenda_servicios', 'duracionMinutos')) {
            Schema::table('agenda_servicios', function (Blueprint $table) {
                $table->dropColumn('duracionMinutos');
            });
        }

        if (Schema::hasColumn('precio_servicios', 'duracionMinutos')) {
            Schema::table('precio_servicios', function (Blueprint $table) {
                $table->dropColumn('duracionMinutos');
            });
        }
    }
};
