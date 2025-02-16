<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjabat_RW extends Model
{
    use HasFactory;

    protected $table = 'penjabat_rw';

    protected $fillable = [
        'id_pengguna',
        'id_rw'
    ];
}
