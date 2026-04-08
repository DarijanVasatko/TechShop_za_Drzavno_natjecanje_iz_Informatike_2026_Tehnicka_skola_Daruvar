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
        Schema::create('pc_configuration_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('configuration_id');
            $table->unsignedBigInteger('component_type_id');
            $table->unsignedBigInteger('proizvod_id');
            $table->decimal('cijena_u_trenutku', 10, 2);
            $table->timestamps();

            $table->foreign('configuration_id')
                  ->references('id')
                  ->on('pc_configurations')
                  ->onDelete('cascade');

            $table->foreign('component_type_id')
                  ->references('id')
                  ->on('pc_component_types')
                  ->onDelete('cascade');

            $table->foreign('proizvod_id')
                  ->references('Proizvod_ID')
                  ->on('proizvod')
                  ->onDelete('cascade');

            $table->unique(['configuration_id', 'component_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_configuration_items');
    }
};
