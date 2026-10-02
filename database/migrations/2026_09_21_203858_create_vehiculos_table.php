<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Reference\Reference;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clienteID');
            $table->unsignedBigInteger('tipoVehiculoId');
            $table->string('placa');
            $table->string('modelo');
            $table->string('color');
            $table->string('estado');
            $table->foreign('clienteId')->references('id')->on('clientes');
            $table->foreign('tipoVehiculoId')->references('id')->on('tipo_vehiculos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
