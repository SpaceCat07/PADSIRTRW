<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RWModel extends Model
{
    use HasFactory;

    protected $table = 'rw';

    protected $fillable = [
        'nama_rw',
        'nomer_rekening'
    ];
}
