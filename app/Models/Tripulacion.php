<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tripulacion extends Model
{
    protected $table = 'tripulacion';
    protected $primaryKey = 'id_tripulacion';
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'id_user',
        'num_licencia',
    ];

    public function rol()
    {
        return $this->belongsTo(RolTripulacion::class, 'id_rol', 'id_rol');
    }

    public function asignaciones()
    {
        return $this->hasMany(TripulacionVuelo::class, 'id_tripulacion', 'id_tripulacion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}