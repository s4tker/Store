<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('PasswordResets')) {
            return;
        }

        Schema::create('PasswordResets', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Correo', 120)->index();
            $table->string('Token', 255);
            $table->timestamp('CreatedAt')->useCurrent();
        });
    }

    public function down(): void
    {
        //
    }
};
