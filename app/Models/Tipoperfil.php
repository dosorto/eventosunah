<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tipoperfil extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['Tipo Perfil'];
}
