<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventoCertificado extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'evento_id',
        'persona_id',
        'evento_registro_id',
        'conferencia_id',
        'tipo',
        'nombre_certificado',
        'hash_unico',
        'pdf_path',
        'generated_at',
        'metadata',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function eventoRegistro()
    {
        return $this->belongsTo(EventoRegistro::class, 'evento_registro_id');
    }

    public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'conferencia_id');
    }
}
