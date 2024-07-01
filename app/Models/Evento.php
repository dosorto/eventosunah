<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends BaseModel
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['Nombre','Descripcion','Organizador','Fecha Inicio', 'Fecha Final','Hora Inicio','Hora Final','IdModalidad','IdLocalidad','IdConferencia'];
}

