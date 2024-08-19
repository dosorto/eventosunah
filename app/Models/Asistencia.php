<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Asistencia extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['Fecha','Asistencia','IdSuscripcion'];
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'IdSuscripcion');
    }

    public function diplomaGenerado()
    {
        return $this->hasOne(DiplomaGenerado::class, 'IdAsistencia');
    }
}
