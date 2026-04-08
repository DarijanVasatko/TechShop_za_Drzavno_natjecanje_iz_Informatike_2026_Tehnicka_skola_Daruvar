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
        Schema::create('pc_component_specs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proizvod_id');
            $table->unsignedBigInteger('component_type_id');
            $table->string('socket_type', 50)->nullable(); 
            $table->string('ram_type', 20)->nullable(); 
            $table->string('form_factor', 20)->nullable(); 
            $table->integer('wattage')->nullable(); 
            $table->integer('tdp')->nullable(); 
            $table->timestamps();

            $table->foreign('proizvod_id')
                  ->references('Proizvod_ID')
                  ->on('proizvod')
                  ->onDelete('cascade');

            $table->foreign('component_type_id')
                  ->references('id')
                  ->on('pc_component_types')
                  ->onDelete('cascade');

            $table->unique(['proizvod_id', 'component_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_component_specs');
    }
};
