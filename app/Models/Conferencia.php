<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Conferencia extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['nombre','descripcion','fecha','horaInicio','horaFin','lugar','linkreunion', 'idConferencista'];
    public function conferencista()
    {
        return $this->belongsTo(Conferencista::class, 'idConferencista');
    }
}
