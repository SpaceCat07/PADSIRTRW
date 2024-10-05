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
        Schema::create('penjabat_rw', function (Blueprint $table) {
            $table -> unsignedBigInteger('id_penjabat_rw') -> autoIncrement();
            $table -> unsignedBigInteger('id_rw');
            $table -> String('nama');
            $table -> String('email');
            $table -> integer('no_hp');
            $table -> String('username');
            $table -> String('password');
            $table -> enum('role', ['Admin_RW', 'Ketua_RW']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjabat_rw');
    }
};
