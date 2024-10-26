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
        Schema::create('proker', function (Blueprint $table) {
            $table -> id();
            $table -> string('judul') -> nullable(false);
            $table -> text('isi') -> nullable(false);
            $table -> date('tanggal_mulai');
            $table -> date('tanggal_selesai');
            $table -> enum('status', ['on_progress', 'selesai']);
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proker');
    }
};
