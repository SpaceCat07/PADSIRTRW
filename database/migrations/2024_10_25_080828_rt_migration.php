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
        Schema::create('rt', function(Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_rw') -> constrained('rw');
            $table -> string('nama_rt') -> nullable(false);
            $table -> bigInteger('nomor_rekening');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rt');
    }
};
