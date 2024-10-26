<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProkerRT extends Model
{
    use HasFactory;

    protected $table = 'detail_proker_rt';

    protected $fillable = [
        'id_proker',
        'id_rt'
    ];
}
