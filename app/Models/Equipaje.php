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
        'tipo_equipaje',
        'etiqueta',
        'peso_kg',
        'estado',
    ];

    protected $casts = [
        'tipo_equipaje' => 'string',
        'estado' => 'string',
        'peso_kg' => 'decimal:2',
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