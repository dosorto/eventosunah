<?php

namespace App\Models;

class FacturacionConfig extends BaseModel
{
    protected $table = 'facturacion_configs';

    protected $fillable = [
        'razon_social',
        'rtn_emisor',
        'cai',
        'establecimiento_codigo',
        'punto_emision_codigo',
        'tipo_documento_codigo',
        'rango_inicio',
        'rango_fin',
        'siguiente_numero',
        'fecha_limite_emision',
        'direccion_fiscal',
        'telefono_fiscal',
        'correo_fiscal',
        'leyenda',
        'is_active',
    ];

    protected $casts = [
        'rango_inicio' => 'integer',
        'rango_fin' => 'integer',
        'siguiente_numero' => 'integer',
        'fecha_limite_emision' => 'date',
        'is_active' => 'boolean',
    ];

    public function formattedDocumentNumber(int $sequence): string
    {
        return sprintf(
            '%s-%s-%s-%08d',
            str_pad((string) ($this->establecimiento_codigo ?: '000'), 3, '0', STR_PAD_LEFT),
            str_pad((string) ($this->punto_emision_codigo ?: '000'), 3, '0', STR_PAD_LEFT),
            str_pad((string) ($this->tipo_documento_codigo ?: '01'), 2, '0', STR_PAD_LEFT),
            $sequence
        );
    }

    public function hasValidRange(): bool
    {
        return filled($this->cai)
            && filled($this->rango_inicio)
            && filled($this->rango_fin)
            && filled($this->siguiente_numero)
            && $this->siguiente_numero >= $this->rango_inicio
            && $this->siguiente_numero <= $this->rango_fin
            && $this->fecha_limite_emision !== null
            && ($this->fecha_limite_emision->isToday() || $this->fecha_limite_emision->isFuture());
    }
}
