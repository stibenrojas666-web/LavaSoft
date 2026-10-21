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

        DB::table('agenda_servicios as linea')
            ->join('agendas as agenda', 'agenda.id', '=', 'linea.agendaId')
            ->whereNull('linea.vehiculoId')
            ->update(['linea.vehiculoId' => DB::raw('agenda.vehiculoId')]);

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

        Schema::table('agenda_servicios', function (Blueprint $table) {
            $table->dropUnique(['agendaId', 'servicioId']);
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

        DB::table('agendas as agenda')
            ->joinSub(
                DB::table('agenda_servicios')
                    ->select('agendaId', DB::raw('MIN(vehiculoId) as vehiculoId'))
                    ->groupBy('agendaId'),
                'linea',
                'linea.agendaId',
                '=',
                'agenda.id'
            )
            ->whereNull('agenda.vehiculoId')
            ->update(['agenda.vehiculoId' => DB::raw('linea.vehiculoId')]);

        if (DB::table('agendas')->whereNull('vehiculoId')->exists()) {
            throw new RuntimeException('No se puede revertir: hay citas sin vehículo que no pueden representarse en el esquema anterior.');
        }

        Schema::table('agenda_servicios', function (Blueprint $table) {
            $table->unique(['agendaId', 'servicioId']);
        });

        Schema::table('agenda_servicios', function (Blueprint $table) {
            $table->dropUnique(['agendaId', 'vehiculoId', 'servicioId']);
            $table->dropForeign(['vehiculoId']);
            $table->dropColumn('vehiculoId');
            $table->dropIndex(['agendaId']);
        });

        Schema::table('agendas', function (Blueprint $table) {
            $table->unsignedBigInteger('vehiculoId')->nullable(false)->change();
        });
    }
};
