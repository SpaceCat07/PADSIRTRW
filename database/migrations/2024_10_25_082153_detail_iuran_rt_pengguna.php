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
        Schema::create('detail_iuran_rt_pengguna', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_iuran_rt') -> constrained('iuran_rt');
            $table -> foreignId('id_pengguna') -> constrained('pengguna');
            $table -> enum('status', ['belum','pending', 'selesai', 'gagal']) -> default('belum');
            $table -> bigInteger('nomor_rekening');
            $table -> string('bukti_pembayaran') -> default('default.jpg');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_iuran_rt_pengguna');
    }
};
