<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloAeronave extends Model
{
    use HasFactory;

    protected $table = 'modelo_aeronave';
    protected $primaryKey = 'id_modelo';
    public $timestamps = false;

    protected $fillable = [
        'fabricante',
        'nombre_comercial',
        'autonomia_km',
    ];

    public function aeronaves()
    {
        return $this->hasMany(Aeronave::class, 'id_modelo', 'id_modelo');
    }
}