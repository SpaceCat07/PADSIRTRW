<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganRT extends Model
{
    use HasFactory;

    protected $table = 'keuangan_rt';

    protected $fillable = [
        'id_rt',
        'jenis',
        'jumlah',
        'path_file',
        'keterangan',
        'tanggal',
    ];

    public function rt()
    {
        return $this->belongsTo(RTModel::class, 'id_rt', 'id_rt');
    }
}
