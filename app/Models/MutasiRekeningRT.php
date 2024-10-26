<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiRekeningRT extends Model
{
    use HasFactory;

    protected $table = 'mutasi_rekening_rt';

    protected $fillable = [
        'id_rekening_rt',
        'jenis',
        'jumlah',
        'saldo_awal',
        'saldo_akhir',
        'keterangan'
    ];
}
