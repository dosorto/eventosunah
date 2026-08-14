<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'logo',
        'banner',
        'nombreevento',
        'descripcion',
        'organizador',
        'tipo_conferencia_id',
        'fechainicio',
        'fechafinal',
        'horainicio',
        'horafin',
        'idmodalidad',
        'idlocalidad',
        'localidad_nombre',
        'tipo_acceso',
        'genera_diploma_participacion',
        'IdDiploma',
        'diploma_plantilla',
        'diploma_page_size',
        'diploma_orientation',
        'graphic_designs',
        'estado',
        'published_at',
    ];

    protected $casts = [
        'fechainicio' => 'date',
        'fechafinal' => 'date',
        'genera_diploma_participacion' => 'boolean',
        'graphic_designs' => 'array',
        'published_at' => 'datetime',
    ];

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'idmodalidad');
    }

    public function tipoEvento()
    {
        return $this->belongsTo(TipoConferencia::class, 'tipo_conferencia_id');
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'idlocalidad');
    }

    public function conferencias()
    {
        return $this->hasMany(Conferencia::class, 'IdEvento');
    }

    public function diploma()
    {
        return $this->belongsTo(Diploma::class, 'IdDiploma');
    }

    public function invitaciones()
    {
        return $this->hasMany(EventoInvitacion::class, 'evento_id');
    }

    public function staffMembers()
    {
        return $this->hasMany(EventoStaff::class, 'evento_id');
    }

    public function precios()
    {
        return $this->hasMany(EventoPrecio::class, 'evento_id')->orderBy('orden');
    }

    public function registros()
    {
        return $this->hasMany(EventoRegistro::class, 'evento_id');
    }

    public function certificados()
    {
        return $this->hasMany(EventoCertificado::class, 'evento_id');
    }

    public function documentoProcesos()
    {
        return $this->hasMany(EventoDocumentoProceso::class, 'evento_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('estado', 'publicado')->whereNotNull('published_at');
    }

    public function getLocalidadDisplayAttribute(): string
    {
        return $this->localidad_nombre ?: ($this->localidad?->localidad ?? 'Por definir');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return Str::startsWith($this->logo, ['http://', 'https://']) ? $this->logo : asset($this->logo);
    }

    public function getBannerUrlAttribute(): ?string
    {
        if (! $this->banner) {
            return null;
        }

        return Str::startsWith($this->banner, ['http://', 'https://']) ? $this->banner : asset($this->banner);
    }

    public function getDiplomaPlantillaUrlAttribute(): ?string
    {
        if (! $this->diploma_plantilla) {
            return null;
        }

        return Str::startsWith($this->diploma_plantilla, ['http://', 'https://'])
            ? $this->diploma_plantilla
            : asset($this->diploma_plantilla);
    }

    public function currencySymbol(): ?string
    {
        return $this->baseCurrencyPrice()?->moneda?->simbolo;
    }

    public function currencyCode(): ?string
    {
        return $this->baseCurrencyPrice()?->moneda?->codigo;
    }

    public function formatMoney(float|int|string|null $amount, bool $includeCode = false): string
    {
        $number = number_format((float) ($amount ?? 0), 2);
        $symbol = $this->currencySymbol();
        $code = $includeCode ? $this->currencyCode() : null;

        return trim(($symbol ? $symbol . ' ' : '') . $number . ($code ? ' ' . $code : ''));
    }

    private function baseCurrencyPrice(): ?EventoPrecio
    {
        $prices = $this->relationLoaded('precios')
            ? $this->precios
            : $this->precios()->with('moneda')->get();

        return $prices->first(fn (EventoPrecio $price) => (bool) $price->es_precio_evento_dia && filled($price->moneda?->simbolo))
            ?? $prices->first(fn (EventoPrecio $price) => filled($price->moneda?->simbolo))
            ?? $prices->first();
    }
}
