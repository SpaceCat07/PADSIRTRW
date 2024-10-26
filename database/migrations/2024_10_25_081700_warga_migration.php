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
        Schema::create('warga', function (Blueprint $table) {
            $table -> unsignedBigInteger('id_warga') -> primary() -> nullable(false);
            $table -> foreignId('id_rt') -> constrained('rt');
            $table -> string('nama') -> nullable(false);
            $table -> text('alamat') -> nullable(false);
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};
