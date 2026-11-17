<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->decimal('porcentajeComision', 5, 2)
                ->default(0);
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('empleadoId')
                ->nullable()
                ->constrained('empleados')
                ->nullOnDelete();

            $table->decimal('porcentajeComision', 5, 2)
                ->default(0);

            $table->decimal('valorComision', 10, 2)
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['empleadoId']);
            $table->dropColumn([
                'empleadoId',
                'porcentajeComision',
                'valorComision',
            ]);
        });

        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn('porcentajeComision');
        });
    }
};