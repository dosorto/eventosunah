<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetodoPago extends BaseModel
{
    use HasFactory;

    protected $table = 'metodos_pago';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'tipo',
        'requiere_api',
        'requiere_comprobante',
        'documento_cobro_tipo',
        'banco_nombre',
        'numero_cuenta',
        'titular_cuenta',
        'detalle_transferencia_internacional',
        'proveedor',
        'api_base_url',
        'checkout_path',
        'http_method',
        'auth_type',
        'identifier_query_key',
        'public_key',
        'api_key',
        'secret_key',
        'headers_json',
        'checkout_fields_json',
        'instrucciones',
        'orden',
        'is_active',
    ];

    protected $casts = [
        'requiere_api' => 'boolean',
        'requiere_comprobante' => 'boolean',
        'is_active' => 'boolean',
        'headers_json' => 'array',
        'checkout_fields_json' => 'array',
    ];

    public const TYPE_CASH = 'efectivo';
    public const TYPE_TRANSFER = 'transferencia';
    public const TYPE_CARD = 'tarjeta';

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function registros()
    {
        return $this->hasMany(EventoRegistro::class, 'metodo_pago_id');
    }

    public function requiresProof(): bool
    {
        return $this->requiere_comprobante || in_array($this->tipo, [self::TYPE_TRANSFER, self::TYPE_CARD], true);
    }

    public function isCash(): bool
    {
        return $this->tipo === self::TYPE_CASH;
    }

    public function isTransfer(): bool
    {
        return $this->tipo === self::TYPE_TRANSFER;
    }

    public function isCard(): bool
    {
        return $this->tipo === self::TYPE_CARD;
    }
}
