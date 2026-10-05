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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agendaId');
            $table->decimal('Monto', 10, 2);
            $table->enum('MetodoPago', ['Efectivo', 'Tarjeta', 'Transferencia']);
            $table->dateTime('FechaPago')->useCurrent();
            $table->boolean('RequerirFactura')->default(false);
            $table->foreign('agendaId')->references('id')->on('agendas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
