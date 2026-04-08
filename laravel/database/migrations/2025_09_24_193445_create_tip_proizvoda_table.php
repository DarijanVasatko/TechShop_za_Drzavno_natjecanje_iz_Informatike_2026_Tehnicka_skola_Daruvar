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
        if (Schema::hasTable('tip_proizvoda')) {
            return;
        }

        Schema::create('tip_proizvoda', function (Blueprint $table) {
            $table->id('id_tip');
            $table->string('naziv_tip', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tip_proizvoda');
    }
};
