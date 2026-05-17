<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PendingUserVerifications', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Email', 120)->unique();
            $table->string('Password', 255);
            $table->string('OtpCode', 255);
            $table->timestamp('ExpiresAt');
            $table->timestamp('CreatedAt')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PendingUserVerifications');
    }
};
