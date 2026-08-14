<?php

namespace App\Services;

use App\Models\ApiIntegration;
use App\Models\Tipoperfil;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class ProfileDirectoryLookupService
{
    public function lookup(Tipoperfil $tipoperfil, string $identifier): array
    {
        /** @var ApiIntegration|null $integration */
        $integration = $tipoperfil->apiIntegrations()
            ->where('is_active', true)
            ->latest('id')
            ->first();

        if (! $integration) {
            return [
                'success' => false,
                'message' => 'No hay una integración API activa configurada para este tipo de perfil.',
                'data' => [],
            ];
        }

        $url = rtrim($integration->base_url, '/') . '/' . ltrim($integration->lookup_path, '/');
        $url = str_replace('{identifier}', urlencode($identifier), $url);

        $headers = collect($integration->headers_json ?? [])
            ->filter(fn ($value, $key) => filled($key) && filled($value))
            ->all();

        $timeoutSeconds = max(5, (int) ($integration->timeout_seconds ?: 10));

        $request = Http::timeout($timeoutSeconds)
            ->acceptJson()
            ->withHeaders($headers);

        if ($integration->auth_type === 'bearer' && filled($integration->auth_token)) {
            $request = $request->withToken($integration->auth_token);
        }

        $method = Str::upper($integration->http_method ?: 'GET');

        try {
            if ($method === 'GET' && filled($integration->identifier_query_key) && ! str_contains($integration->lookup_path, '{identifier}')) {
                $response = $request->get($url, [
                    $integration->identifier_query_key => $identifier,
                ]);
            } else {
                $response = match ($method) {
                    'POST' => $request->post($url, filled($integration->identifier_query_key) ? [$integration->identifier_query_key => $identifier] : []),
                    'PUT' => $request->put($url, filled($integration->identifier_query_key) ? [$integration->identifier_query_key => $identifier] : []),
                    default => $request->get($url),
                };
            }
        } catch (ConnectionException $exception) {
            report($exception);

            return [
                'success' => false,
                'message' => 'No se pudo conectar con el servicio externo. Verifica tu conexión a internet o intenta nuevamente en unos minutos.',
                'data' => [],
            ];
        } catch (RequestException $exception) {
            report($exception);

            return [
                'success' => false,
                'message' => 'La consulta al servicio externo no pudo completarse. Intenta nuevamente.',
                'data' => [],
            ];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'message' => 'Ocurrió un problema al consultar el servicio externo. Intenta nuevamente más tarde.',
                'data' => [],
            ];
        }

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'La API respondió con estado ' . $response->status() . '.',
                'data' => [],
            ];
        }

        $payload = $response->json();
        $record = filled($integration->response_path) ? data_get($payload, $integration->response_path) : $payload;

        if (! is_array($record)) {
            return [
                'success' => false,
                'message' => 'La respuesta de la API no tiene un formato válido para el mapeo configurado.',
                'data' => [],
            ];
        }

        $fieldMap = $integration->field_map ?? [];
        $data = [];

        foreach ($fieldMap as $localField => $remotePath) {
            if (! filled($remotePath)) {
                continue;
            }

            $value = data_get($record, $remotePath);

            if ($value !== null) {
                $data[$localField] = is_scalar($value) ? trim((string) $value) : $value;
            }
        }

        $data = $this->normalizePersonNameFields($record, $data);

        $fallbacks = [
            'fechaNacimiento' => ['fechaNacimiento', 'fecha_nacimiento', 'fecha_nac', 'fechaNacimientoPersona', 'birthdate', 'birthDate', 'DOB', 'dob'],
            'sexo' => ['sexo', 'genero', 'género', 'gender', 'sex'],
            'nacionalidad' => ['nacionalidad', 'nombreNacionalidad', 'pais_nacionalidad', 'nationality'],
            'IdNacionalidad' => ['IdNacionalidad', 'idNacionalidad', 'nacionalidad_id'],
        ];

        foreach ($fallbacks as $localField => $candidates) {
            if (filled($data[$localField] ?? null)) {
                continue;
            }

            foreach ($candidates as $candidate) {
                $value = data_get($record, $candidate);

                if ($value !== null && trim((string) $value) !== '') {
                    $data[$localField] = trim((string) $value);
                    break;
                }
            }
        }

        return [
            'success' => true,
            'message' => 'Datos encontrados correctamente en la API.',
            'data' => $data,
        ];
    }

    private function normalizePersonNameFields(array $record, array $data): array
    {
        $primerNombre = $data['primer_nombre'] ?? $this->firstFilled($record, [
            'primer_nombre',
            'primerNombre',
            'first_name',
            'firstName',
        ]);
        $segundoNombre = $data['segundo_nombre'] ?? $this->firstFilled($record, [
            'segundo_nombre',
            'segundoNombre',
            'middle_name',
            'middleName',
        ]);
        $primerApellido = $data['primer_apellido'] ?? $this->firstFilled($record, [
            'primer_apellido',
            'primerApellido',
            'apellido_paterno',
            'apellidoPaterno',
            'last_name',
            'lastName',
        ]);
        $segundoApellido = $data['segundo_apellido'] ?? $this->firstFilled($record, [
            'segundo_apellido',
            'segundoApellido',
            'apellido_materno',
            'apellidoMaterno',
            'second_last_name',
            'secondLastName',
        ]);

        if (! filled($primerNombre) && ! filled($segundoNombre)) {
            [$primerNombre, $segundoNombre] = $this->splitTwoPartName(
                $data['nombre'] ?? $this->firstFilled($record, ['nombre', 'nombres'])
            );
        }

        if (! filled($primerApellido) && ! filled($segundoApellido)) {
            [$primerApellido, $segundoApellido] = $this->splitTwoPartName(
                $data['apellido'] ?? $this->firstFilled($record, ['apellido', 'apellidos'])
            );
        }

        if ((! filled($primerNombre) || ! filled($primerApellido)) && filled($fullName = $this->firstFilled($record, [
            'nombre_completo',
            'nombreCompleto',
            'full_name',
            'fullName',
        ]))) {
            [$fallbackPrimerNombre, $fallbackSegundoNombre, $fallbackPrimerApellido, $fallbackSegundoApellido] = $this->splitFullName($fullName);

            $primerNombre = filled($primerNombre) ? $primerNombre : $fallbackPrimerNombre;
            $segundoNombre = filled($segundoNombre) ? $segundoNombre : $fallbackSegundoNombre;
            $primerApellido = filled($primerApellido) ? $primerApellido : $fallbackPrimerApellido;
            $segundoApellido = filled($segundoApellido) ? $segundoApellido : $fallbackSegundoApellido;
        }

        $data['primer_nombre'] = trim((string) $primerNombre);
        $data['segundo_nombre'] = trim((string) $segundoNombre);
        $data['primer_apellido'] = trim((string) $primerApellido);
        $data['segundo_apellido'] = trim((string) $segundoApellido);

        if (! filled($data['nombre'] ?? null)) {
            $data['nombre'] = trim(collect([$data['primer_nombre'], $data['segundo_nombre']])->filter()->implode(' '));
        }

        if (! filled($data['apellido'] ?? null)) {
            $data['apellido'] = trim(collect([$data['primer_apellido'], $data['segundo_apellido']])->filter()->implode(' '));
        }

        return $data;
    }

    private function firstFilled(array $record, array $paths): ?string
    {
        foreach ($paths as $path) {
            $value = data_get($record, $path);

            if ($value !== null && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }

    private function splitTwoPartName(mixed $value): array
    {
        $parts = $this->cleanNameParts($value);

        if ($parts === []) {
            return [null, null];
        }

        return [
            $parts[0] ?? null,
            trim(implode(' ', array_slice($parts, 1))) ?: null,
        ];
    }

    private function splitFullName(mixed $value): array
    {
        $parts = $this->cleanNameParts($value);

        if (count($parts) <= 1) {
            return [$parts[0] ?? null, null, null, null];
        }

        if (count($parts) === 2) {
            return [$parts[0], null, $parts[1], null];
        }

        if (count($parts) === 3) {
            return [$parts[0], null, $parts[1], $parts[2]];
        }

        return [
            $parts[0],
            $parts[1],
            $parts[count($parts) - 2],
            $parts[count($parts) - 1],
        ];
    }

    private function cleanNameParts(mixed $value): array
    {
        return collect(explode(' ', trim((string) $value)))
            ->filter(fn (string $part) => $part !== '')
            ->values()
            ->all();
    }
}
