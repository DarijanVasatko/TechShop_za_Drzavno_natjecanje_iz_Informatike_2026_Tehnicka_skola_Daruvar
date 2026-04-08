<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pc_configuration_items', function (Blueprint $table) {
            $table->unsignedSmallInteger('kolicina')->default(1)->after('cijena_u_trenutku');
        });
    }

    public function down(): void
    {
        Schema::table('pc_configuration_items', function (Blueprint $table) {
            $table->dropColumn('kolicina');
        });
    }
};
