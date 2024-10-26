<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningRT extends Model
{
    use HasFactory;

    protected $table = 'rekening_rt';

    protected $fillable = [
        'id_rt',
        'nomor_rekening',
        'saldo'
    ];
}
