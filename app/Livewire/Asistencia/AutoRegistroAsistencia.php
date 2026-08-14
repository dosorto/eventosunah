<?php

namespace App\Livewire\Asistencia;

use App\Models\Conferencia;
use App\Models\ConferenciaRegistroAsistencia;
use App\Models\Evento;
use App\Models\EventoRegistro;
use App\Models\Persona;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AutoRegistroAsistencia extends Component
{
    public ?Conferencia $conference = null;

    public ?Evento $event = null;

    public string $status = 'error';

    public string $message = '';

    public bool $alreadyRegistered = false;

    public function mount(string $code): void
    {
        $code = strtoupper(trim($code));

        $this->conference = Conferencia::query()
            ->with(['evento', 'tipoConferencia'])
            ->where('codigo_auto_asistencia', $code)
            ->first();

        if (! $this->conference || ! $this->conference->evento) {
            $this->deny('El código de asistencia no existe o ya no está disponible.');

            return;
        }

        $this->event = $this->conference->evento;

        $persona = Persona::query()
            ->where('IdUsuario', Auth::id())
            ->first();

        if (! $persona) {
            $this->deny('Tu usuario no tiene un perfil de participante asociado.');

            return;
        }

        $registration = EventoRegistro::query()
            ->where('evento_id', $this->event->id)
            ->where('persona_id', $persona->id)
            ->where(function ($query): void {
                $query->whereNull('estado')
                    ->orWhereNotIn('estado', ['cancelado', 'reembolsado', 'eliminado']);
            })
            ->first();

        if (! $registration) {
            $this->deny('No estás inscrito en este evento, por eso no puedes registrar asistencia.');

            return;
        }

        if (! $this->registrationCanMarkAttendance($registration)) {
            $this->deny('Tu inscripción existe, pero el pago aún no está validado. Cuando el pago sea aprobado podrás registrar asistencia.');

            return;
        }

        $attendance = ConferenciaRegistroAsistencia::query()
            ->where('conferencia_id', $this->conference->id)
            ->where('evento_registro_id', $registration->id)
            ->first();

        if ($attendance) {
            if (! $attendance->checked_in_at) {
                $attendance->forceFill(['checked_in_at' => now()])->save();
            }

            $this->alreadyRegistered = true;
            $this->status = 'success';
            $this->message = 'Tu asistencia ya estaba registrada para esta conferencia.';

            return;
        }

        ConferenciaRegistroAsistencia::query()->create([
            'conferencia_id' => $this->conference->id,
            'evento_registro_id' => $registration->id,
            'checked_in_at' => now(),
        ]);

        $this->status = 'success';
        $this->message = 'Tu asistencia fue registrada correctamente.';
    }

    private function deny(string $message): void
    {
        $this->status = 'error';
        $this->message = $message;
    }

    private function registrationCanMarkAttendance(EventoRegistro $registration): bool
    {
        if ($this->event?->tipo_acceso !== 'pagada') {
            return true;
        }

        return $registration->estado_pago === 'pagado';
    }

    public function render()
    {
        return view('livewire.Asistencia.auto-registro-asistencia')
            ->layout('components.layouts.app');
    }
}
