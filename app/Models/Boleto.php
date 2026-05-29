<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    protected $table = 'boleto';
    protected $primaryKey = 'id_boleto';
    public $timestamps = false;

    protected $fillable = [
        'id_reserva',
        'numero_asiento',
        'clase',
        'precio',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva', 'id_reserva');
    }

    public function abordaje()
    {
        return $this->hasOne(Abordaje::class, 'id_boleto', 'id_boleto');
    }

    public function equipajes()
    {
        return $this->hasMany(Equipaje::class, 'id_boleto', 'id_boleto');
    }
}