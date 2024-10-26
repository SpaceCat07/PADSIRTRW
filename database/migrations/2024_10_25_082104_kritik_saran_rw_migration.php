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
        Schema::create('kritik_saran_rw', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_rw') -> constrained('rw');
            $table -> foreignId('id_pengguna') -> constrained('pengguna');
            $table -> text('isi');
            $table -> enum('status', ['dilihat', 'diproses', 'selesai']);
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kritik_saran_rw');
    }
};
