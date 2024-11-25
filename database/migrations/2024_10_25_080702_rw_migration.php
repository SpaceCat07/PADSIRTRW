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
        Schema::create('rw', function(Blueprint $table) {
            // $table -> unsignedBigInteger('id_rw') -> primary() -> nullable(false);
            $table -> id();
            $table -> string('nama_rw') -> nullable(false);
            $table -> integer('nomer_rekening');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rw');
    }
};
