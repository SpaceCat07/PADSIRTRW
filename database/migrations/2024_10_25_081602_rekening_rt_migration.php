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
        Schema::create('rekening_rt', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_rt') -> constrained('rt');
            $table -> integer('nomor_rekening');
            $table -> float('saldo');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_rt');
    }
};
