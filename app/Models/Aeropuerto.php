<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aeropuerto extends Model
{
    protected $table = 'aeropuerto';
    protected $primaryKey = 'id_aeropuerto';
    public $timestamps = false;

    protected $fillable = [
        'codigo_iata',
        'nombre',
        'ciudad',
        'pais',
    ];

    public function puertas()
    {
        return $this->hasMany(PuertaEmbarque::class, 'id_aeropuerto', 'id_aeropuerto');
    }

    public function rutasOrigen()
    {
        return $this->hasMany(Ruta::class, 'id_aeropuerto_origen', 'id_aeropuerto');
    }

    public function rutasDestino()
    {
        return $this->hasMany(Ruta::class, 'id_aeropuerto_destino', 'id_aeropuerto');
    }
}