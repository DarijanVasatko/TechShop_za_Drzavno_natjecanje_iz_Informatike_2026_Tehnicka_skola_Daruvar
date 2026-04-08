<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->timestamps();
        });

        Schema::table('proizvod', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('Tip_ID');
            
            $table->foreign('brand_id')
                  ->references('id')
                  ->on('brands')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('proizvod', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });
        Schema::dropIfExists('brands');
    }
};
