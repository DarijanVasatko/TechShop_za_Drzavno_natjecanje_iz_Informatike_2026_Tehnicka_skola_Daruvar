<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $hasPk = DB::select("
            SELECT COUNT(*) as cnt
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'kosarica'
              AND CONSTRAINT_TYPE = 'PRIMARY KEY'
        ")[0]->cnt;

        if (!$hasPk) {
            DB::statement('ALTER TABLE `kosarica` ADD PRIMARY KEY (`id`)');
        }

        $hasAutoInc = DB::select("
            SELECT COUNT(*) as cnt
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'kosarica'
              AND COLUMN_NAME = 'id'
              AND EXTRA LIKE '%auto_increment%'
        ")[0]->cnt;

        if (!$hasAutoInc) {
            DB::statement('ALTER TABLE `kosarica` MODIFY COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT');
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `kosarica` MODIFY COLUMN `id` INT(11) NOT NULL');
        DB::statement('ALTER TABLE `kosarica` DROP PRIMARY KEY');
    }
};
