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
        Schema::create('kosarica', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('korisnik_id');
    $table->foreign('korisnik_id')->references('Kupac_ID')->on('kupac');
    $table->unsignedBigInteger('proizvod_id');
    $table->foreign('proizvod_id')->references('Proizvod_ID')->on('proizvod');
    $table->integer('kolicina')->default(1);
    $table->timestamp('datum_dodavanja')->useCurrent();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosarica');
    }
};
