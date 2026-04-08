<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('narudzba_id');
            $table->string('provider')->default('fakepay');
            $table->string('reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('narudzba_id')
                ->references('Narudzba_ID')
                ->on('narudzba')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
