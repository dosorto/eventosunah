<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'IdUsuario',
        'dni',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'nombre',
        'apellido',
        'correo',
        'correoInstitucional',
        'fechaNacimiento',
        'sexo',
        'direccion',
        'telefono',
        'numeroCuenta',
        'numeroEmpleado',
        'IdNacionalidad',
        'IdTipoPerfil',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'nombre',
        'apellido',
        'nombre_completo',
    ];

    public function getNombreAttribute(): string
    {
        return $this->joinNameParts($this->primer_nombre, $this->segundo_nombre);
    }

    public function setNombreAttribute(mixed $value): void
    {
        [$primerNombre, $segundoNombre] = $this->splitNameParts($value);

        $this->attributes['primer_nombre'] = $primerNombre ?: null;
        $this->attributes['segundo_nombre'] = $segundoNombre ?: null;
    }

    public function getApellidoAttribute(): string
    {
        return $this->joinNameParts($this->primer_apellido, $this->segundo_apellido);
    }

    public function setApellidoAttribute(mixed $value): void
    {
        [$primerApellido, $segundoApellido] = $this->splitNameParts($value);

        $this->attributes['primer_apellido'] = $primerApellido ?: null;
        $this->attributes['segundo_apellido'] = $segundoApellido ?: null;
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombre . ' ' . $this->apellido);
    }

    public function scopeSearchName(Builder $query, string $search): Builder
    {
        $search = trim($search);

        if ($search === '') {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($search) {
            $subQuery
                ->where('primer_nombre', 'like', '%' . $search . '%')
                ->orWhere('segundo_nombre', 'like', '%' . $search . '%')
                ->orWhere('primer_apellido', 'like', '%' . $search . '%')
                ->orWhere('segundo_apellido', 'like', '%' . $search . '%')
                ->orWhereRaw("TRIM(CONCAT_WS(' ', primer_nombre, segundo_nombre, primer_apellido, segundo_apellido)) LIKE ?", ['%' . $search . '%']);
        });
    }

    public function scopeOrderByFullName(Builder $query): Builder
    {
        return $query
            ->orderBy('primer_nombre')
            ->orderBy('segundo_nombre')
            ->orderBy('primer_apellido')
            ->orderBy('segundo_apellido');
    }

    private function splitNameParts(mixed $value): array
    {
        $parts = preg_split('/\s+/', trim((string) $value), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $first = array_shift($parts) ?: '';

        return [$first, implode(' ', $parts)];
    }

    private function joinNameParts(?string $first, ?string $second): string
    {
        return trim(implode(' ', array_filter([
            trim((string) $first),
            trim((string) $second),
        ])));
    }
    
    public function nacionalidad()
    {
        return $this->belongsTo(Nacionalidad::class, 'IdNacionalidad');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'IdUsuario');
    }
    public function conferencistas()
    {
        return $this->hasMany(Conferencista::class, 'IdPersona');
    }
    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'IdTipoPerfil');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'IdPersona');
    }

    public function eventoRegistros()
    {
        return $this->hasMany(EventoRegistro::class, 'persona_id');
    }

    public function eventoStaff()
    {
        return $this->hasMany(EventoStaff::class, 'persona_id');
    }

    public function certificados()
    {
        return $this->hasMany(EventoCertificado::class, 'persona_id');
    }
}
