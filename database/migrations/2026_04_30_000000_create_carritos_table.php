<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('Carritos')) {
            return;
        }

        Schema::create('Carritos', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('UsuarioId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Carritos');
    }
};
