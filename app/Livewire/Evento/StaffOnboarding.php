<?php

namespace App\Livewire\Evento;

use App\Models\EventoStaff;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Tipoperfil;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class StaffOnboarding extends Component
{
    public EventoStaff $staffMember;
    public int $step = 1;
    public bool $showSubmitConfirmation = false;

    public string $nombre = '';
    public string $apellido = '';
    public string $correo = '';
    public string $telefono = '';
    public string $dni = '';
    public string $fechaNacimiento = '';
    public string $sexo = '';
    public string $direccion = '';
    public string $IdNacionalidad = '';
    public string $IdTipoPerfil = '';
    public string $account_password = '';
    public string $account_password_confirmation = '';

    protected $validationAttributes = [
        'nombre' => 'nombres',
        'apellido' => 'apellidos',
        'correo' => 'correo',
        'telefono' => 'telefono',
        'dni' => 'dni',
        'fechaNacimiento' => 'fecha de nacimiento',
        'sexo' => 'sexo',
        'direccion' => 'direccion',
        'IdNacionalidad' => 'nacionalidad',
        'IdTipoPerfil' => 'tipo de perfil',
        'account_password' => 'contrasena',
        'account_password_confirmation' => 'confirmacion de contrasena',
    ];

    public function mount(string $token): void
    {
        $this->staffMember = EventoStaff::query()
            ->with(['evento', 'persona.user', 'persona.nacionalidad', 'persona.tipoPerfil'])
            ->where('staff_access_token', $token)
            ->firstOrFail();

        $linkedUserId = $this->staffMember->persona?->IdUsuario;

        if ($linkedUserId) {
            if (! Auth::check()) {
                abort(403, 'Debes iniciar sesión para acceder a este wizard.');
            }

            abort_if((int) Auth::id() !== (int) $linkedUserId, 403, 'Este enlace pertenece a otro miembro del staff.');
        }

        $this->fillFromStaffMember();
        $this->step = $this->isSubmitted() ? 3 : 1;
    }

    public function render()
    {
        return view('livewire.Evento.staff-onboarding', [
            'evento' => $this->staffMember->evento,
            'nacionalidades' => Nacionalidad::query()->orderBy('nombreNacionalidad')->get(),
            'tiposPerfil' => Tipoperfil::query()->orderBy('tipoperfil')->get(),
            'stepDefinitions' => $this->stepDefinitions(),
            'isSubmitted' => $this->isSubmitted(),
            'requiresAccount' => $this->requiresAccount(),
        ])->layout('components.layouts.public');
    }

    public function nextStep(): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        $this->validate($this->stepRules($this->step));
        $this->step = min(3, $this->step + 1);
    }

    public function previousStep(): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        $this->step = max(1, $this->step - 1);
    }

    public function goToStep(int $step): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        if ($step > $this->step) {
            $this->validate($this->stepRules($this->step));
        }

        $this->step = max(1, min(3, $step));
    }

    public function openSubmitConfirmation(): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        $this->step = 3;
        $this->validate($this->rules());
        $this->showSubmitConfirmation = true;
    }

    public function closeSubmitConfirmation(): void
    {
        $this->showSubmitConfirmation = false;
    }

    public function submitFinal(): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        $validated = $this->validate($this->rules());
        $persona = $this->resolveOrCreatePersona();
        $user = $persona->user;

        if (! $user) {
            $user = User::query()->create([
                'name' => trim($validated['nombre'] . ' ' . $validated['apellido']),
                'email' => $validated['correo'],
                'password' => Hash::make($validated['account_password']),
            ]);
            $user->forceFill(['email_verified_at' => now()])->save();

            $persona->update([
                'IdUsuario' => $user->id,
                'updated_by' => Auth::id() ?? 1,
            ]);
        }

        $this->assignStaffRole($user);

        $this->staffMember->update([
            'persona_id' => $persona->id,
            'nombre' => trim($validated['nombre'] . ' ' . $validated['apellido']),
            'correo' => $validated['correo'],
            'telefono' => $validated['telefono'],
            'invitado_at' => $this->staffMember->invitado_at ?: now(),
            'perfil_completado_at' => now(),
            'activo' => true,
        ]);

        $this->staffMember->refresh();
        $this->staffMember->load(['evento', 'persona.user', 'persona.nacionalidad', 'persona.tipoPerfil']);
        $this->fillFromStaffMember();
        $this->showSubmitConfirmation = false;
        $this->step = 3;
        $this->reset(['account_password', 'account_password_confirmation']);

        session()->flash('message', 'Tu perfil de staff fue completado correctamente. Ya puedes ingresar al sistema con tus credenciales.');
    }

    private function rules(): array
    {
        $personaId = $this->staffMember->persona?->id;
        $userId = $this->staffMember->persona?->IdUsuario;
        $passwordRule = $this->requiresAccount()
            ? ['required', 'confirmed', Password::min(8)]
            : ['nullable'];

        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:personas,correo,' . $personaId . '|unique:users,email,' . $userId,
            'telefono' => 'required|string|max:50',
            'dni' => 'required|string|max:50|unique:personas,dni,' . $personaId,
            'fechaNacimiento' => 'nullable|date',
            'sexo' => 'required|string|max:50',
            'direccion' => 'required|string|max:255',
            'IdNacionalidad' => 'required|exists:nacionalidads,id',
            'IdTipoPerfil' => 'required|exists:tipoperfils,id',
            'account_password' => $passwordRule,
        ];
    }

    private function stepRules(int $step): array
    {
        return match ($step) {
            1 => [
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'correo' => 'required|email|max:255',
                'telefono' => 'required|string|max:50',
            ],
            2 => [
                'dni' => 'required|string|max:50',
                'fechaNacimiento' => 'nullable|date',
                'sexo' => 'required|string|max:50',
                'direccion' => 'required|string|max:255',
                'IdNacionalidad' => 'required|exists:nacionalidads,id',
                'IdTipoPerfil' => 'required|exists:tipoperfils,id',
            ],
            default => $this->rules(),
        };
    }

    private function resolveOrCreatePersona(): Persona
    {
        $persona = $this->staffMember->persona;

        if (! $persona && $this->dni !== '') {
            $persona = Persona::query()->where('dni', $this->dni)->first();
        }

        if (! $persona && $this->correo !== '') {
            $persona = Persona::query()->where('correo', $this->correo)->first();
        }

        $payload = [
            'dni' => $this->dni,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'correo' => $this->correo,
            'correoInstitucional' => null,
            'fechaNacimiento' => $this->fechaNacimiento !== '' ? $this->fechaNacimiento : ($persona?->fechaNacimiento ? date('Y-m-d', strtotime((string) $persona->fechaNacimiento)) : '1990-01-01'),
            'sexo' => $this->sexo,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'numeroCuenta' => null,
            'numeroEmpleado' => null,
            'IdNacionalidad' => (int) $this->IdNacionalidad,
            'IdTipoPerfil' => (int) $this->IdTipoPerfil,
            'updated_by' => Auth::id() ?? 1,
        ];

        if ($persona) {
            $persona->update($payload);

            return $persona->fresh();
        }

        return Persona::query()->create($payload + [
            'created_by' => Auth::id() ?? 1,
        ]);
    }

    private function fillFromStaffMember(): void
    {
        $persona = $this->staffMember->persona;
        [$firstName, $lastName] = $this->splitFullName($this->staffMember->nombre ?? '');

        $this->nombre = $persona?->nombre ?? $firstName;
        $this->apellido = $persona?->apellido ?? $lastName;
        $this->correo = $persona?->correo ?? ($this->staffMember->correo ?? '');
        $this->telefono = $persona?->telefono ?? ($this->staffMember->telefono ?? '');
        $this->dni = $persona?->dni ?? '';
        $this->fechaNacimiento = $persona?->fechaNacimiento ? date('Y-m-d', strtotime((string) $persona->fechaNacimiento)) : '';
        $this->sexo = $persona?->sexo ?? '';
        $this->direccion = $persona?->direccion ?? '';
        $this->IdNacionalidad = (string) ($persona?->IdNacionalidad ?? '');
        $this->IdTipoPerfil = (string) ($persona?->IdTipoPerfil ?? $this->employeeProfileId());
    }

    private function splitFullName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];

        if (count($parts) <= 1) {
            return [$parts[0] ?? '', ''];
        }

        $lastName = array_pop($parts);

        return [implode(' ', $parts), $lastName];
    }

    private function assignStaffRole(User $user): void
    {
        if (method_exists($user, 'assignRole') && ! $user->hasRole('staff-evento')) {
            $user->assignRole('staff-evento');
        }
    }

    private function isSubmitted(): bool
    {
        return (bool) $this->staffMember->perfil_completado_at;
    }

    private function requiresAccount(): bool
    {
        return ! (bool) $this->staffMember->persona?->user;
    }

    private function stepDefinitions(): array
    {
        return [
            ['number' => 1, 'title' => 'Paso 1', 'label' => 'Datos base'],
            ['number' => 2, 'title' => 'Paso 2', 'label' => 'Perfil'],
            ['number' => 3, 'title' => 'Paso 3', 'label' => 'Resumen'],
        ];
    }

    private function employeeProfileId(): ?int
    {
        return Tipoperfil::query()
            ->whereRaw('LOWER(tipoperfil) = ?', ['empleado'])
            ->value('id');
    }
}
