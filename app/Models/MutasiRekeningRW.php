<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiRekeningRW extends Model
{
    use HasFactory;

    protected $table = 'mutasi_rekening_rw';

    protected $fillable = [
        'id_rekening_rw',
        'jenis',
        'jumlah',
        'saldo_awal',
        'saldo_akhir',
        'keterangan'
    ];
}
