<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipaje extends Model
{
    protected $table = 'equipaje';
    protected $primaryKey = 'id_equipaje';
    public $timestamps = false;

    protected $fillable = [
        'id_pasajero',
        'id_boleto',
        'peso_kg',
        'estado',
    ];

    public function pasajero()
    {
        return $this->belongsTo(Pasajero::class, 'id_pasajero', 'id_pasajero');
    }

    public function boleto()
    {
        return $this->belongsTo(Boleto::class, 'id_boleto', 'id_boleto');
    }
}