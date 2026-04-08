<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'kosarica'
            AND COLUMN_NAME = 'proizvod_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (!empty($foreignKeys)) {
            Schema::table('kosarica', function (Blueprint $table) use ($foreignKeys) {
                foreach ($foreignKeys as $fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                }
            });
        }

        DB::statement('ALTER TABLE kosarica MODIFY proizvod_id INT(11) NOT NULL');

        DB::statement('DELETE FROM kosarica WHERE proizvod_id NOT IN (SELECT Proizvod_ID FROM proizvod)');

        Schema::table('kosarica', function (Blueprint $table) {
            $table->foreign('proizvod_id')
                  ->references('Proizvod_ID')
                  ->on('proizvod')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('kosarica', function (Blueprint $table) {
            $table->dropForeign(['proizvod_id']);
        });

        DB::statement('ALTER TABLE kosarica MODIFY proizvod_id INT(11) NOT NULL');
    }
};
