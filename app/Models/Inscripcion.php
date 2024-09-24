<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suscripcion extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    protected $fillable = ['IdEvento', 'IdPersona', 'created_by', 'deleted_by'];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'IdEvento');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }



}
