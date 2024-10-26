<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KritikSaranRT extends Model
{
    use HasFactory;

    protected $table = 'kritik_saran_rt';

    protected $fillable = [
        'id_rt',
        'id_pengguna',
        'isi',
        'status'
    ];
}
