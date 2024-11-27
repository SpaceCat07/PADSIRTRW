<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranRT extends Model
{
    use HasFactory;

    protected $table = 'iuran_rt';

    protected $fillable = [
        'id_rt',
        'nama_iuran',
        'total_iuran',
        'bulan',
        'jenis_iuran'
    ];
}
