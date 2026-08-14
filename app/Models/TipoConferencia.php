<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoConferencia extends BaseModel
{
    use HasFactory;

    protected $table = 'tipos_conferencias';

    protected $fillable = [
        'tipo',
    ];

    public function conferencias()
    {
        return $this->hasMany(Conferencia::class, 'tipo_conferencia_id');
    }
}
