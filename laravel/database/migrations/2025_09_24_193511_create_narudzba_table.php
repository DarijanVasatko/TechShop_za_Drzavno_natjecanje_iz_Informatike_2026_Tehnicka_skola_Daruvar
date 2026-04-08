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
        Schema::create('narudzba', function (Blueprint $table) {
    $table->id('Narudzba_ID');
    $table->unsignedBigInteger('Kupac_ID')->nullable();
    $table->foreign('Kupac_ID')->references('Kupac_ID')->on('kupac');
    $table->unsignedBigInteger('NacinPlacanja_ID')->nullable();
    $table->foreign('NacinPlacanja_ID')->references('NacinPlacanja_ID')->on('nacin_placanja');
    $table->date('Datum_narudzbe');
    $table->decimal('Ukupni_iznos', 10, 2);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('narudzba');
    }
};
