<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_lavadero', function (Blueprint $table) {
            $table->time('apertura_regular')->default('07:00:00');
            $table->time('cierre_regular')->default('18:00:00');
            $table->time('apertura_domingo_festivo')->default('08:00:00');
            $table->time('cierre_domingo_festivo')->default('13:00:00');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_lavadero', function (Blueprint $table) {
            $table->dropColumn([
                'apertura_regular', 'cierre_regular',
                'apertura_domingo_festivo', 'cierre_domingo_festivo',
            ]);
        });
    }
};
