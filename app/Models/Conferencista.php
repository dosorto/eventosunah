<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conferencista extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'Titulo', 'Descripcion', 'Foto', 'dni', 'nombre', 'apellido', 'correo',
        'correoInstitucional', 'fechaNacimiento', 'sexo', 'direccion', 'telefono',
        'numeroCuenta', 'IdNacionalidad', 'IdTipoPerfil', 'created_by',
    ];
    

    public function nacionalidad()
    {
        return $this->belongsTo(Nacionalidad::class, 'IdNacionalidad', 'id');
    }

    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'IdTipoPerfil', 'id');
    }
}
