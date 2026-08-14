<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoDocumentoProceso extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'evento_documento_procesos';

    protected $fillable = [
        'evento_id',
        'solicitado_por',
        'tipo',
        'estado',
        'total',
        'procesados',
        'generados',
        'omitidos',
        'tamano_lote',
        'ultimo_cursor_id',
        'payload',
        'directorio_temporal',
        'ruta_salida',
        'mensaje_estado',
        'error_detalle',
        'iniciado_at',
        'completado_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'iniciado_at' => 'datetime',
        'completado_at' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitado_por');
    }

    public function getPorcentajeAttribute(): int
    {
        if ((int) $this->total === 0) {
            return 0;
        }

        return (int) min(100, round(((int) $this->procesados / (int) $this->total) * 100));
    }

    public function getEstaActivoAttribute(): bool
    {
        return in_array($this->estado, ['pendiente', 'procesando'], true);
    }

    public function getEstaCompletoAttribute(): bool
    {
        return $this->estado === 'completado';
    }

    public function getEstaCanceladoAttribute(): bool
    {
        return $this->estado === 'cancelado';
    }
}
