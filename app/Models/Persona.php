<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Persona extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['IdUsuario','dni','foto','nombre','apellido','correo','correoInstitucional','fechaNacimiento','sexo', 'direccion', 'telefono', 'numeroCuenta', 'IdNacionalidad','IdTipoPerfil'];
    
    public function nacionalidad()
    {
        return $this->belongsTo(Nacionalidad::class, 'IdNacionalidad');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'IdUsuario');
    }
    public function conferencistas()
    {
        return $this->hasMany(Conferencista::class, 'IdPersona');
    }
    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'IdTipoPerfil');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'IdPersona');
    }
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'IdPersona');
    }
}