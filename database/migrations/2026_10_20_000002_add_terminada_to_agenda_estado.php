<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'terminada'])->change();
        });
    }

    public function down(): void
    {
        DB::table('agendas')->where('estado', 'terminada')->update(['estado' => 'confirmada']);

        Schema::table('agendas', function (Blueprint $table) {
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada'])->change();
        });
    }
};
