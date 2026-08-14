<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventoPrecio extends BaseModel
{
    use HasFactory;

    protected $table = 'evento_precios';

    protected $fillable = [
        'evento_id',
        'IdTipoPerfil',
        'moneda_id',
        'categoria_key',
        'categoria_nombre',
        'es_precio_evento_dia',
        'precio',
        'fecha_inicio',
        'fecha_fin',
        'orden',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'es_precio_evento_dia' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function tipoPerfil()
    {
        return $this->belongsTo(Tipoperfil::class, 'IdTipoPerfil');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }
}
