<?php

namespace App\Livewire\Evento;

use App\Models\Evento;
use App\Models\EventoRegistro;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Tipoperfil;
use App\Services\ProfileDirectoryLookupService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class PublicEventRegistration extends Component
{
    public Evento $evento;
    public bool $showRegistrationModal = false;
    public string $tipoperfil_id = '';
    public string $lookup_identifier = '';
    public bool $lookupResolved = false;
    public bool $manualEntryEnabled = false;
    public string $lookupMessage = '';
    public bool $registrationSuccess = false;
    public bool $alreadyRegistered = false;

    public string $dni = '';
    public string $numeroCuenta = '';
    public string $numeroEmpleado = '';
    public string $nombre = '';
    public string $apellido = '';
    public string $correo = '';
    public string $correoInstitucional = '';
    public string $fechaNacimiento = '';
    public string $sexo = '';
    public string $direccion = '';
    public string $telefono = '';
    public string $IdNacionalidad = '';

    public function mount(Evento $evento): void
    {
        $this->evento = $evento->load(['precios.moneda', 'registros']);
        $this->prefillFromAuthenticatedPersona();
    }

    public function render()
    {
        return view('livewire.Evento.public-event-registration', [
            'tiposPerfil' => Tipoperfil::query()->orderBy('tipoperfil')->get(),
            'nacionalidades' => Nacionalidad::query()->orderBy('nombreNacionalidad')->get(),
            'selectedProfile' => $this->selectedProfile(),
            'pricePreview' => $this->pricePreview(),
        ]);
    }

    public function openRegistrationModal(): void
    {
        $this->showRegistrationModal = true;
        $this->registrationSuccess = false;
        $this->alreadyRegistered = false;
    }

    public function closeRegistrationModal(): void
    {
        $this->showRegistrationModal = false;
        $this->resetLookupState();
        $this->resetValidation();
        $this->prefillFromAuthenticatedPersona();
    }

    public function updatedTipoperfilId(): void
    {
        $this->resetLookupState();
        $this->clearIdentityFields();
    }

    public function lookupProfileData(ProfileDirectoryLookupService $lookupService): void
    {
        $profile = $this->selectedProfile();

        if (! $profile) {
            $this->addError('tipoperfil_id', 'Selecciona un tipo de perfil.');

            return;
        }

        $this->validate([
            'tipoperfil_id' => 'required|exists:tipoperfils,id',
            'lookup_identifier' => 'required|string|max:100',
        ]);

        $result = $lookupService->lookup($profile, trim($this->lookup_identifier));

        if (! $result['success']) {
            $this->lookupResolved = false;
            $this->lookupMessage = $result['message'];
            $this->manualEntryEnabled = ! $profile->requiere_api && $profile->permite_registro_manual;

            if ($profile->requiere_api) {
                $this->addError('lookup_identifier', $result['message']);
            }

            return;
        }

        $this->fillFieldsFromLookup($result['data'], $profile);
        $this->lookupResolved = true;
        $this->manualEntryEnabled = ! $profile->requiere_api && $profile->permite_registro_manual;
        $this->lookupMessage = $result['message'];
    }

    public function enableManualEntry(): void
    {
        $profile = $this->selectedProfile();

        if (! $profile || ! $profile->permite_registro_manual) {
            return;
        }

        $this->manualEntryEnabled = true;
        $this->lookupMessage = 'Completa manualmente la información para continuar con la inscripción.';
    }

    public function register(): void
    {
        $profile = $this->selectedProfile();

        if (! $profile) {
            $this->addError('tipoperfil_id', 'Selecciona un tipo de perfil.');

            return;
        }

        if ($profile->requiere_api && ! $this->lookupResolved) {
            $this->addError('lookup_identifier', 'Primero debes consultar la información desde la API para este tipo de perfil.');

            return;
        }

        if (! $profile->requiere_api && ! $this->lookupResolved && ! $this->manualEntryEnabled) {
            $this->manualEntryEnabled = true;
        }

        $rules = [
            'tipoperfil_id' => 'required|exists:tipoperfils,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'correoInstitucional' => 'nullable|email|max:255',
            'fechaNacimiento' => 'required|date',
            'sexo' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:30',
            'IdNacionalidad' => 'required|exists:nacionalidads,id',
        ];

        $identifierField = $profile->tipo_identificador;

        if ($identifierField === 'dni') {
            $rules['dni'] = 'required|string|max:50';
        }

        if ($identifierField === 'numeroCuenta') {
            $rules['numeroCuenta'] = 'required|string|max:50';
        }

        if ($identifierField === 'numeroEmpleado') {
            $rules['numeroEmpleado'] = 'required|string|max:50';
        }

        $validated = $this->validate($rules);

        $persona = $this->resolvePersona($profile, $validated);

        $existingRegistration = EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->where('persona_id', $persona->id)
            ->exists();

        if ($existingRegistration) {
            $this->alreadyRegistered = true;
            return;
        }

        $price = $this->resolvePriceForProfile((int) $profile->id);

        EventoRegistro::query()->create([
            'evento_id' => $this->evento->id,
            'persona_id' => $persona->id,
            'tipoperfil_id' => $profile->id,
            'precio_aplicado' => $price['amount'],
            'detalle_precio' => $price['label'],
            'estado' => 'registrado',
        ]);

        $this->evento->refresh();
        $this->registrationSuccess = true;
        $this->alreadyRegistered = false;
        $this->lookupMessage = 'La inscripción al evento se realizó correctamente.';
    }

    private function resolvePersona(Tipoperfil $profile, array $validated): Persona
    {
        $identifierField = $profile->tipo_identificador;
        $identifierValue = match ($identifierField) {
            'numeroCuenta' => trim($validated['numeroCuenta'] ?? ''),
            'numeroEmpleado' => trim($validated['numeroEmpleado'] ?? ''),
            default => trim($validated['dni'] ?? ''),
        };

        $query = Persona::query();

        if (filled($identifierField) && filled($identifierValue)) {
            $query->where($identifierField, $identifierValue);
        } else {
            $query->where('correo', trim($validated['correo']));
        }

        $persona = $query->first() ?? new Persona();

        $persona->fill([
            'IdUsuario' => $persona->IdUsuario,
            'dni' => trim($validated['dni'] ?? $this->fallbackDni($profile)),
            'nombre' => trim($validated['nombre']),
            'apellido' => trim($validated['apellido']),
            'correo' => trim($validated['correo']),
            'correoInstitucional' => filled($validated['correoInstitucional']) ? trim($validated['correoInstitucional']) : null,
            'fechaNacimiento' => $validated['fechaNacimiento'],
            'sexo' => trim($validated['sexo']),
            'direccion' => trim($validated['direccion']),
            'telefono' => trim($validated['telefono']),
            'numeroCuenta' => filled($validated['numeroCuenta'] ?? null) ? trim($validated['numeroCuenta']) : null,
            'numeroEmpleado' => filled($validated['numeroEmpleado'] ?? null) ? trim($validated['numeroEmpleado']) : null,
            'IdNacionalidad' => $validated['IdNacionalidad'],
            'IdTipoPerfil' => $profile->id,
        ]);

        if (! $persona->exists) {
            $persona->created_by = Auth::id() ?? 0;
        }

        $persona->save();

        return $persona;
    }

    private function resolvePriceForProfile(int $profileId): array
    {
        if ($this->evento->tipo_acceso !== 'pagada') {
            return [
                'amount' => 0,
                'label' => 'Evento gratuito',
                'currency_symbol' => null,
                'currency_code' => null,
            ];
        }

        $today = now()->toDateString();
        $prices = $this->evento->precios->where('IdTipoPerfil', $profileId)->values();

        $range = $prices->first(function ($price) use ($today) {
            return ! $price->es_precio_evento_dia
                && $price->fecha_inicio?->format('Y-m-d') <= $today
                && $price->fecha_fin?->format('Y-m-d') >= $today;
        });

        if ($range) {
            return [
                'amount' => (float) $range->precio,
                'label' => $range->categoria_nombre ?: 'Rango configurado',
                'currency_symbol' => $range->moneda?->simbolo,
                'currency_code' => $range->moneda?->codigo,
            ];
        }

        $eventDayPrice = $prices->firstWhere('es_precio_evento_dia', true);

        return [
            'amount' => (float) ($eventDayPrice->precio ?? 0),
            'label' => $eventDayPrice?->categoria_nombre ?: 'Precio del día del evento',
            'currency_symbol' => $eventDayPrice?->moneda?->simbolo,
            'currency_code' => $eventDayPrice?->moneda?->codigo,
        ];
    }

    private function selectedProfile(): ?Tipoperfil
    {
        if (! filled($this->tipoperfil_id)) {
            return null;
        }

        return Tipoperfil::query()->find($this->tipoperfil_id);
    }

    private function pricePreview(): array
    {
        $profile = $this->selectedProfile();

        if (! $profile) {
            return ['amount' => null, 'label' => null, 'currency_symbol' => null, 'currency_code' => null];
        }

        return $this->resolvePriceForProfile((int) $profile->id);
    }

    private function fillFieldsFromLookup(array $data, Tipoperfil $profile): void
    {
        $this->dni = trim((string) ($data['dni'] ?? $this->fallbackDni($profile)));
        $this->numeroCuenta = trim((string) ($data['numeroCuenta'] ?? ''));
        $this->numeroEmpleado = trim((string) ($data['numeroEmpleado'] ?? ''));
        $this->nombre = trim((string) ($data['nombre'] ?? ''));
        $this->apellido = trim((string) ($data['apellido'] ?? ''));
        $this->correo = trim((string) ($data['correo'] ?? ''));
        $this->correoInstitucional = trim((string) ($data['correoInstitucional'] ?? ''));
        $this->fechaNacimiento = trim((string) ($data['fechaNacimiento'] ?? ''));
        $this->sexo = trim((string) ($data['sexo'] ?? ''));
        $this->direccion = trim((string) ($data['direccion'] ?? ''));
        $this->telefono = trim((string) ($data['telefono'] ?? ''));
    }

    private function prefillFromAuthenticatedPersona(): void
    {
        $persona = Auth::user()?->persona;

        if (! $persona) {
            return;
        }

        $this->tipoperfil_id = (string) ($persona->IdTipoPerfil ?? '');
        $this->dni = $persona->dni ?? '';
        $this->numeroCuenta = $persona->numeroCuenta ?? '';
        $this->numeroEmpleado = $persona->numeroEmpleado ?? '';
        $this->nombre = $persona->nombre ?? '';
        $this->apellido = $persona->apellido ?? '';
        $this->correo = $persona->correo ?? (Auth::user()->email ?? '');
        $this->correoInstitucional = $persona->correoInstitucional ?? '';
        $this->fechaNacimiento = $persona->fechaNacimiento ? \Carbon\Carbon::parse($persona->fechaNacimiento)->format('Y-m-d') : '';
        $this->sexo = $persona->sexo ?? '';
        $this->direccion = $persona->direccion ?? '';
        $this->telefono = $persona->telefono ?? '';
        $this->IdNacionalidad = (string) ($persona->IdNacionalidad ?? '');
    }

    private function clearIdentityFields(): void
    {
        $this->lookup_identifier = '';
        $this->dni = '';
        $this->numeroCuenta = '';
        $this->numeroEmpleado = '';
        $this->nombre = '';
        $this->apellido = '';
        $this->correo = '';
        $this->correoInstitucional = '';
        $this->fechaNacimiento = '';
        $this->sexo = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->IdNacionalidad = '';
    }

    private function resetLookupState(): void
    {
        $this->lookupResolved = false;
        $this->manualEntryEnabled = false;
        $this->lookupMessage = '';
    }

    private function fallbackDni(Tipoperfil $profile): string
    {
        if ($profile->tipo_identificador === 'dni' && filled($this->lookup_identifier)) {
            return trim($this->lookup_identifier);
        }

        return 'TMP-' . Str::upper(Str::random(12));
    }
}
