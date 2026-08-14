<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiIntegration extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'tipoperfil_id',
        'nombre',
        'base_url',
        'lookup_path',
        'http_method',
        'auth_type',
        'auth_token',
        'identifier_query_key',
        'timeout_seconds',
        'response_path',
        'headers_json',
        'field_map',
        'is_active',
    ];

    protected $casts = [
        'headers_json' => 'array',
        'field_map' => 'array',
        'is_active' => 'boolean',
    ];

    public function tipoPerfil()
    {
        return $this->belongsTo(Tipoperfil::class, 'tipoperfil_id');
    }
}
