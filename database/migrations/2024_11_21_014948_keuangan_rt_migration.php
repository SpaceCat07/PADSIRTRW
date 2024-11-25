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
        Schema::create('keuangan_rt', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_rt') -> constrained('rt');
            $table -> enum('jenis', ['D', 'K']);
            // $table -> float('jumlah');
            $table->decimal('jumlah', 30, 3);
            $table -> string('path_file');
            $table -> string('keterangan');
            $table -> date('tanggal');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_rt');
    }
};