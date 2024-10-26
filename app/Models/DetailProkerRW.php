<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProkerRW extends Model
{
    use HasFactory;

    protected $table = 'detail_proker_rw';

    protected $fillable = [
        'id_proker',
        'id_rw'
    ];
}
