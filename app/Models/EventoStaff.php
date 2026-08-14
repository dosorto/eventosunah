<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class EventoStaff extends BaseModel
{
    use HasFactory;

    protected $table = 'evento_staff';

    protected $fillable = [
        'evento_id',
        'persona_id',
        'nombre',
        'correo',
        'telefono',
        'staff_access_token',
        'invitado_at',
        'perfil_completado_at',
        'activo',
    ];

    protected $casts = [
        'invitado_at' => 'datetime',
        'perfil_completado_at' => 'datetime',
        'activo' => 'boolean',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    protected function onboardingUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->staff_access_token ? route('staff.access', ['token' => $this->staff_access_token]) : null
        );
    }

    protected function hasUser(): Attribute
    {
        return Attribute::make(
            get: fn () => (bool) $this->persona?->user
        );
    }

    protected function estadoPerfil(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->perfil_completado_at ? 'Completo' : 'Pendiente'
        );
    }

    protected function nombreCorto(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->nombre, 42)
        );
    }
}
