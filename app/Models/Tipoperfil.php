<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tipoperfil extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'tipoperfil',
        'codigo',
        'tipo_identificador',
        'etiqueta_identificador',
        'requiere_api',
        'permite_registro_manual',
    ];

    protected $casts = [
        'requiere_api' => 'boolean',
        'permite_registro_manual' => 'boolean',
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class, 'IdTipoPerfil');
    }

    public function apiIntegrations()
    {
        return $this->hasMany(ApiIntegration::class, 'tipoperfil_id');
    }
}
