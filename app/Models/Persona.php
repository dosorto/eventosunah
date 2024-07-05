<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Persona extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['DNI','nombre','apellido','correo','Fecha de nacimiento','Sexo','Direccion','Telefono','IdNacionalidad','IdTipoPerfil'];
}
