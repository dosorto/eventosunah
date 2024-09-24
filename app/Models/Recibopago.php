<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibopago extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'IdEvento',
        'IdPersona',
        'foto',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'IdEvento');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }

}
