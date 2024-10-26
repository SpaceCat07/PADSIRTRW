<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjabat_RT extends Model
{
    use HasFactory;

    protected $table = 'penjabat_rt';

    protected $fillable = [
        'id_pengguna',
        'id_rt'
    ];
}
