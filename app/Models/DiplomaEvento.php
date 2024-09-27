<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiplomaEvento extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    protected $fillable = ['inscripcionId', 'uuid', 'created_by', 'deleted_by'];

    public function inscripcion()
    {
        return $this->hasMany(Inscripcion::class, 'inscripcionId');
    }

}
