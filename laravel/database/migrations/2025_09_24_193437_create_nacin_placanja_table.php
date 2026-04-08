<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('nacin_placanja')) {
            return;
        }

        Schema::create('nacin_placanja', function (Blueprint $table) {
            $table->id('NacinPlacanja_ID');
            $table->string('Opis', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nacin_placanja');
    }
};
