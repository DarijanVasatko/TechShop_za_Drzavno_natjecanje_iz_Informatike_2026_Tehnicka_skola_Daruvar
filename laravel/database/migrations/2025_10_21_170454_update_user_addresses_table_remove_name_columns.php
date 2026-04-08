<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('user_addresses', function (Blueprint $table) {
        $table->dropColumn(['ime', 'prezime', 'telefon']);
    });
}

public function down(): void
{
    Schema::table('user_addresses', function (Blueprint $table) {
        $table->string('ime')->nullable();
        $table->string('prezime')->nullable();
        $table->string('telefon')->nullable();
    });
}

};
