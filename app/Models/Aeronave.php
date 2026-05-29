<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Aeronave extends Model
{
    use HasFactory;

    protected $table = 'aeronave';
    protected $primaryKey = 'id_aeronave';
    public $timestamps = false;

    protected $fillable = [
        'id_modelo',
        'matricula',
        'capacidad_max',
    ];

    public function modelo()
    {
        return $this->belongsTo(ModeloAeronave::class, 'id_modelo', 'id_modelo');
    }

    public function vuelos()
    {
        return $this->hasMany(Vuelo::class, 'id_aeronave', 'id_aeronave');
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'id_aeronave', 'id_aeronave');
    }
}