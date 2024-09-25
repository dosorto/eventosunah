<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'NumTransaccion',
        'IdConferencia',
        'IdPersona',
        'Monto',
        'NombreTarjeta',
        'NumeroTarjeta',
        'FechaPago',
        'MesExpiracion',
        'AnioExpiracion',
        'cvv',
    ];

    public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'IdConferencia');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }
}
