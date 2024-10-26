<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KritikSaranRW extends Model
{
    use HasFactory;

    protected $table = 'kritik_saran_rw';

    protected $fillable = [
        'id_rw',
        'id_pengguna',
        'isi',
        'status'
    ];
}
