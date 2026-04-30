<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('Pedidos') || ! Schema::hasColumn('Pedidos', 'Estado')) {
            return;
        }

        $column = DB::selectOne("
            SELECT DATA_TYPE, IS_NULLABLE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'Pedidos'
              AND COLUMN_NAME = 'Estado'
        ");

        if (! $column) {
            return;
        }

        $dataType = strtolower($column->DATA_TYPE);
        $nullable = strtoupper($column->IS_NULLABLE) === 'YES' ? 'NULL' : 'NOT NULL';

        if (in_array($dataType, ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'], true)) {
            DB::statement("
                UPDATE `Pedidos`
                SET `Estado` = CASE CAST(`Estado` AS CHAR)
                    WHEN '0' THEN 'Pendiente'
                    WHEN '1' THEN 'Pagado'
                    WHEN '2' THEN 'Enviado'
                    WHEN '3' THEN 'Entregado'
                    WHEN '4' THEN 'Cancelado'
                    ELSE 'Pendiente'
                END
            ");
        }

        DB::statement("
            ALTER TABLE `Pedidos`
            MODIFY `Estado` VARCHAR(50) {$nullable}
        ");
    }

    public function down(): void
    {
        if (! Schema::hasTable('Pedidos') || ! Schema::hasColumn('Pedidos', 'Estado')) {
            return;
        }

        DB::statement("
            UPDATE `Pedidos`
            SET `Estado` = CASE
                WHEN `Estado` IN ('Pendiente', 'Pagado', 'Enviado', 'Entregado', 'Cancelado') THEN `Estado`
                ELSE 'Pendiente'
            END
        ");

        DB::statement("
            ALTER TABLE `Pedidos`
            MODIFY `Estado` ENUM('Pendiente', 'Pagado', 'Enviado', 'Entregado', 'Cancelado') NOT NULL
        ");
    }
};
