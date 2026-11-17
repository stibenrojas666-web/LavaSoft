<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_lavadero', function (Blueprint $table) {
            $table->boolean('mostrar_logo')->default(true)->after('logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_lavadero', function (Blueprint $table) {
            $table->dropColumn('mostrar_logo');
        });
    }
};
