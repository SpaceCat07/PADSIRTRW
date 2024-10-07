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
        Schema::create('users', function (Blueprint $table) {
            $table -> unsignedBigInteger('id_warga')->autoIncrement();
            $table -> String('nama');
            $table -> String('email');
            $table -> String('no_hp');
            $table -> String('username');
            $table -> String('password');
            $table -> enum('role', ['Ketua_RT', 'Ketua_RW', 'Admin_RT', 'Admin_RW', 'Super_Admin', 'Warga']);
            $table -> enum('aktivasi', ['Activated', 'Unactivated']) -> default('Unactivated');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};
