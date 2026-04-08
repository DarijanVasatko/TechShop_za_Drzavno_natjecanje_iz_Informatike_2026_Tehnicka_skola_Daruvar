<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ime')->after('id')->nullable();
            $table->string('prezime')->after('ime')->nullable();
        });

        DB::table('users')->get()->each(function ($user) {
            if (!empty($user->name)) {
                $parts = explode(' ', $user->name, 2);
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'ime' => $parts[0] ?? null,
                        'prezime' => $parts[1] ?? null,
                    ]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->dropColumn(['ime', 'prezime']);
        });
    }
};
