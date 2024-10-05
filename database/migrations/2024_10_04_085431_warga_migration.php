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
        Schema::create('warga', function (Blueprint $table) {
            $table -> unsignedBigInteger('id_warga')->autoIncrement();
            $table -> String('nama');
            $table -> String('email');
            $table -> integer('no_hp');
            $table -> String('username');
            $table -> String('password');
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
