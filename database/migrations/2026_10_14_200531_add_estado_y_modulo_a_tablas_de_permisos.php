<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tablas = config('permission.table_names');

        Schema::table($tablas['roles'], function (Blueprint $table) {
            $table->boolean('activo')->default(true);
        });

        Schema::table($tablas['permissions'], function (Blueprint $table) {
            $table->string('modulo')->nullable()->index();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablas = config('permission.table_names');

        Schema::table($tablas['permissions'], function (Blueprint $table) {
            $table->dropIndex(['modulo']);
            $table->dropColumn('modulo');
        });

        Schema::table($tablas['roles'], function (Blueprint $table) {
            $table->dropColumn('activo');
        });

    }
};
