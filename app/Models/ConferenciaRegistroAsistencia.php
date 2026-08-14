<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConferenciaRegistroAsistencia extends BaseModel
{
    use HasFactory;

    protected bool $usesAuditColumns = false;

    protected $table = 'conferencia_registro_asistencias';

    protected $fillable = [
        'conferencia_id',
        'evento_registro_id',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'conferencia_id');
    }

    public function eventoRegistro()
    {
        return $this->belongsTo(EventoRegistro::class, 'evento_registro_id');
    }
}
