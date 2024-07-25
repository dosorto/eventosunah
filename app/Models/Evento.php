<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['nombreevento','descripcion','organizador','idmodalidad','idlocalidad'];

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'idmodalidad');
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'idlocalidad');
    }

}