<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailIuranRWRT extends Model
{
    use HasFactory;

    protected $table = 'detail_iuran_rw_rt';

    protected $fillable = [
        'id_rt',
        'id_iuran_rw',
        'status',
        'nomer_rekening',
        'bukti_pembayaran'
    ];
}
