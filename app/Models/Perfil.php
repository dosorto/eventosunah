<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfil extends BaseModel
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['Numero de Cuenta','Correo Institucional','IdPersona'];
}
