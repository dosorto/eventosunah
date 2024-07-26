<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suscripcion extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['IdConferencia', 'IdPersona'];
    public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'IdConferencia');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }

}
