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
        Schema::create('pc_component_types', function (Blueprint $table) {
            $table->id();
            $table->string('naziv', 100);
            $table->string('slug', 50)->unique();
            $table->integer('redoslijed')->default(0);
            $table->string('ikona', 50)->nullable();
            $table->boolean('obavezan')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_component_types');
    }
};
