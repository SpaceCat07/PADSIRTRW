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

    // Relasi ke model Proker
    public function proker()
    {
        return $this->belongsTo(Proker::class, 'id_proker'); // id_proker adalah foreign key di detail_proker_rt
    }

    // Relasi ke model RTModel
    public function rt()
    {
        return $this->belongsTo(RTModel::class, 'id_rt'); // id_rt adalah foreign key di detail_proker_rt
    }

    // public $timestamps = false;
}
