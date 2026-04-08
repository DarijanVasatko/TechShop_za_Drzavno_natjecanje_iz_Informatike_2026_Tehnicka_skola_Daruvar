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
        Schema::create('detalji_narudzbe', function (Blueprint $table) {
    $table->id('DetaljiNarudzbe_ID');
    $table->unsignedBigInteger('Narudzba_ID');
    $table->foreign('Narudzba_ID')->references('Narudzba_ID')->on('narudzba');
    $table->unsignedBigInteger('Proizvod_ID');
    $table->foreign('Proizvod_ID')->references('Proizvod_ID')->on('proizvod')->cascadeOnDelete();
    $table->integer('Kolicina');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalji_narudzbe');
    }
};
