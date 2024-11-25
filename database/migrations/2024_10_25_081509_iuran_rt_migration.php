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
        Schema::create('iuran_rt', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_rt') -> constrained('rt');
            $table -> string('nama_iuran');
            $table -> enum('bulan',  ['January','February','March','April','May','June','July','August','September','October','November','December']);
            $table -> decimal('total_iuran',30, 3);
            $table -> enum('jenis_iuran', ['bulanan', 'tambahan']);
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_rt');
    }
};
