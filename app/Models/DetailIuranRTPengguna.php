<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailIuranRTPengguna extends Model
{
    use HasFactory;

    protected $table = 'detail_iuran_rt_pengguna';

    protected $fillable = [
        'id_iuran_rt',
        'id_pengguna',
        'status',
        'nomor_rekening',
        'bukti_pembayaran'
    ];
}
