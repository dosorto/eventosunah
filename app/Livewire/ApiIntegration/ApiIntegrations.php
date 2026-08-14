<?php

namespace App\Livewire\ApiIntegration;

use App\Models\ApiIntegration;
use App\Models\Tipoperfil;
use Livewire\Component;
use Livewire\WithPagination;

class ApiIntegrations extends Component
{
    use WithPagination;

    public ?int $integration_id = null;
    public string $nombre = '';
    public string $tipoperfil_id = '';
    public string $base_url = '';
    public string $lookup_path = '';
    public string $http_method = 'GET';
    public string $auth_type = 'none';
    public string $auth_token = '';
    public string $identifier_query_key = '';
    public string $timeout_seconds = '10';
    public string $response_path = '';
    public string $headers_json = '';
    public string $field_map_json = '';
    public bool $is_active = true;
    public string $search = '';
    public bool $showModal = false;
    public bool $confirmingDelete = false;
    public ?int $deleteId = null;
    public string $deleteName = '';

    public function render()
    {
        $integrations = ApiIntegration::query()
            ->with('tipoPerfil')
            ->where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tipoPerfil', fn ($profileQuery) => $profileQuery->where('tipoperfil', 'like', '%' . $this->search . '%'));
            })
            ->latest('id')
            ->paginate(10);

        return view('livewire.ApiIntegration.api-integrations', [
            'integrations' => $integrations,
            'tiposPerfil' => Tipoperfil::query()->orderBy('tipoperfil')->get(),
        ])->layout('components.layouts.app');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $integration = ApiIntegration::findOrFail($id);

        $this->integration_id = $integration->id;
        $this->nombre = $integration->nombre;
        $this->tipoperfil_id = (string) $integration->tipoperfil_id;
        $this->base_url = $integration->base_url;
        $this->lookup_path = $integration->lookup_path;
        $this->http_method = $integration->http_method;
        $this->auth_type = $integration->auth_type;
        $this->auth_token = $integration->auth_token ?? '';
        $this->identifier_query_key = $integration->identifier_query_key ?? '';
        $this->timeout_seconds = (string) $integration->timeout_seconds;
        $this->response_path = $integration->response_path ?? '';
        $this->headers_json = $integration->headers_json ? json_encode($integration->headers_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';
        $this->field_map_json = $integration->field_map ? json_encode($integration->field_map, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';
        $this->is_active = (bool) $integration->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'nombre' => 'required|string|max:255',
            'tipoperfil_id' => 'required|exists:tipoperfils,id',
            'base_url' => 'required|url|max:255',
            'lookup_path' => 'required|string|max:255',
            'http_method' => 'required|in:GET,POST,PUT',
            'auth_type' => 'required|in:none,bearer',
            'auth_token' => 'nullable|string',
            'identifier_query_key' => 'nullable|string|max:100',
            'timeout_seconds' => 'required|integer|min:1|max:60',
            'response_path' => 'nullable|string|max:255',
            'headers_json' => 'nullable|string',
            'field_map_json' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $headers = $this->decodeJsonField($validated['headers_json'], 'headers_json');
        $fieldMap = $this->decodeJsonField($validated['field_map_json'], 'field_map_json');

        ApiIntegration::updateOrCreate(
            ['id' => $this->integration_id],
            [
                'nombre' => trim($validated['nombre']),
                'tipoperfil_id' => $validated['tipoperfil_id'],
                'base_url' => trim($validated['base_url']),
                'lookup_path' => trim($validated['lookup_path']),
                'http_method' => $validated['http_method'],
                'auth_type' => $validated['auth_type'],
                'auth_token' => filled($validated['auth_token']) ? trim($validated['auth_token']) : null,
                'identifier_query_key' => filled($validated['identifier_query_key']) ? trim($validated['identifier_query_key']) : null,
                'timeout_seconds' => (int) $validated['timeout_seconds'],
                'response_path' => filled($validated['response_path']) ? trim($validated['response_path']) : null,
                'headers_json' => $headers,
                'field_map' => $fieldMap,
                'is_active' => (bool) $validated['is_active'],
            ]
        );

        session()->flash('message', $this->integration_id ? 'Integración API actualizada correctamente.' : 'Integración API creada correctamente.');

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $integration = ApiIntegration::findOrFail($id);
        $this->deleteId = $integration->id;
        $this->deleteName = $integration->nombre;
        $this->confirmingDelete = true;
    }

    public function delete(): void
    {
        if (! $this->deleteId) {
            return;
        }

        ApiIntegration::findOrFail($this->deleteId)->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->deleteName = '';

        session()->flash('message', 'Integración API eliminada correctamente.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm(): void
    {
        $this->integration_id = null;
        $this->nombre = '';
        $this->tipoperfil_id = '';
        $this->base_url = '';
        $this->lookup_path = '';
        $this->http_method = 'GET';
        $this->auth_type = 'none';
        $this->auth_token = '';
        $this->identifier_query_key = '';
        $this->timeout_seconds = '10';
        $this->response_path = '';
        $this->headers_json = '';
        $this->field_map_json = json_encode([
            'dni' => 'dni',
            'numeroCuenta' => 'numeroCuenta',
            'numeroEmpleado' => 'numeroEmpleado',
            'primer_nombre' => 'primer_nombre',
            'segundo_nombre' => 'segundo_nombre',
            'primer_apellido' => 'primer_apellido',
            'segundo_apellido' => 'segundo_apellido',
            'correo' => 'correo',
            'correoInstitucional' => 'correoInstitucional',
            'fechaNacimiento' => 'fechaNacimiento',
            'sexo' => 'sexo',
            'direccion' => 'direccion',
            'telefono' => 'telefono',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $this->is_active = true;
        $this->deleteId = null;
        $this->deleteName = '';
    }

    private function decodeJsonField(?string $value, string $field): ?array
    {
        if (! filled($value)) {
            return null;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            $this->addError($field, 'Debe ser un JSON válido.');
            throw \Illuminate\Validation\ValidationException::withMessages([
                $field => 'Debe ser un JSON válido.',
            ]);
        }

        return $decoded;
    }
}
