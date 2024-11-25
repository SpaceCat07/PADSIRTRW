<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranRW extends Model
{
    use HasFactory;

    protected $table = 'iuran_rw';

    protected $fillable = [
        'id_rw',
        'nama_iuran',
        'total_iuran',
        'bulan',
        'jenis_iuran'
    ];
}
