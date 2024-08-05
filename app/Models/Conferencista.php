<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conferencista extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['titulo', 'foto', 'descripcion', 'IdPersona'];

    public function persona()
    {
        return $this->belongsTo(Nacionalidad::class, 'IdNacionalidad', 'id');
    }

    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'IdTipoPerfil', 'id');
    }
    public function conferencias()
    {
        return $this->hasMany(Conferencia::class, 'idConferencista');
    }
}