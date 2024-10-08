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
        Schema::create('pengguna', function (Blueprint $table) {
            $table -> unsignedBigInteger('id_pengguna');
            $table -> String('nama');
            $table -> String('email');
            $table -> String('no_hp');
            $table -> String('password') -> default(Hash::make('masukhantam'));
            $table -> enum('role', ['Ketua_RT', 'Ketua_RW', 'Admin_RT', 'Admin_RW', 'Super_Admin', 'Warga']);
            $table -> enum('aktivasi', ['Activated', 'Unactivated']) -> default('Activated');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
