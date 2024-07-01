<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Conferencia extends BaseModel
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['Nombre','Descripcion','Fecha','HoraInicio','HoraFin','Lugar','Link Reunion', 'IdConferencista'];
}
