<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tip_proizvoda', function (Blueprint $table) {
            $table->unsignedBigInteger('kategorija_id')->nullable()->after('naziv_tip');

            $table->foreign('kategorija_id')
                ->references('id_kategorija')
                ->on('kategorija')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tip_proizvoda', function (Blueprint $table) {
            $table->dropForeign(['kategorija_id']);
            $table->dropColumn('kategorija_id');
        });
    }
};
