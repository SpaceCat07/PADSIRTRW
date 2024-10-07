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
            $table -> unsignedBigInteger('id_penjabat_rt') -> autoIncrement();
            $table -> unsignedBigInteger('id_rt');
            $table -> unsignedBigInteger('id_warga');
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
