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
            AND COLUMN_NAME = 'korisnik_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (!empty($foreignKeys)) {
            Schema::table('kosarica', function (Blueprint $table) use ($foreignKeys) {
                foreach ($foreignKeys as $fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                }
            });
        }

        DB::statement('ALTER TABLE kosarica MODIFY korisnik_id BIGINT UNSIGNED NOT NULL');

        DB::statement('DELETE FROM kosarica WHERE korisnik_id NOT IN (SELECT id FROM users)');

        Schema::table('kosarica', function (Blueprint $table) {
            $table->foreign('korisnik_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('kosarica', function (Blueprint $table) {
            $table->dropForeign(['korisnik_id']);
        });

        DB::statement('ALTER TABLE kosarica MODIFY korisnik_id INT(11) NOT NULL');
    }
};
