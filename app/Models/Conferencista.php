<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conferencista extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['titulo', 'foto', 'descripcion', 'IdPersona','firma','sello'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }
    public function conferencias()
    {
        return $this->hasMany(Conferencia::class, 'idConferencista');
    }
}