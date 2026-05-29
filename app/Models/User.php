<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'contrasenna',
        'rol',
    ];

    protected $hidden = [
        'contrasenna',
    ];

    protected $casts = [
        // no special casts needed by default
    ];

    public function pasajero()
    {
        return $this->hasOne(Pasajero::class, 'id_user', 'id_user');
    }

    public function tripulacion()
    {
        return $this->hasOne(Tripulacion::class, 'id_user', 'id_user');
    }

    public function getAuthPasswordName(): string
    {
        return 'contrasenna';
    }
}