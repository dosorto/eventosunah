<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventoRegistro extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected bool $usesAuditColumns = false;

    protected $fillable = [
        'evento_id',
        'persona_id',
        'tipoperfil_id',
        'metodo_pago_id',
        'precio_aplicado',
        'detalle_precio',
        'estado',
        'estado_pago',
        'referencia_pago',
        'payload_pago',
        'comprobante_pago_path',
        'comprobante_pago_nombre',
        'comprobante_pago_mime',
        'pagado_en',
    ];

    protected $casts = [
        'precio_aplicado' => 'decimal:2',
        'payload_pago' => 'array',
        'pagado_en' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function tipoPerfil()
    {
        return $this->belongsTo(Tipoperfil::class, 'tipoperfil_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function conferenciaAsistencias()
    {
        return $this->hasMany(ConferenciaRegistroAsistencia::class, 'evento_registro_id');
    }

    public function certificados()
    {
        return $this->hasMany(EventoCertificado::class, 'evento_registro_id');
    }

    public function formattedPrecioAplicado(bool $includeCode = false): string
    {
        $amount = (float) ($this->precio_aplicado ?? 0);
        $price = $this->matchingEventoPrice();

        if ($price?->moneda) {
            $symbol = $price->moneda->simbolo;
            $code = $includeCode ? $price->moneda->codigo : null;

            return trim(($symbol ? $symbol . ' ' : '') . number_format($amount, 2) . ($code ? ' ' . $code : ''));
        }

        return $this->evento?->formatMoney($amount, $includeCode) ?? number_format($amount, 2);
    }

    private function matchingEventoPrice(): ?EventoPrecio
    {
        $event = $this->relationLoaded('evento')
            ? $this->evento
            : $this->evento()->with('precios.moneda')->first();

        if (! $event) {
            return null;
        }

        $prices = $event->relationLoaded('precios')
            ? $event->precios
            : $event->precios()->with('moneda')->get();

        $profileId = (int) ($this->tipoperfil_id ?? 0);
        $amount = (float) ($this->precio_aplicado ?? 0);
        $profilePrices = $profileId > 0 ? $prices->where('IdTipoPerfil', $profileId) : collect();

        return $profilePrices->first(fn (EventoPrecio $price) => abs((float) $price->precio - $amount) < 0.01)
            ?? $profilePrices->first(fn (EventoPrecio $price) => (bool) $price->es_precio_evento_dia)
            ?? $profilePrices->first()
            ?? $prices->first(fn (EventoPrecio $price) => abs((float) $price->precio - $amount) < 0.01)
            ?? $prices->first(fn (EventoPrecio $price) => (bool) $price->es_precio_evento_dia)
            ?? $prices->first();
    }
}
