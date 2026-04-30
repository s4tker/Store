<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PedidoDetalles', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('PedidoId');
            $table->unsignedBigInteger('VarianteId');
            $table->unsignedInteger('Cantidad');
            $table->decimal('Precio', 12, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PedidoDetalles');
    }
};
