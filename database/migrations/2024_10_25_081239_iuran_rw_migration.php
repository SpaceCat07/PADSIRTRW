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
        Schema::create('iuran_rw', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_rw') -> constrained('rw');
            $table -> string('nama_iuran');
            $table -> float('total_iuran');
            $table -> integer('tanggal_pembayaran');
            $table -> enum('jenis_iuran', ['bulanan', 'tambahan']);
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_rw');
    }
};
