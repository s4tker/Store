<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Usuarios', function (Blueprint $table) {
            // agrega campos si no existen
            if (!Schema::hasColumn('Usuarios', 'Dni')) {
                $table->string('Dni', 15)->nullable()->unique()->after('Telefono');
            }
            
            if (!Schema::hasColumn('Usuarios', 'Ruc')) {
                $table->string('Ruc', 15)->nullable()->unique()->after('Dni');
            }

            if (!Schema::hasColumn('Usuarios', 'RazonSocial')) {
                $table->string('RazonSocial', 255)->nullable()->after('Ruc');
            }
        });
    }

    public function down(): void
    {
        Schema::table('Usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('Usuarios', 'Dni')) {
                $table->dropUnique(['Dni']);
                $table->dropColumn('Dni');
            }
            
            if (Schema::hasColumn('Usuarios', 'Ruc')) {
                $table->dropUnique(['Ruc']);
                $table->dropColumn('Ruc');
            }

            if (Schema::hasColumn('Usuarios', 'RazonSocial')) {
                $table->dropColumn('RazonSocial');
            }
        });
    }
};
