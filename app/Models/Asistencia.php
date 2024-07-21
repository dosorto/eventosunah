<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Asistencia extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['Fecha','Asistencia','IdPersona', 'IdEvento'];
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'IdEvento');
    }
}
