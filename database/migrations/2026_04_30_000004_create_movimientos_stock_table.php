<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('MovimientosStock', function (Blueprint $table) {
            $table->unsignedBigInteger('VarianteId');
            $table->string('Tipo', 50);
            $table->unsignedInteger('Cantidad');
            $table->string('Motivo', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MovimientosStock');
    }
};
