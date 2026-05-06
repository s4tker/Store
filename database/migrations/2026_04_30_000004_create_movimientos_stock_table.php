<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('MovimientosStock')) {
            return;
        }

        Schema::create('MovimientosStock', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('VarianteId');
            $table->string('Tipo', 50);
            $table->unsignedInteger('Cantidad');
            $table->string('Motivo', 255);
            $table->timestamp('CreatedAt')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MovimientosStock');
    }
};
