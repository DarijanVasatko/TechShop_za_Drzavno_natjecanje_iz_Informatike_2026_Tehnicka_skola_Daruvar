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
        Schema::create('promo_kodovi', function (Blueprint $table) {
            $table->id();
            $table->string('kod')->unique();
            $table->enum('tip', ['postotak', 'fiksno'])->default('postotak');
            $table->decimal('vrijednost', 8, 2);
            $table->timestamp('vrijedi_od')->nullable();
            $table->timestamp('vrijedi_do')->nullable();
            $table->integer('max_koristenja')->nullable();
            $table->integer('koristenja')->default(0); 
            $table->decimal('minimalan_iznos', 8, 2)->nullable();
            $table->boolean('aktivno')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_kodovi');
    }
};