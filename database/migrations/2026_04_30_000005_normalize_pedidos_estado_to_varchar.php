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
                    WHEN '0' THEN 'pendiente'
                    WHEN '1' THEN 'pagado'
                    WHEN '2' THEN 'enviado'
                    WHEN '3' THEN 'entregado'
                    WHEN '4' THEN 'cancelado'
                    ELSE 'pendiente'
                END
            ");
        }

        DB::statement("
            UPDATE `Pedidos`
            SET `Estado` = CASE LOWER(TRIM(`Estado`))
                WHEN 'pagado' THEN 'pagado'
                WHEN 'enviado' THEN 'enviado'
                WHEN 'entregado' THEN 'entregado'
                WHEN 'cancelado' THEN 'cancelado'
                ELSE 'pendiente'
            END
        ");

        DB::statement("
            ALTER TABLE `Pedidos`
            MODIFY `Estado` VARCHAR(50) {$nullable} DEFAULT 'pendiente'
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
                WHEN LOWER(`Estado`) = 'pendiente' THEN 'Pendiente'
                WHEN LOWER(`Estado`) = 'pagado' THEN 'Pagado'
                WHEN LOWER(`Estado`) = 'enviado' THEN 'Enviado'
                WHEN LOWER(`Estado`) = 'entregado' THEN 'Entregado'
                WHEN LOWER(`Estado`) = 'cancelado' THEN 'Cancelado'
                ELSE 'Pendiente'
            END
        ");

        DB::statement("
            ALTER TABLE `Pedidos`
            MODIFY `Estado` ENUM('Pendiente', 'Pagado', 'Enviado', 'Entregado', 'Cancelado') NOT NULL
        ");
    }
};
