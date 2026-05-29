<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripulacionVuelo extends Model
{
    protected $table = 'tripulacion_vuelo';
    protected $primaryKey = 'id_tripvuelo';
    public $timestamps = false;

    protected $fillable = [
        'id_vuelo',
        'id_tripulacion',
        'id_rol',
    ];

    public function vuelo()
    {
        return $this->belongsTo(Vuelo::class, 'id_vuelo', 'id_vuelo');
    }

    public function tripulacion()
    {
        return $this->belongsTo(Tripulacion::class, 'id_tripulacion', 'id_tripulacion');
    }

    public function rol()
    {
        return $this->belongsTo(RolTripulacion::class, 'id_rol', 'id_rol');
    }
}