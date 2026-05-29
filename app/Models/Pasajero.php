<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasajero extends Model
{
    use HasFactory;

    protected $table = 'pasajero';
    protected $primaryKey = 'id_pasajero';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nombre_completo',
        'pasaporte',
        'nacionalidad',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_pasajero', 'id_pasajero');
    }

    public function equipajes()
    {
        return $this->hasMany(Equipaje::class, 'id_pasajero', 'id_pasajero');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}