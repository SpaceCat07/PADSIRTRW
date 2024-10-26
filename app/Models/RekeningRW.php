<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningRW extends Model
{
    use HasFactory;

    protected $table = 'rekening_rw';

    protected $fillable = [
        'id_rw',
        'nomor_rekening',
        'saldo'
    ];
}
