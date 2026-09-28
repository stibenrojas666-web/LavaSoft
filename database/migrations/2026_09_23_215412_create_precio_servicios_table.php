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
        Schema::create('precio_servicios', function (Blueprint $table) {
            $table->id();
            $table->decimal('precio');
            $table->timestamps();
            $table->unsignedBigInteger('servicioid');
            $table->unsignedBigInteger('tipoVehiculoid');

            $table->foreign('servicioid')->references('id')->on('servicios');
            $table->foreign('tipoServicioid')->references('id')->on('tipo_vehiculos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio_servicios');
    }
};
