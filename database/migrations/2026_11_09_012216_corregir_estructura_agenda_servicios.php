<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('agenda_servicios', 'vehiculoId')) {
            Schema::table('agenda_servicios', function (Blueprint $table) {
                $table->unsignedBigInteger('vehiculoId')->nullable();
            });
        }

        if (!Schema::hasColumn('agenda_servicios', 'duracionMinutos')) {
            Schema::table('agenda_servicios', function (Blueprint $table) {
                $table->unsignedSmallInteger('duracionMinutos')->default(30);
            });
        }

        $indices = collect(Schema::getIndexes('agenda_servicios'));
        $indiceAnterior = $indices->first(fn (array $indice) =>
            $indice['unique'] && $indice['columns'] === ['agendaId', 'servicioId']
        );
        if ($indiceAnterior !== null) {
            Schema::table('agenda_servicios', function (Blueprint $table) use ($indiceAnterior): void {
                $table->dropUnique($indiceAnterior['name']);
            });
        }

        $indices = collect(Schema::getIndexes('agenda_servicios'));
        $indiceNuevoExiste = $indices->contains(fn (array $indice) =>
            $indice['unique'] && $indice['columns'] === ['agendaId', 'vehiculoId', 'servicioId']
        );
        if (!$indiceNuevoExiste) {
            Schema::table('agenda_servicios', function (Blueprint $table): void {
                $table->unique(
                    ['agendaId', 'vehiculoId', 'servicioId'],
                    'agenda_servicios_agenda_vehiculo_servicio_unique'
                );
            });
        }
    }

    public function down(): void
    {
        Schema::table('agenda_servicios', function (Blueprint $table) {
            $table->dropUnique('agenda_servicios_agenda_vehiculo_servicio_unique');
            $table->dropForeign(['vehiculoId']);
            $table->dropColumn(['vehiculoId', 'duracionMinutos']);

            $table->unique(['agendaId', 'servicioId']);
        });
    }
};
