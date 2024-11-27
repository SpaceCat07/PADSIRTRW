<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RTModel extends Model
{
    use HasFactory;

    protected $table = 'rt';

    protected $fillable = [
        'id_rw',
        'nama_rt',
        'nomor_rekening'
    ];
}
