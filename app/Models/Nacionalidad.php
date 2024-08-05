<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Nacionalidad extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['nombreNacionalidad'];
    public function personas()
    {
        return $this->hasMany(Persona::class, 'IdNacionalidad');
    }

    protected $table = 'nacionalidads'; 
}
