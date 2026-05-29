<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abordaje extends Model
{
    protected $table = 'abordaje';
    protected $primaryKey = 'id_abordaje';
    public $timestamps = false;

    protected $fillable = [
        'id_boleto',
        'id_puerta',
        'hora_abordaje',
    ];

    protected $casts = [
        'hora_abordaje' => 'datetime',
    ];

    public function boleto()
    {
        return $this->belongsTo(Boleto::class, 'id_boleto', 'id_boleto');
    }

    public function puertaEmbarque()
    {
        return $this->belongsTo(PuertaEmbarque::class, 'id_puerta', 'id_puerta');
    }
}