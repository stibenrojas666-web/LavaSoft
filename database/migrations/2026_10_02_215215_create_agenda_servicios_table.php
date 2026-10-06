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
        Schema::create('agenda_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agendaId');
            $table->unsignedBigInteger('servicioId');
            $table->decimal('Precio', 10, 2);
            $table->foreign('agendaId')->references('id')->on('agendas');
            $table->foreign('servicioId')->references('id')->on('servicios');
            $table->unique(['agendaId', 'servicioId']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_servicios');
    }
};
