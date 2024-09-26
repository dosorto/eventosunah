<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibopago extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'idEvento',
        'idPersona',
        'fecha',
        'foto',
    ];

    public function evento()
    {
        return $this->hasMany(Evento::class, 'idEvento');
    }
    public function inscripcion()
    {
        return $this->hasOne(Inscripcion::class, 'idRecibo');
    }

    public function persona()
    {
        return $this->hasOne(Persona::class, 'idPersona');
    }
    

}
