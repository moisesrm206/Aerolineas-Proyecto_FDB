<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'ruta';
    protected $primaryKey = 'id_ruta';
    public $timestamps = false;

    protected $fillable = [
        'id_aeropuerto_origen',
        'id_aeropuerto_destino',
        'distancia_km',
    ];

    public function aeropuertoOrigen()
    {
        return $this->belongsTo(Aeropuerto::class, 'id_aeropuerto_origen', 'id_aeropuerto');
    }

    public function aeropuertoDestino()
    {
        return $this->belongsTo(Aeropuerto::class, 'id_aeropuerto_destino', 'id_aeropuerto');
    }

    public function vuelos()
    {
        return $this->hasMany(Vuelo::class, 'id_ruta', 'id_ruta');
    }
}