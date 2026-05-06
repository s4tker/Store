<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('Pedidos')) {
            return;
        }

        Schema::create('Pedidos', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('UsuarioId');
            $table->unsignedBigInteger('DireccionId');
            $table->decimal('Total', 14, 2);
            $table->string('Estado', 50)->default('pendiente');
            $table->timestamp('CreatedAt')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Pedidos');
    }
};
