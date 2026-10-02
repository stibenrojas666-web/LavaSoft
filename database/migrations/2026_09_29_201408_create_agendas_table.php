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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleadoId')->nullable();
            $table->unsignedBigInteger('clienteId');
            $table->unsignedBigInteger('vehiculoId');
            $table->date('fecha');
            $table->time('hora');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada']);
            $table->enum('tipoDeAtencion', ['Por orden de llegada','Por cita agendada']);
            $table->foreign('empleadoId')->references('id')->on('empleados');
            $table->foreign('clienteId')->references('id')->on('clientes');
            $table->foreign('vehiculoId')->references('id')->on('vehiculos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');

    }
};
