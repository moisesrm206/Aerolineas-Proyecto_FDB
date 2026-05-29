<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table = 'mantenimiento';
    protected $primaryKey = 'id_mantenimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_aeronave',
        'fecha',
        'tipo',
        'responsable',
    ];

    public function aeronave()
    {
        return $this->belongsTo(Aeronave::class, 'id_aeronave', 'id_aeronave');
    }
}