<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganRW extends Model
{
    use HasFactory;

    protected $table = 'keuangan_rw';

    protected $fillable = [
        'id_rw',
        'jenis',
        'jumlah',
        'path_file',
        'keterangan'
    ];

    public function rw()
    {
        return $this->belongsTo(RWModel::class, 'id_rw', 'id_rw');
    }
}
