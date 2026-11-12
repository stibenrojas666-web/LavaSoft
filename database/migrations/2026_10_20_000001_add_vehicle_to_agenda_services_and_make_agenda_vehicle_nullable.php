<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('agenda_servicios', 'vehiculoId')) {
            Schema::table('agenda_servicios', function (Blueprint $table) {
                $table->unsignedBigInteger('vehiculoId')->nullable()->after('agendaId');
            });
        }

        DB::table('agenda_servicios')
            ->whereNull('vehiculoId')
            ->orderBy('id')
            ->chunkById(200, function ($lineas): void {
                foreach ($lineas as $linea) {
                    $vehiculoId = DB::table('agendas')
                        ->where('id', $linea->agendaId)
                        ->value('vehiculoId');

                    if ($vehiculoId !== null) {
                        DB::table('agenda_servicios')
                            ->where('id', $linea->id)
                            ->update(['vehiculoId' => $vehiculoId]);
                    }
                }
            });

        if (DB::table('agenda_servicios')->whereNull('vehiculoId')->exists()) {
            throw new RuntimeException('No se pudo asignar un vehículo a todas las líneas de agenda existentes.');
        }

        $indiceAgendaExiste = collect(Schema::getIndexes('agenda_servicios'))
            ->contains(fn (array $indice) => $indice['name'] === 'agenda_servicios_agendaid_index');

        if (!$indiceAgendaExiste) {
            Schema::table('agenda_servicios', function (Blueprint $table) {
                // MySQL usa el índice único anterior para respaldar la FK agendaId.
                // Creamos un índice solo para agendaId antes de reemplazarlo.
                $table->index('agendaId');
            });
        }

        $indices = collect(Schema::getIndexes('agenda_servicios'));
        $indiceAnterior = $indices->first(fn (array $indice) =>
            $indice['columns'] === ['agendaId', 'servicioId']
        );
        if ($indiceAnterior !== null) {
            Schema::table('agenda_servicios', function (Blueprint $table) use ($indiceAnterior): void {
                $table->dropUnique($indiceAnterior['name']);
            });
        }

        Schema::table('agenda_servicios', function (Blueprint $table): void {
            $table->foreign('vehiculoId')->references('id')->on('vehiculos');
            $table->unsignedBigInteger('vehiculoId')->nullable(false)->change();
            $table->unique(['agendaId', 'vehiculoId', 'servicioId']);
        });

        Schema::table('agendas', function (Blueprint $table) {
            $table->unsignedBigInteger('vehiculoId')->nullable()->change();
        });
    }

    public function down(): void
    {
        $hayCitasConVariosVehiculos = DB::table('agenda_servicios')
            ->select('agendaId')
            ->groupBy('agendaId')
            ->havingRaw('COUNT(DISTINCT vehiculoId) > 1')
            ->exists();

        if ($hayCitasConVariosVehiculos) {
            throw new RuntimeException('No se puede revertir: hay citas con varios vehículos que el esquema anterior no puede representar.');
        }

        DB::table('agenda_servicios')
            ->select('agendaId')
            ->selectRaw('MIN(vehiculoId) as vehiculoId')
            ->groupBy('agendaId')
            ->orderBy('agendaId')
            ->chunk(200, function ($lineas): void {
                foreach ($lineas as $linea) {
                    DB::table('agendas')
                        ->where('id', $linea->agendaId)
                        ->whereNull('vehiculoId')
                        ->update(['vehiculoId' => $linea->vehiculoId]);
                }
            });

        if (DB::table('agendas')->whereNull('vehiculoId')->exists()) {
            throw new RuntimeException('No se puede revertir: hay citas sin vehículo que no pueden representarse en el esquema anterior.');
        }

        $indiceNuevo = collect(Schema::getIndexes('agenda_servicios'))->first(fn (array $indice) =>
            $indice['columns'] === ['agendaId', 'vehiculoId', 'servicioId']
        );
        if ($indiceNuevo !== null) {
            Schema::table('agenda_servicios', function (Blueprint $table) use ($indiceNuevo): void {
                $table->dropUnique($indiceNuevo['name']);
            });
        }

        Schema::table('agenda_servicios', function (Blueprint $table): void {
            $table->unique(['agendaId', 'servicioId']);
            $table->dropForeign(['vehiculoId']);
            $table->dropColumn('vehiculoId');
            $table->dropIndex(['agendaId']);
        });

        Schema::table('agendas', function (Blueprint $table) {
            $table->unsignedBigInteger('vehiculoId')->nullable(false)->change();
        });
    }
};
