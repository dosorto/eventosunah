<?php

namespace App\Actions\Fortify;

use App\Models\Persona;
use App\Models\Tipoperfil;
use App\Models\User;
use App\Services\ProfileDirectoryLookupService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'tipoperfil_id' => ['required', 'exists:tipoperfils,id'],
            'IdNacionalidad' => ['required', 'exists:nacionalidads,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:personas,correo'],
            'password' => $this->passwordRules(),
            'lookup_identifier' => ['nullable', 'string', 'max:100'],
            'dni' => ['nullable', 'string', 'max:50'],
            'numeroCuenta' => ['nullable', 'string', 'max:50'],
            'numeroEmpleado' => ['nullable', 'string', 'max:50'],
            'correoInstitucional' => ['nullable', 'string', 'email', 'max:255'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $profile = Tipoperfil::query()
            ->with(['apiIntegrations' => fn ($query) => $query->where('is_active', true)->latest('id')])
            ->findOrFail((int) $input['tipoperfil_id']);

        $usesApiLookup = empty($input['skip_profile_lookup']) && $profile->apiIntegrations->isNotEmpty();
        $lookupData = $usesApiLookup
            ? $this->lookupProfileData($profile, $input)
            : [];

        $payload = $this->normalizedPayload($input, $lookupData, $profile);

        Validator::make($payload, [
            'tipoperfil_id' => ['required', 'exists:tipoperfils,id'],
            'IdNacionalidad' => ['required', 'exists:nacionalidads,id'],
            'primer_nombre' => ['required', 'string', 'max:100'],
            'segundo_nombre' => ['nullable', 'string', 'max:100'],
            'primer_apellido' => ['required', 'string', 'max:100'],
            'segundo_apellido' => ['nullable', 'string', 'max:100'],
            'dni' => ['required', 'string', 'max:50', 'unique:personas,dni'],
            'correoInstitucional' => ['nullable', 'string', 'email', 'max:255', 'unique:personas,correoInstitucional'],
            'fechaNacimiento' => ['required', 'date'],
            'sexo' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:30'],
            'numeroCuenta' => [
                $payload['requires_numero_cuenta'] ? 'required' : 'nullable',
                'string',
                'max:50',
                'unique:personas,numeroCuenta',
            ],
            'numeroEmpleado' => [
                $payload['requires_numero_empleado'] ? 'required' : 'nullable',
                'string',
                'max:50',
                'unique:personas,numeroEmpleado',
            ],
        ], [], [
            'tipoperfil_id' => 'tipo de perfil',
            'IdNacionalidad' => 'nacionalidad',
            'fechaNacimiento' => 'fecha de nacimiento',
            'correoInstitucional' => 'correo institucional',
            'numeroCuenta' => 'numero de cuenta',
            'numeroEmpleado' => 'numero de empleado',
            'primer_nombre' => 'primer nombre',
            'segundo_nombre' => 'segundo nombre',
            'primer_apellido' => 'primer apellido',
            'segundo_apellido' => 'segundo apellido',
        ])->validate();

        $user = User::create([
            'name' => trim($payload['nombre'] . ' ' . $payload['apellido']),
            'email' => trim((string) $input['email']),
            'password' => Hash::make($input['password']),
        ]);

        if (method_exists($user, 'hasAnyRole') && ! $user->hasAnyRole(['participante', 'conferencista', 'admin-eventos', 'gestor-contenido', 'super-admin'])) {
            $user->assignRole('participante');
        }

        Persona::query()->create([
            'IdUsuario' => $user->id,
            'dni' => $payload['dni'],
            'primer_nombre' => $this->formatName($payload['primer_nombre']),
            'segundo_nombre' => filled($payload['segundo_nombre']) ? $this->formatName($payload['segundo_nombre']) : null,
            'primer_apellido' => $this->formatName($payload['primer_apellido']),
            'segundo_apellido' => filled($payload['segundo_apellido']) ? $this->formatName($payload['segundo_apellido']) : null,
            'correo' => trim((string) $input['email']),
            'correoInstitucional' => $payload['correoInstitucional'] ?: null,
            'fechaNacimiento' => $payload['fechaNacimiento'],
            'sexo' => $payload['sexo'],
            'direccion' => $payload['direccion'],
            'telefono' => $payload['telefono'],
            'numeroCuenta' => $payload['numeroCuenta'] ?: null,
            'numeroEmpleado' => $payload['numeroEmpleado'] ?: null,
            'IdNacionalidad' => (int) $payload['IdNacionalidad'],
            'IdTipoPerfil' => (int) $payload['tipoperfil_id'],
            'created_by' => $user->id,
        ]);

        return $user;
    }

    private function lookupProfileData(Tipoperfil $profile, array $input): array
    {
        $identifier = trim((string) ($input['lookup_identifier'] ?? ''));

        if ($identifier === '') {
            throw ValidationException::withMessages([
                'lookup_identifier' => 'Debes ingresar el identificador para consultar la API del perfil seleccionado.',
            ]);
        }

        $result = app(ProfileDirectoryLookupService::class)->lookup($profile, $identifier);

        if (! ($result['success'] ?? false)) {
            throw ValidationException::withMessages([
                'lookup_identifier' => $result['message'] ?? 'No fue posible consultar la API configurada para este perfil.',
            ]);
        }

        return $result['data'] ?? [];
    }

    private function normalizedPayload(array $input, array $lookupData, Tipoperfil $profile): array
    {
        $identifierField = $profile->tipo_identificador ?: 'dni';
        $identifierValue = trim((string) ($input['lookup_identifier'] ?? ''));
        [$primerNombre, $segundoNombre] = $this->resolveSplitPair($input, $lookupData, 'primer_nombre', 'segundo_nombre', 'nombre');
        [$primerApellido, $segundoApellido] = $this->resolveSplitPair($input, $lookupData, 'primer_apellido', 'segundo_apellido', 'apellido');

        return [
            'tipoperfil_id' => (string) ($input['tipoperfil_id'] ?? ''),
            'IdNacionalidad' => (string) ($input['IdNacionalidad'] ?? ''),
            'primer_nombre' => $primerNombre,
            'segundo_nombre' => $segundoNombre,
            'primer_apellido' => $primerApellido,
            'segundo_apellido' => $segundoApellido,
            'nombre' => trim($primerNombre . ' ' . $segundoNombre),
            'apellido' => trim($primerApellido . ' ' . $segundoApellido),
            'dni' => $this->normalizedString($input['dni'] ?? $lookupData['dni'] ?? ($identifierField === 'dni' ? $identifierValue : '')),
            'numeroCuenta' => $this->nullableString($input['numeroCuenta'] ?? $lookupData['numeroCuenta'] ?? ($identifierField === 'numeroCuenta' ? $identifierValue : '')),
            'numeroEmpleado' => $this->nullableString($input['numeroEmpleado'] ?? $lookupData['numeroEmpleado'] ?? ($identifierField === 'numeroEmpleado' ? $identifierValue : '')),
            'correoInstitucional' => $this->nullableString($input['correoInstitucional'] ?? $lookupData['correoInstitucional'] ?? ''),
            'fechaNacimiento' => $this->normalizedString($input['fechaNacimiento'] ?? $lookupData['fechaNacimiento'] ?? ''),
            'sexo' => $this->normalizedString($input['sexo'] ?? $lookupData['sexo'] ?? ''),
            'direccion' => $this->normalizedString($input['direccion'] ?? $lookupData['direccion'] ?? ''),
            'telefono' => $this->normalizedString($input['telefono'] ?? $lookupData['telefono'] ?? ''),
            'requires_numero_cuenta' => $identifierField === 'numeroCuenta',
            'requires_numero_empleado' => $identifierField === 'numeroEmpleado',
        ];
    }

    private function normalizedString(mixed $value): string
    {
        return trim((string) $value);
    }

    private function nullableString(mixed $value): ?string
    {
        $normalized = trim((string) $value);

        return $normalized !== '' ? $normalized : null;
    }

    private function formatName(string $value): string
    {
        return Str::title(Str::lower(trim($value)));
    }

    private function resolveSplitPair(array $input, array $lookupData, string $firstKey, string $secondKey, string $legacyKey): array
    {
        $first = $this->normalizedString($input[$firstKey] ?? $lookupData[$firstKey] ?? '');
        $second = $this->normalizedString($input[$secondKey] ?? $lookupData[$secondKey] ?? '');

        if ($first === '' && $second === '') {
            [$first, $second] = $this->splitNameParts($input[$legacyKey] ?? $lookupData[$legacyKey] ?? '');
        }

        return [$first, $second];
    }

    private function splitNameParts(mixed $value): array
    {
        $parts = preg_split('/\s+/', trim((string) $value), 2, PREG_SPLIT_NO_EMPTY) ?: [];

        return [$parts[0] ?? '', $parts[1] ?? ''];
    }
}
