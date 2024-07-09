<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrera extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['carrera','IdDepartamento'];
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'IdDepartamento');
    }
}

