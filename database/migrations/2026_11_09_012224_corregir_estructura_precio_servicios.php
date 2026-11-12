<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('precio_servicios', 'modalidad')) {
            Schema::table('precio_servicios', function (Blueprint $table) {
                $table->string('modalidad')->default('fijo');
            });
        }

        if (!Schema::hasColumn('precio_servicios', 'duracionMinutos')) {
            Schema::table('precio_servicios', function (Blueprint $table) {
                $table->unsignedSmallInteger('duracionMinutos')->default(30);
            });
        }

        Schema::table('precio_servicios', function (Blueprint $table) {
            $table->decimal('precio', 10, 2)->nullable()->change();
        });

        // Los precios nulos corresponden a servicios por convenir.
        DB::table('precio_servicios')
            ->whereNull('precio')
            ->update(['modalidad' => 'convenir']);

        // Estos servicios tienen modalidad "desde".
        $serviciosDesde = DB::table('servicios')
            ->whereIn('nombre', [
                'Lavado de motor a vapor',
                'Enceradas',
            ])
            ->select('id');

        DB::table('precio_servicios')
            ->whereIn('servicio_id', $serviciosDesde)
            ->update(['modalidad' => 'desde']);
    }

    public function down(): void
    {
        Schema::table('precio_servicios', function (Blueprint $table) {
            $table->dropColumn(['modalidad', 'duracionMinutos']);
        });
    }
};
