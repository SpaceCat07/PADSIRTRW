<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;
    
    protected $table = 'warga';
    protected $fillable = [
        'id_warga',
        'name',
        'alamat'
    ];

    protected $primaryKey = 'id_warga';
}