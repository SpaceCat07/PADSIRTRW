<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtModel extends Model
{
    use HasFactory;

    protected $table = 'rt';

    protected $fillable = [
        'nama_rw',
        'id_rw',
        'nama_rt'
    ];
}
