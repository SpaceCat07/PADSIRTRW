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
        Schema::create('penjabat_rt', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_pengguna') -> constrained('pengguna');
            $table -> foreignId('id_rt') -> constrained('rt');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjabat_rt');
    }
};
