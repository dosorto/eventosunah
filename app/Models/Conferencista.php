<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conferencista extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['Titulo','Descripcion','Foto','IdPersona'];
}
