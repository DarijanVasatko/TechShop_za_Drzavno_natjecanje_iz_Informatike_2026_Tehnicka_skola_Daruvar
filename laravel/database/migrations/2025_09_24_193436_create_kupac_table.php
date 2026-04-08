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
        Schema::create('kupac', function (Blueprint $table) {
    $table->id('Kupac_ID');
    $table->string('Ime', 50);
    $table->string('Prezime', 50);
    $table->string('Adresa', 50);
    $table->integer('KucniBroj');
    $table->integer('PostarskiBroj');
    $table->string('Drzava', 25);
    $table->string('Email', 100)->unique();
    $table->string('Username', 15);
    $table->string('Lozinka', 255);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kupac');
    }
};
