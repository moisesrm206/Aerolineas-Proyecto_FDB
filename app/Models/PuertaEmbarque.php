<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuertaEmbarque extends Model
{
    protected $table = 'puerta_embarque';
    protected $primaryKey = 'id_puerta';
    public $timestamps = false;

    protected $fillable = [
        'id_aeropuerto',
        'nombre_puerta',
        'terminal',
    ];

    public function aeropuerto()
    {
        return $this->belongsTo(Aeropuerto::class, 'id_aeropuerto', 'id_aeropuerto');
    }

    public function abordajes()
    {
        return $this->hasMany(Abordaje::class, 'id_puerta', 'id_puerta');
    }
}