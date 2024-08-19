<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiplomaGenerado extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    protected $fillable = ['IdAsistencia', 'uuid', 'created_by', 'deleted_by'];

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id');
    }

}
