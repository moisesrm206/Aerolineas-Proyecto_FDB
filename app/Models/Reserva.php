<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reserva';
    protected $primaryKey = 'id_reserva';
    public $timestamps = false;

    protected $fillable = [
        'id_pasajero',
        'id_vuelo',
        'fecha_creacion',
        'estado',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function pasajero()
    {
        return $this->belongsTo(Pasajero::class, 'id_pasajero', 'id_pasajero');
    }

    public function vuelo()
    {
        return $this->belongsTo(Vuelo::class, 'id_vuelo', 'id_vuelo');
    }

    public function boletos()
    {
        return $this->hasMany(Boleto::class, 'id_reserva', 'id_reserva');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'id_reserva', 'id_reserva');
    }
}