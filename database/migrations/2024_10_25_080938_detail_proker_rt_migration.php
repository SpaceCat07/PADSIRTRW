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
        Schema::create('detail_proker_rt', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('id_proker') -> constrained('proker');
            $table -> foreignId('id_rt') -> constrained('rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_proker_rt');
    }
};
