<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolTripulacion extends Model
{
    protected $table = 'rol_tripulacion';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre_rol',
        'descripcion',
    ];

    public function tripulaciones()
    {
        return $this->hasMany(Tripulacion::class, 'id_rol', 'id_rol');
    }

    public function asignaciones()
    {
        return $this->hasMany(TripulacionVuelo::class, 'id_rol', 'id_rol');
    }
}