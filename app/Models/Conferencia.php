<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Conferencia extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'conferencias';
    protected $fillable = ['IdEvento','tipo_conferencia_id','foto','nombre','descripcion','fecha','horaInicio','horaFin','lugar','linkreunion', 'conferencista_nombre_invitado', 'speaker_persona_id', 'speaker_access_token', 'speaker_profile_completed_at', 'conference_content_completed_at', 'speaker_onboarding_step', 'speaker_onboarding_submitted_at', 'codigo_auto_asistencia', 'codigo_auto_asistencia_generado_en', 'idConferencista'];

    protected $casts = [
        'speaker_profile_completed_at' => 'datetime',
        'conference_content_completed_at' => 'datetime',
        'speaker_onboarding_submitted_at' => 'datetime',
        'codigo_auto_asistencia_generado_en' => 'datetime',
    ];

    public function conferencista()
    {
        return $this->belongsTo(Conferencista::class, 'idConferencista');
    }

    public function speakerPersona()
    {
        return $this->belongsTo(Persona::class, 'speaker_persona_id');
    }

    public function tipoConferencia()
    {
        return $this->belongsTo(TipoConferencia::class, 'tipo_conferencia_id');
    }
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'IdEvento');
    }
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'IdConferencia');
    }

    public function registroAsistencias()
    {
        return $this->hasMany(ConferenciaRegistroAsistencia::class, 'conferencia_id');
    }

    public function certificados()
    {
        return $this->hasMany(EventoCertificado::class, 'conferencia_id');
    }

    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto
                ? (Str::startsWith($this->foto, ['http://', 'https://']) ? $this->foto : asset(str_replace('public/', 'storage/', $this->foto)))
                : null
        );
    }

    protected function onboardingUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->speaker_access_token ? route('speaker.access', ['token' => $this->speaker_access_token]) : null
        );
    }

    protected function speakerPending(): Attribute
    {
        return Attribute::make(
            get: fn () => ! $this->speaker_profile_completed_at || ! $this->conference_content_completed_at
        );
    }
}
