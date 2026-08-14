<?php

namespace App\Livewire\Auth;

use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Tipoperfil;
use App\Services\ProfileDirectoryLookupService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;

class RegisterWizard extends Component
{
    public int $step = 1;
    public int $maxUnlockedStep = 1;

    public string $tipoperfil_id = '';
    public string $lookup_identifier = '';
    public bool $identifierChecked = false;
    public bool $lookupSuccess = false;
    public bool $dataLocked = false;
    public string $lookupMessage = '';

    public string $primer_nombre = '';
    public string $segundo_nombre = '';
    public string $primer_apellido = '';
    public string $segundo_apellido = '';
    public string $dni = '';
    public string $numeroCuenta = '';
    public string $numeroEmpleado = '';
    public string $correoInstitucional = '';
    public string $fechaNacimiento = '';
    public string $sexo = '';
    public string $telefono = '';
    public string $direccion = '';
    public string $IdNacionalidad = '';

    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $showPassword = false;
    public bool $showPasswordConfirmation = false;
    public bool $terms = false;
    public bool $showConfirmationModal = false;

    public Collection $tiposPerfil;
    public Collection $nacionalidades;

    protected $validationAttributes = [
        'tipoperfil_id' => 'tipo de perfil',
        'lookup_identifier' => 'identificador',
        'primer_nombre' => 'primer nombre',
        'segundo_nombre' => 'segundo nombre',
        'primer_apellido' => 'primer apellido',
        'segundo_apellido' => 'segundo apellido',
        'dni' => 'identidad',
        'numeroCuenta' => 'numero de cuenta',
        'numeroEmpleado' => 'numero de empleado',
        'correoInstitucional' => 'correo institucional',
        'fechaNacimiento' => 'fecha de nacimiento',
        'sexo' => 'sexo',
        'telefono' => 'telefono',
        'direccion' => 'direccion',
        'IdNacionalidad' => 'nacionalidad',
        'email' => 'correo del usuario',
        'password' => 'contrasena',
        'password_confirmation' => 'confirmacion de contrasena',
        'terms' => 'terminos y condiciones',
    ];

    public function mount(): void
    {
        $this->tiposPerfil = Tipoperfil::query()
            ->with(['apiIntegrations' => fn ($query) => $query->where('is_active', true)->latest('id')])
            ->orderBy('tipoperfil')
            ->get();

        $this->nacionalidades = Nacionalidad::query()
            ->orderBy('nombreNacionalidad')
            ->get(['id', 'nombreNacionalidad']);

        $this->setDefaultNationality();
    }

    public function render()
    {
        return view('livewire.auth.register-wizard', [
            'stepDefinitions' => $this->stepDefinitions(),
        ]);
    }

    public function selectProfile(int $profileId): void
    {
        $this->tipoperfil_id = (string) $profileId;
        $this->step = 1;
        $this->maxUnlockedStep = 2;
        $this->resetLookupState();
        $this->resetRegistrantData();
        $this->resetCredentials();
        $this->resetValidation();
    }

    public function updatedLookupIdentifier(): void
    {
        $this->resetLookupState(false);
        $this->resetRegistrantData();
        $this->tipoperfil_id = '';
    }

    public function previousStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function goToStep(int $targetStep): void
    {
        if ($targetStep <= $this->maxUnlockedStep) {
            $this->step = $targetStep;
        }
    }

    public function nextStep(): void
    {
        $this->resetErrorBag();

        if ($this->step === 1) {
            $this->verifyIdentity();

            return;
        }

        match ($this->step) {
            2 => $this->validatePersonStep(),
            3 => $this->validateCredentialsStep(),
            4 => $this->openConfirmationModal(),
            default => null,
        };

        if ($this->step < 4) {
            $this->maxUnlockedStep = max($this->maxUnlockedStep, min(4, $this->step + 1));
            $this->step = min(4, $this->step + 1);
        }
    }

    public function verifyIdentity(): void
    {
        $this->resetErrorBag();
        $this->validate([
            'lookup_identifier' => ['required', 'string', 'max:100'],
        ]);

        $identifier = trim($this->lookup_identifier);
        $this->resetRegistrantData();
        $this->lookup_identifier = $identifier;
        $this->dni = $identifier;

        $existingPerson = $this->findExistingPersonByIdentifier($identifier);

        if ($existingPerson) {
            session()->flash('status', 'Esta persona ya se encuentra registrada en el sistema. Inicia sesion para continuar.');
            $this->redirectRoute('login', navigate: true);

            return;
        }

        $this->identifierChecked = true;
        $this->lookupSuccess = false;
        $this->dataLocked = false;
        $this->tipoperfil_id = (string) optional($this->externalProfile())->id;

        $profile = $this->profileForApiLookup();

        if (! $profile) {
            $this->lookupMessage = 'No se encontró una API activa para consultar. Completa la información manualmente.';
            $this->maxUnlockedStep = max($this->maxUnlockedStep, 2);
            $this->step = 2;
            return;
        }

        $result = app(ProfileDirectoryLookupService::class)->lookup($profile, $identifier);

        if (! ($result['success'] ?? false)) {
            $this->lookupMessage = $result['message'] ?? 'No se encontró información automática para esta identidad. Completa los datos manualmente.';
            $this->maxUnlockedStep = max($this->maxUnlockedStep, 2);
            $this->step = 2;

            return;
        }

        $data = $result['data'] ?? [];
        $this->fillFromLookup($data);
        $this->applyIdentifierToForm();
        $this->inferProfileFromCurrentData();

        $this->lookupSuccess = true;
        $this->dataLocked = false;
        $this->lookupMessage = 'Datos encontrados correctamente en la API.';
        $this->maxUnlockedStep = max($this->maxUnlockedStep, 2);
        $this->step = 2;
    }

    public function togglePasswordVisibility(): void
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function togglePasswordConfirmationVisibility(): void
    {
        $this->showPasswordConfirmation = ! $this->showPasswordConfirmation;
    }

    public function register(): mixed
    {
        $this->resetErrorBag();
        $this->validateStepOne();
        $this->validatePersonStep();
        $this->validateCredentialsStep(true);

        App::make(CreatesNewUsers::class)->create([
            'skip_profile_lookup' => true,
            'name' => $this->fullNameForBackend(),
            'tipoperfil_id' => $this->tipoperfil_id,
            'lookup_identifier' => $this->lookup_identifier,
            'primer_nombre' => $this->primer_nombre,
            'segundo_nombre' => $this->segundo_nombre,
            'primer_apellido' => $this->primer_apellido,
            'segundo_apellido' => $this->segundo_apellido,
            'nombre' => $this->givenNamesForBackend(),
            'apellido' => $this->surnamesForBackend(),
            'dni' => $this->dni,
            'numeroCuenta' => $this->numeroCuenta,
            'numeroEmpleado' => $this->numeroEmpleado,
            'correoInstitucional' => $this->correoInstitucional,
            'fechaNacimiento' => $this->fechaNacimiento,
            'sexo' => $this->sexo,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'IdNacionalidad' => $this->IdNacionalidad,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'terms' => $this->terms,
        ]);

        session()->flash('status', 'Tu cuenta fue creada correctamente. Ahora puedes iniciar sesion con tus credenciales.');

        return redirect()->route('login');
    }

    public function openConfirmationModal(): void
    {
        $this->resetErrorBag();
        $this->validateStepOne();
        $this->validatePersonStep();
        $this->validateCredentialsStep(true);

        $this->showConfirmationModal = true;
    }

    public function closeConfirmationModal(): void
    {
        $this->showConfirmationModal = false;
    }

    public function selectedProfile(): ?Tipoperfil
    {
        return $this->tiposPerfil->firstWhere('id', (int) $this->tipoperfil_id);
    }

    public function isProfileSelected(int $profileId): bool
    {
        return (int) $this->tipoperfil_id === $profileId;
    }

    public function lookupLabel(): string
    {
        return 'Número de identidad';
    }

    public function showNumeroCuenta(): bool
    {
        return $this->numeroCuenta !== '';
    }

    public function showNumeroEmpleado(): bool
    {
        return $this->numeroEmpleado !== '';
    }

    public function usesApiLookup(): bool
    {
        return (bool) $this->selectedProfile()?->apiIntegrations?->isNotEmpty();
    }

    public function areResolvedPersonFieldsReadOnly(): bool
    {
        return false;
    }

    public function nationalityName(): string
    {
        return (string) optional($this->nacionalidades->firstWhere('id', (int) $this->IdNacionalidad))->nombreNacionalidad ?: 'Pendiente';
    }

    public function fullNameForBackend(): string
    {
        return trim($this->givenNamesForBackend() . ' ' . $this->surnamesForBackend()) ?: 'Usuario participante';
    }

    public function givenNamesForBackend(): string
    {
        return trim($this->primer_nombre . ' ' . $this->segundo_nombre);
    }

    public function surnamesForBackend(): string
    {
        return trim($this->primer_apellido . ' ' . $this->segundo_apellido);
    }

    public function inferredProfileName(): string
    {
        return (string) ($this->selectedProfile()?->tipoperfil ?? 'Externo');
    }

    private function validateStepOne(): void
    {
        $this->validate([
            'lookup_identifier' => ['required', 'string', 'max:100'],
            'tipoperfil_id' => ['required', 'exists:tipoperfils,id'],
        ]);

        if (! $this->identifierChecked) {
            throw ValidationException::withMessages([
                'lookup_identifier' => 'Debes buscar la identidad antes de continuar.',
            ]);
        }
    }

    private function validatePersonStep(): void
    {
        $this->validate([
            'lookup_identifier' => ['required', 'string', 'max:100'],
            'tipoperfil_id' => ['required', 'exists:tipoperfils,id'],
            'primer_nombre' => ['required', 'string', 'max:100'],
            'segundo_nombre' => ['nullable', 'string', 'max:100'],
            'primer_apellido' => ['required', 'string', 'max:100'],
            'segundo_apellido' => ['nullable', 'string', 'max:100'],
            'dni' => ['required', 'string', 'max:50', Rule::unique('personas', 'dni')],
            'correoInstitucional' => ['nullable', 'email', 'max:255', Rule::unique('personas', 'correoInstitucional')],
            'fechaNacimiento' => ['required', 'date'],
            'sexo' => ['required', 'string', 'max:20'],
            'telefono' => ['required', 'string', 'max:30'],
            'direccion' => ['required', 'string', 'max:255'],
            'IdNacionalidad' => ['required', 'exists:nacionalidads,id'],
        ]);

        if (! $this->identifierChecked) {
            throw ValidationException::withMessages([
                'lookup_identifier' => 'Debes verificar primero la identidad antes de continuar.',
            ]);
        }

        if ($this->showNumeroCuenta()) {
            $this->validate([
                'numeroCuenta' => ['required', 'string', 'max:50', Rule::unique('personas', 'numeroCuenta')],
            ]);
        }

        if ($this->showNumeroEmpleado()) {
            $this->validate([
                'numeroEmpleado' => ['required', 'string', 'max:50', Rule::unique('personas', 'numeroEmpleado')],
            ]);
        }
    }

    private function validateAddressStep(): void
    {
        $this->validate([
            'telefono' => ['required', 'string', 'max:30'],
            'direccion' => ['required', 'string', 'max:255'],
            'IdNacionalidad' => ['required', 'exists:nacionalidads,id'],
        ]);
    }

    private function validateCredentialsStep(bool $final = false): void
    {
        $rules = [
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email'), Rule::unique('personas', 'correo')],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if (Jetstream::hasTermsAndPrivacyPolicyFeature() && $final) {
            $rules['terms'] = ['accepted'];
        }

        $this->validate($rules);
    }

    private function fillFromLookup(array $data): void
    {
        [$legacyPrimerNombre, $legacySegundoNombre] = $this->splitNameParts($data['nombre'] ?? '');
        [$legacyPrimerApellido, $legacySegundoApellido] = $this->splitNameParts($data['apellido'] ?? '');

        $this->primer_nombre = $this->preferredText($data['primer_nombre'] ?? null, $this->primer_nombre, $legacyPrimerNombre);
        $this->segundo_nombre = $this->preferredText($data['segundo_nombre'] ?? null, $this->segundo_nombre, $legacySegundoNombre);
        $this->primer_apellido = $this->preferredText($data['primer_apellido'] ?? null, $this->primer_apellido, $legacyPrimerApellido);
        $this->segundo_apellido = $this->preferredText($data['segundo_apellido'] ?? null, $this->segundo_apellido, $legacySegundoApellido);
        $this->dni = trim((string) ($data['dni'] ?? $this->dni));
        $this->numeroCuenta = trim((string) ($data['numeroCuenta'] ?? $this->numeroCuenta));
        $this->numeroEmpleado = trim((string) ($data['numeroEmpleado'] ?? $this->numeroEmpleado));
        $this->correoInstitucional = trim((string) ($data['correoInstitucional'] ?? $this->correoInstitucional));
        $this->fechaNacimiento = $this->normalizeDateValue($data['fechaNacimiento'] ?? $this->fechaNacimiento);
        $this->sexo = $this->normalizeSexValue((string) ($data['sexo'] ?? $this->sexo));
        $this->telefono = trim((string) ($data['telefono'] ?? $this->telefono));
        $this->direccion = trim((string) ($data['direccion'] ?? $this->direccion));
    }

    private function applyIdentifierToForm(): void
    {
        $identifier = trim($this->lookup_identifier);

        if ($identifier === '') {
            return;
        }

        if ($this->dni === '') {
            $this->dni = $identifier;
        }
    }

    private function findExistingPersonByIdentifier(string $identifier): ?Persona
    {
        if ($identifier === '') {
            return null;
        }

        return Persona::query()
            ->where('dni', $identifier)
            ->orWhere('numeroCuenta', $identifier)
            ->orWhere('numeroEmpleado', $identifier)
            ->first();
    }

    private function normalizeDateValue(mixed $value): string
    {
        $date = trim((string) $value);

        if ($date === '') {
            return '';
        }

        foreach (['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Throwable) {
                //
            }
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Throwable) {
            return '';
        }
    }

    private function normalizeSexValue(string $value): string
    {
        $normalized = mb_strtolower(trim($value));

        return match ($normalized) {
            'm', 'masculino', 'male', 'hombre' => 'M',
            'f', 'femenino', 'female', 'mujer' => 'F',
            '' => '',
            default => 'Otro',
        };
    }

    private function resetLookupState(bool $clearIdentifier = true): void
    {
        $this->identifierChecked = false;
        $this->lookupSuccess = false;
        $this->dataLocked = false;
        $this->lookupMessage = '';

        if ($clearIdentifier) {
            $this->lookup_identifier = '';
        }
    }

    private function clearResolvedPersonData(): void
    {
        $this->primer_nombre = '';
        $this->segundo_nombre = '';
        $this->primer_apellido = '';
        $this->segundo_apellido = '';
        $this->dni = '';
        $this->numeroCuenta = '';
        $this->numeroEmpleado = '';
        $this->correoInstitucional = '';
        $this->fechaNacimiento = '';
        $this->sexo = '';
        $this->setDefaultNationality();
    }

    private function resetRegistrantData(): void
    {
        $this->clearResolvedPersonData();
        $this->telefono = '';
        $this->direccion = '';
        $this->setDefaultNationality();
    }

    private function resetCredentials(): void
    {
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->showPassword = false;
        $this->showPasswordConfirmation = false;
        $this->terms = false;
    }

    private function stepDefinitions(): array
    {
        return [
            ['number' => 1, 'title' => 'Paso 1', 'label' => 'Identidad'],
            ['number' => 2, 'title' => 'Paso 2', 'label' => 'Datos personales'],
            ['number' => 3, 'title' => 'Paso 3', 'label' => 'Usuario'],
            ['number' => 4, 'title' => 'Paso 4', 'label' => 'Confirmación'],
        ];
    }

    private function profileForApiLookup(): ?Tipoperfil
    {
        return $this->externalProfile()
            ?? $this->tiposPerfil->first(fn (Tipoperfil $profile) => $profile->apiIntegrations->isNotEmpty());
    }

    private function inferProfileFromCurrentData(): void
    {
        $profile = filled($this->numeroEmpleado)
            ? $this->employeeProfile()
            : (filled($this->numeroCuenta) ? $this->studentProfile() : $this->externalProfile());

        if ($profile) {
            $this->tipoperfil_id = (string) $profile->id;
        }
    }

    private function employeeProfile(): ?Tipoperfil
    {
        return $this->profileByName('emple');
    }

    private function studentProfile(): ?Tipoperfil
    {
        return $this->profileByName('estudian');
    }

    private function externalProfile(): ?Tipoperfil
    {
        return $this->profileByName('extern');
    }

    private function profileByName(string $needle): ?Tipoperfil
    {
        return $this->tiposPerfil->first(fn (Tipoperfil $profile) => str_contains(mb_strtolower((string) $profile->tipoperfil), $needle));
    }

    private function setDefaultNationality(): void
    {
        $default = $this->nacionalidades
            ->first(fn (Nacionalidad $nacionalidad) => str_contains(mb_strtolower((string) $nacionalidad->nombreNacionalidad), 'hondur'));

        if ($default) {
            $this->IdNacionalidad = (string) $default->id;
        }
    }

    private function splitNameParts(mixed $value): array
    {
        $parts = preg_split('/\s+/', trim((string) $value), 2, PREG_SPLIT_NO_EMPTY) ?: [];

        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    private function preferredText(mixed ...$values): string
    {
        foreach ($values as $value) {
            $normalized = trim((string) $value);

            if ($normalized !== '') {
                return $normalized;
            }
        }

        return '';
    }
}
