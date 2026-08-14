<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventoInvitacion extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'evento_invitaciones';

    protected $fillable = [
        'evento_id',
        'codigo',
        'nombre_invitado',
        'correo_invitado',
        'telefono_invitado',
        'cupos',
        'cupos_utilizados',
        'activa',
        'enviada_at',
        'correo_enviado_at',
        'whatsapp_enviado_at',
        'ultimo_canal_envio',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'enviada_at' => 'datetime',
        'correo_enviado_at' => 'datetime',
        'whatsapp_enviado_at' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
