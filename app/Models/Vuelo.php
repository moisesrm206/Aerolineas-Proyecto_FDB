<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vuelo extends Model
{
    use HasFactory;
    protected $table = 'vuelo';
    protected $primaryKey = 'id_vuelo';
    public $timestamps = false;

    protected $fillable = [
        'id_ruta',
        'id_aeronave',
        'salida_planificada',
        'llegada_planificada',
        'estado',
    ];

    protected $casts = [
        'salida_planificada' => 'datetime',
        'llegada_planificada' => 'datetime',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta', 'id_ruta');
    }

    public function aeronave()
    {
        return $this->belongsTo(Aeronave::class, 'id_aeronave', 'id_aeronave');
    }

    public function tripulacionVuelo()
    {
        return $this->hasMany(TripulacionVuelo::class, 'id_vuelo', 'id_vuelo');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_vuelo', 'id_vuelo');
    }
}