<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('country_town', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_town');
    }
};
