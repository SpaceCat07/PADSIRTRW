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

    // Relasi ke model Proker
    public function proker()
    {
        return $this->belongsTo(Proker::class, 'id_proker'); // id_proker adalah foreign key di detail_proker_rt
    }

    // Relasi ke model RWModel
    public function rw()
    {
        return $this->belongsTo(RWModel::class, 'id_rw'); // id_rw adalah foreign key di detail_proker_rt
    }

    // public $timestamps = false;
}
