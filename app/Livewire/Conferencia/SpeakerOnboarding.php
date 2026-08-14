<?php

namespace App\Livewire\Conferencia;

use App\Mail\SpeakerAccessCredentialsMail;
use App\Models\Conferencia;
use App\Models\Conferencista;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Tipoperfil;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class SpeakerOnboarding extends Component
{
    use WithFileUploads;

    private const STEP_FIELDS = [
        1 => [
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'dni',
            'correo',
            'correoInstitucional',
            'telefono',
            'fechaNacimiento',
            'sexo',
            'direccion',
            'numeroCuenta',
            'IdNacionalidad',
            'IdTipoPerfil',
        ],
        2 => [
            'nivel_academico',
            'descripcion_perfil',
            'speaker_photo',
        ],
        3 => [
            'conference_nombre',
            'conference_descripcion',
            'conference_photo',
        ],
    ];

    public Conferencia $conferencia;
    public int $step = 1;
    public bool $showSubmitConfirmation = false;
    public ?string $draftSavedAt = null;
    public $speaker_photo;
    public $conference_photo;
    public string $dni = '';
    public string $primer_nombre = '';
    public string $segundo_nombre = '';
    public string $primer_apellido = '';
    public string $segundo_apellido = '';
    public string $correo = '';
    public string $correoInstitucional = '';
    public string $fechaNacimiento = '';
    public string $sexo = '';
    public string $direccion = '';
    public string $telefono = '';
    public string $numeroCuenta = '';
    public string $IdNacionalidad = '';
    public string $IdTipoPerfil = '';
    public string $nivel_academico = '';
    public string $descripcion_perfil = '';
    public string $conference_nombre = '';
    public string $conference_descripcion = '';
    public ?string $currentSpeakerPhoto = null;
    public ?string $currentConferencePhoto = null;

    protected $validationAttributes = [
        'dni' => 'dni',
        'primer_nombre' => 'primer nombre',
        'segundo_nombre' => 'segundo nombre',
        'primer_apellido' => 'primer apellido',
        'segundo_apellido' => 'segundo apellido',
        'correo' => 'correo personal',
        'correoInstitucional' => 'correo institucional',
        'fechaNacimiento' => 'fecha de nacimiento',
        'sexo' => 'sexo',
        'direccion' => 'direccion',
        'telefono' => 'telefono',
        'numeroCuenta' => 'numero de identificacion',
        'IdNacionalidad' => 'nacionalidad',
        'IdTipoPerfil' => 'tipo de perfil',
        'nivel_academico' => 'nivel academico',
        'descripcion_perfil' => 'perfil profesional',
        'conference_nombre' => 'nombre de la conferencia',
        'conference_descripcion' => 'descripcion de la conferencia',
        'speaker_photo' => 'foto del conferencista',
        'conference_photo' => 'imagen de la conferencia',
    ];

    public function mount(string $token): void
    {
        $this->conferencia = Conferencia::query()
            ->with(['evento', 'conferencista.persona.user', 'speakerPersona.user'])
            ->where('speaker_access_token', $token)
            ->firstOrFail();

        $this->fillFromConference();
        $this->step = $this->resolveInitialStep();
    }

    public function render()
    {
        return view('livewire.Conferencia.speaker-onboarding', [
            'evento' => $this->conferencia->evento,
            'nacionalidades' => Nacionalidad::query()->orderBy('nombreNacionalidad')->get(),
            'tiposPerfil' => Tipoperfil::query()->orderBy('tipoperfil')->get(),
            'academicLevels' => $this->academicLevels(),
            'stepDefinitions' => $this->stepDefinitions(),
            'isSubmitted' => $this->isSubmitted(),
        ])->layout('components.layouts.public');
    }

    public function updated($property): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        if (! $this->isDraftProperty($property)) {
            return;
        }

        $this->persistDraft();
    }

    public function goToStep(int $step): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        $step = max(1, min(4, $step));

        if ($step > $this->step) {
            $this->validate($this->stepRules($this->step));
        }

        $this->step = $step;
        $this->persistDraft();
    }

    public function nextStep(): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        $this->validate($this->stepRules($this->step));
        $this->step = min(4, $this->step + 1);
        $this->persistDraft();
    }

    public function previousStep(): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        $this->step = max(1, $this->step - 1);
        $this->persistDraft();
    }

    public function openSubmitConfirmation(): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        $this->step = 4;
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

        $this->step = 4;
        $this->persistDraft(true);

        $persona = $this->resolveOrCreatePersona();

        if ($persona->user) {
            $this->assignSpeakerRole($persona->user);
        } else {
            $credentials = $this->ensureSpeakerUserAccount($persona);

            if ($credentials) {
                $this->sendSpeakerCredentials($credentials);
            }
        }

        $this->conferencia->update([
            'speaker_profile_completed_at' => now(),
            'conference_content_completed_at' => now(),
            'speaker_onboarding_submitted_at' => now(),
            'speaker_onboarding_step' => 4,
        ]);

        $this->conferencia->refresh();
        $this->conferencia->load(['evento', 'conferencista.persona.user', 'speakerPersona.user']);
        $this->fillFromConference();
        $this->step = 4;
        $this->showSubmitConfirmation = false;

        session()->flash('message', 'La informacion fue enviada correctamente. Este enlace ahora solo mostrara el resumen.');
    }

    private function rules(): array
    {
        $personaId = $this->conferencia->conferencista?->persona?->id;
        $numeroCuentaRule = $this->requiresProfileNumber()
            ? 'required|string|max:50|unique:personas,numeroCuenta,' . $personaId
            : 'nullable|string|max:50|unique:personas,numeroCuenta,' . $personaId;
        $speakerPhotoRule = $this->currentSpeakerPhoto
            ? 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            : 'required|image|mimes:jpg,jpeg,png|max:2048';
        $conferencePhotoRule = $this->currentConferencePhoto
            ? 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            : 'required|image|mimes:jpg,jpeg,png|max:2048';

        return [
            'dni' => 'required|string|max:50|unique:personas,dni,' . $personaId,
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'correo' => 'required|email|max:255|unique:personas,correo,' . $personaId . '|unique:users,email,' . ($this->conferencia->conferencista?->persona?->IdUsuario ?? 'NULL'),
            'correoInstitucional' => 'nullable|email|max:255|unique:personas,correoInstitucional,' . $personaId,
            'fechaNacimiento' => 'nullable|date',
            'sexo' => 'required|string|max:50',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'numeroCuenta' => $numeroCuentaRule,
            'IdNacionalidad' => 'required|exists:nacionalidads,id',
            'IdTipoPerfil' => 'required|exists:tipoperfils,id',
            'nivel_academico' => 'required|in:Licenciatura,Maestria,Doctor,PosDoctorado',
            'descripcion_perfil' => 'required|string|max:1000',
            'conference_nombre' => 'required|string|max:255',
            'conference_descripcion' => 'required|string|max:1200',
            'speaker_photo' => $speakerPhotoRule,
            'conference_photo' => $conferencePhotoRule,
        ];
    }

    private function stepRules(int $step): array
    {
        return match ($step) {
            1 => Arr::only($this->rules(), self::STEP_FIELDS[1]),
            2 => Arr::only($this->rules(), self::STEP_FIELDS[2]),
            3 => Arr::only($this->rules(), self::STEP_FIELDS[3]),
            default => $this->rules(),
        };
    }

    private function fillFromConference(): void
    {
        $persona = $this->conferencia->speakerPersona ?: $this->conferencia->conferencista?->persona;
        $fullName = $this->conferencia->conferencista_nombre_invitado ?: '';
        [$invitedPrimerNombre, $invitedSegundoNombre, $invitedPrimerApellido, $invitedSegundoApellido] = $this->splitFullNameParts($fullName);
        [$legacyPrimerNombre, $legacySegundoNombre] = $this->splitPair($persona?->nombre ?? '');
        [$legacyPrimerApellido, $legacySegundoApellido] = $this->splitPair($persona?->apellido ?? '');

        $this->dni = $persona?->dni ?? '';
        $this->primer_nombre = $persona?->primer_nombre ?: $legacyPrimerNombre ?: $invitedPrimerNombre;
        $this->segundo_nombre = $persona?->segundo_nombre ?: $legacySegundoNombre ?: $invitedSegundoNombre;
        $this->primer_apellido = $persona?->primer_apellido ?: $legacyPrimerApellido ?: $invitedPrimerApellido;
        $this->segundo_apellido = $persona?->segundo_apellido ?: $legacySegundoApellido ?: $invitedSegundoApellido;
        $this->correo = $persona?->correo ?? '';
        $this->correoInstitucional = $persona?->correoInstitucional ?? '';
        $this->fechaNacimiento = $persona?->fechaNacimiento
            ? (is_string($persona->fechaNacimiento) ? $persona->fechaNacimiento : $persona->fechaNacimiento->format('Y-m-d'))
            : '';
        $this->sexo = $persona?->sexo ?? '';
        $this->direccion = $persona?->direccion ?? '';
        $this->telefono = $persona?->telefono ?? '';
        $this->numeroCuenta = $persona?->numeroCuenta ?? '';
        $this->IdNacionalidad = (string) ($persona?->IdNacionalidad ?? '');
        $this->IdTipoPerfil = (string) ($persona?->IdTipoPerfil ?? '');
        $this->nivel_academico = $this->conferencia->conferencista?->nivel_academico ?? '';
        $this->descripcion_perfil = $this->conferencia->conferencista?->descripcion ?? '';
        $this->conference_nombre = $this->conferencia->nombre ?? '';
        $this->conference_descripcion = $this->conferencia->descripcion ?? '';
        $this->currentSpeakerPhoto = $this->conferencia->conferencista?->foto;
        $this->currentConferencePhoto = $this->conferencia->foto;
        $this->speaker_photo = null;
        $this->conference_photo = null;
    }

    private function persistDraft(bool $validateAll = false): void
    {
        if ($this->isSubmitted()) {
            return;
        }

        if ($validateAll) {
            $validated = $this->validate($this->rules());
        } else {
            $validated = $this->validate($this->draftRules());
        }

        $persona = $this->resolveOrCreatePersona();
        $primerNombre = trim($validated['primer_nombre'] ?? $this->primer_nombre);
        $segundoNombre = trim($validated['segundo_nombre'] ?? $this->segundo_nombre);
        $primerApellido = trim($validated['primer_apellido'] ?? $this->primer_apellido);
        $segundoApellido = trim($validated['segundo_apellido'] ?? $this->segundo_apellido);

        $persona->update([
            'dni' => trim($validated['dni'] ?? $this->dni),
            'primer_nombre' => $primerNombre,
            'segundo_nombre' => $segundoNombre ?: null,
            'primer_apellido' => $primerApellido,
            'segundo_apellido' => $segundoApellido ?: null,
            'nombre' => trim($primerNombre . ' ' . $segundoNombre),
            'apellido' => trim($primerApellido . ' ' . $segundoApellido),
            'correo' => trim($validated['correo'] ?? $this->correo),
            'correoInstitucional' => $this->correoInstitucional ? trim($this->correoInstitucional) : null,
            'fechaNacimiento' => $this->fechaNacimiento ?: $persona->fechaNacimiento,
            'sexo' => $validated['sexo'] ?? $this->sexo,
            'direccion' => trim($validated['direccion'] ?? $this->direccion),
            'telefono' => trim($validated['telefono'] ?? $this->telefono),
            'numeroCuenta' => $this->numeroCuenta ? trim($this->numeroCuenta) : null,
            'IdNacionalidad' => $validated['IdNacionalidad'] ?? $this->IdNacionalidad,
            'IdTipoPerfil' => $validated['IdTipoPerfil'] ?? $this->IdTipoPerfil,
        ]);

        $speakerPhotoPath = $this->currentSpeakerPhoto;
        if ($this->speaker_photo) {
            $speakerPhotoPath = str_replace('public/', 'storage/', $this->speaker_photo->store('public/conferencistas'));
            $this->speaker_photo = null;
        }

        $conferencista = $this->resolveOrCreateConferencista($persona->id);

        $conferencista->update([
            'IdPersona' => $persona->id,
            'nivel_academico' => $this->nivel_academico ?: null,
            'descripcion' => $this->descripcion_perfil ? trim($this->descripcion_perfil) : null,
            'foto' => $speakerPhotoPath,
        ]);

        $conferencePhotoPath = $this->currentConferencePhoto;
        if ($this->conference_photo) {
            $conferencePhotoPath = str_replace('public/', 'storage/', $this->conference_photo->store('public/conferencias'));
            $this->conference_photo = null;
        }

        $this->conferencia->update([
            'idConferencista' => $conferencista->id,
            'speaker_persona_id' => $persona->id,
            'conferencista_nombre_invitado' => $this->speakerFullName(),
            'nombre' => $this->conference_nombre ? trim($this->conference_nombre) : $this->conferencia->nombre,
            'descripcion' => $this->conference_descripcion ? trim($this->conference_descripcion) : $this->conferencia->descripcion,
            'foto' => $conferencePhotoPath,
            'speaker_onboarding_step' => $this->step,
        ]);

        $this->conferencia->refresh();
        $this->conferencia->load(['evento', 'conferencista.persona.user', 'speakerPersona.user']);
        $this->currentSpeakerPhoto = $this->conferencia->conferencista?->foto;
        $this->currentConferencePhoto = $this->conferencia->foto;
        $this->draftSavedAt = now()->format('H:i');
    }

    private function draftRules(): array
    {
        $personaId = $this->conferencia->speakerPersona?->id ?: $this->conferencia->conferencista?->persona?->id;
        $userId = $this->linkedUserId();

        return [
            'dni' => 'nullable|string|max:50|unique:personas,dni,' . $personaId,
            'primer_nombre' => 'nullable|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'nullable|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'correo' => 'nullable|email|max:255|unique:personas,correo,' . $personaId . '|unique:users,email,' . ($userId ?? 'NULL'),
            'correoInstitucional' => 'nullable|email|max:255|unique:personas,correoInstitucional,' . $personaId,
            'fechaNacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'numeroCuenta' => 'nullable|string|max:50|unique:personas,numeroCuenta,' . $personaId,
            'IdNacionalidad' => 'nullable|exists:nacionalidads,id',
            'IdTipoPerfil' => 'nullable|exists:tipoperfils,id',
            'nivel_academico' => 'nullable|in:Licenciatura,Maestria,Doctor,PosDoctorado',
            'descripcion_perfil' => 'nullable|string|max:1000',
            'conference_nombre' => 'nullable|string|max:255',
            'conference_descripcion' => 'nullable|string|max:1200',
            'speaker_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'conference_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    private function resolveOrCreatePersona(): Persona
    {
        $persona = $this->conferencia->speakerPersona ?: $this->conferencia->conferencista?->persona;

        if ($persona) {
            return $persona;
        }

        return Persona::query()->create([
            'dni' => $this->dni ?: 'TMP-' . Str::uuid()->toString(),
            'primer_nombre' => $this->primer_nombre ?: 'Pendiente',
            'segundo_nombre' => $this->segundo_nombre ?: null,
            'primer_apellido' => $this->primer_apellido ?: 'Pendiente',
            'segundo_apellido' => $this->segundo_apellido ?: null,
            'nombre' => trim($this->primer_nombre . ' ' . $this->segundo_nombre) ?: 'Pendiente',
            'apellido' => trim($this->primer_apellido . ' ' . $this->segundo_apellido) ?: 'Pendiente',
            'correo' => $this->correo ?: 'tmp-' . Str::uuid()->toString() . '@placeholder.local',
            'correoInstitucional' => null,
            'fechaNacimiento' => $this->fechaNacimiento ?: now()->subYears(18)->format('Y-m-d'),
            'sexo' => $this->sexo ?: 'Pendiente',
            'direccion' => $this->direccion ?: 'Pendiente',
            'telefono' => $this->telefono ?: 'Pendiente',
            'numeroCuenta' => null,
            'IdNacionalidad' => $this->IdNacionalidad ?: Nacionalidad::query()->orderBy('id')->value('id'),
            'IdTipoPerfil' => $this->IdTipoPerfil ?: Tipoperfil::query()->orderBy('id')->value('id'),
        ]);
    }

    private function resolveOrCreateConferencista(int $personaId): Conferencista
    {
        $conferencista = $this->conferencia->conferencista;

        if ($conferencista) {
            return $conferencista;
        }

        $conferencista = Conferencista::query()->create([
            'IdPersona' => $personaId,
            'nivel_academico' => null,
            'descripcion' => null,
            'foto' => null,
            'firma' => null,
            'sello' => null,
        ]);

        $this->conferencia->setRelation('conferencista', $conferencista->load('persona.user'));

        return $conferencista;
    }

    private function resolveInitialStep(): int
    {
        if ($this->isSubmitted()) {
            return 4;
        }

        return max(1, min(4, (int) ($this->conferencia->speaker_onboarding_step ?? 1)));
    }

    private function isDraftProperty(string $property): bool
    {
        return in_array($property, array_merge(self::STEP_FIELDS[1], self::STEP_FIELDS[2], self::STEP_FIELDS[3]), true);
    }

    public function isSubmitted(): bool
    {
        return (bool) $this->conferencia->speaker_onboarding_submitted_at;
    }

    public function speakerFullName(): string
    {
        return trim(implode(' ', array_filter([
            trim($this->primer_nombre),
            trim($this->segundo_nombre),
            trim($this->primer_apellido),
            trim($this->segundo_apellido),
        ])));
    }

    public function requiresProfileNumber(): bool
    {
        return in_array($this->profileNumberType(), ['estudiante', 'empleado'], true);
    }

    public function profileNumberLabel(): string
    {
        return match ($this->profileNumberType()) {
            'estudiante' => 'Numero de cuenta estudiantil',
            'empleado' => 'Numero de empleado',
            default => 'Numero de identificacion adicional',
        };
    }

    public function profileNumberHelp(): string
    {
        return match ($this->profileNumberType()) {
            'estudiante' => 'Ingresa tu numero de cuenta estudiantil.',
            'empleado' => 'Ingresa tu numero de empleado.',
            default => '',
        };
    }

    public function profileTypeName(): string
    {
        if (! $this->IdTipoPerfil) {
            return '';
        }

        return Tipoperfil::query()->find($this->IdTipoPerfil)?->tipoperfil ?? '';
    }

    private function profileNumberType(): ?string
    {
        $profileName = Str::of($this->profileTypeName())->lower()->ascii()->value();

        if (str_contains($profileName, 'estudiante')) {
            return 'estudiante';
        }

        if (str_contains($profileName, 'empleado')) {
            return 'empleado';
        }

        return null;
    }

    private function academicLevels(): array
    {
        return [
            'Licenciatura',
            'Maestria',
            'Doctor',
            'PosDoctorado',
        ];
    }

    private function stepDefinitions(): array
    {
        return [
            ['number' => 1, 'title' => 'Paso 1', 'label' => 'Datos personales'],
            ['number' => 2, 'title' => 'Paso 2', 'label' => 'Perfil profesional'],
            ['number' => 3, 'title' => 'Paso 3', 'label' => 'Datos conferencia'],
            ['number' => 4, 'title' => 'Paso 4', 'label' => 'Resumen'],
        ];
    }

    private function splitFullNameParts(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if (count($parts) === 0) {
            return ['', '', '', ''];
        }

        if (count($parts) === 1) {
            return [$parts[0], '', '', ''];
        }

        if (count($parts) === 2) {
            return [$parts[0], '', $parts[1], ''];
        }

        if (count($parts) === 3) {
            return [$parts[0], '', $parts[1], $parts[2]];
        }

        return [$parts[0], $parts[1], $parts[2], implode(' ', array_slice($parts, 3))];
    }

    private function splitPair(?string $value): array
    {
        $parts = preg_split('/\s+/', trim((string) $value), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if (count($parts) === 0) {
            return ['', ''];
        }

        if (count($parts) === 1) {
            return [$parts[0] ?? '', ''];
        }

        $first = array_shift($parts);

        return [$first, implode(' ', $parts)];
    }

    private function personaFullName(Persona $persona): string
    {
        return trim(implode(' ', array_filter([
            trim((string) $persona->primer_nombre),
            trim((string) $persona->segundo_nombre),
            trim((string) $persona->primer_apellido),
            trim((string) $persona->segundo_apellido),
        ]))) ?: trim($persona->nombre . ' ' . $persona->apellido);
    }

    private function ensureSpeakerUserAccount(Persona $persona): ?array
    {
        if ($persona->user) {
            $this->assignSpeakerRole($persona->user);

            return null;
        }

        $email = trim((string) $persona->correo);

        if ($email === '') {
            return null;
        }

        $existingUser = User::query()->where('email', $email)->first();

        if ($existingUser) {
            $this->assignSpeakerRole($existingUser);
            $persona->update(['IdUsuario' => $existingUser->id]);

            return null;
        }

        $plainPassword = 'Evt-' . Str::upper(Str::random(10));
        $user = User::query()->create([
            'name' => $this->personaFullName($persona) ?: $this->speakerFullName() ?: 'Conferencista',
            'email' => $email,
            'password' => Hash::make($plainPassword),
        ]);

        $this->assignSpeakerRole($user);
        $persona->update(['IdUsuario' => $user->id]);

        return [
            'name' => $user->name,
            'email' => $email,
            'password' => $plainPassword,
        ];
    }

    private function sendSpeakerCredentials(array $credentials): void
    {
        try {
            Mail::to($credentials['email'])->send(new SpeakerAccessCredentialsMail(
                speakerName: $credentials['name'],
                email: $credentials['email'],
                password: $credentials['password'],
                eventName: $this->conferencia->evento?->nombreevento ?? 'Evento',
                conferenceName: $this->conference_nombre ?: $this->conferencia->nombre,
            ));
        } catch (Throwable $exception) {
            report($exception);

            session()->flash('warning', 'El usuario fue creado, pero no se pudo enviar el correo de credenciales. Revisa la configuracion de correo.');
        }
    }

    private function linkedUserId(): ?int
    {
        return $this->conferencia->speakerPersona?->IdUsuario
            ?: $this->conferencia->conferencista?->persona?->IdUsuario;
    }

    private function assignSpeakerRole(User $user): void
    {
        if (! method_exists($user, 'assignRole')) {
            return;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('conferencista')) {
            return;
        }

        if (method_exists($user, 'hasRole') && method_exists($user, 'syncRoles') && $user->hasRole('participante') && $user->roles()->count() === 1) {
            $user->syncRoles(['conferencista']);

            return;
        }

        $user->assignRole('conferencista');
    }
}
