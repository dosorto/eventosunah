<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Conferencia extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'conferencias';
    protected $fillable = ['IdEvento','foto','nombre','descripcion','fecha','horaInicio','horaFin','lugar','linkreunion', 'idConferencista','estado','precio'];
    public function conferencista()
    {
        return $this->belongsTo(Conferencista::class, 'idConferencista');
    }
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'IdEvento');
    }
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'IdConferencia');
    }
    public function inscripciones() {
        return $this->belongsTo(Inscripcion::class, 'IdEvento');
    }
    
}