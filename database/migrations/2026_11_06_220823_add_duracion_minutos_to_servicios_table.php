
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cada combinación de servicio y vehículo tendrá su duración.
        Schema::table('precio_servicios', function (Blueprint $table) {
            $table->unsignedSmallInteger('duracionMinutos')
                ->default(30)
                ->after('precio');
        });

        // Guardamos la duración aplicada a cada servicio de una cita.
        Schema::table('agenda_servicios', function (Blueprint $table) {
            $table->unsignedSmallInteger('duracionMinutos')
                ->default(30)
                ->after('Precio');
        });

        // Conservamos la duración general que ya tenía cada servicio
        // como valor inicial de las configuraciones existentes.
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

        // Para las citas antiguas usamos inicialmente la duración
        // general que tenía registrado el servicio.
        DB::table('agenda_servicios')->orderBy('id')->chunk(200, function ($lineas) use ($duracionPorServicio): void {
            foreach ($lineas as $linea) {
                DB::table('agenda_servicios')->where('id', $linea->id)->update([
                    'duracionMinutos' => $duracionPorServicio[$linea->servicioId] ?? 30,
                ]);
            }
        });

        // Si existe una configuración específica para el tipo de
        // vehículo, actualizamos la duración histórica con ese valor.
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
        Schema::table('agenda_servicios', function (Blueprint $table) {
            $table->dropColumn('duracionMinutos');
        });

        Schema::table('precio_servicios', function (Blueprint $table) {
            $table->dropColumn('duracionMinutos');
        });
    }
};
