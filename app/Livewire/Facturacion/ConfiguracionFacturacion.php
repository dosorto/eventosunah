<?php

namespace App\Livewire\Facturacion;

use App\Models\FacturacionConfig;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ConfiguracionFacturacion extends Component
{
    public ?int $configId = null;
    public string $razon_social = '';
    public string $rtn_emisor = '';
    public string $cai = '';
    public string $establecimiento_codigo = '';
    public string $punto_emision_codigo = '';
    public string $tipo_documento_codigo = '01';
    public string $rango_inicio = '';
    public string $rango_fin = '';
    public string $siguiente_numero = '';
    public string $fecha_limite_emision = '';
    public string $direccion_fiscal = '';
    public string $telefono_fiscal = '';
    public string $correo_fiscal = '';
    public string $leyenda = '';
    public bool $is_active = true;

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('events.manage'), 403);

        $config = FacturacionConfig::query()->latest('id')->first();

        if (! $config) {
            return;
        }

        $this->configId = $config->id;
        $this->razon_social = (string) $config->razon_social;
        $this->rtn_emisor = (string) $config->rtn_emisor;
        $this->cai = (string) $config->cai;
        $this->establecimiento_codigo = (string) $config->establecimiento_codigo;
        $this->punto_emision_codigo = (string) $config->punto_emision_codigo;
        $this->tipo_documento_codigo = (string) $config->tipo_documento_codigo;
        $this->rango_inicio = (string) $config->rango_inicio;
        $this->rango_fin = (string) $config->rango_fin;
        $this->siguiente_numero = (string) $config->siguiente_numero;
        $this->fecha_limite_emision = $config->fecha_limite_emision?->format('Y-m-d') ?? '';
        $this->direccion_fiscal = (string) $config->direccion_fiscal;
        $this->telefono_fiscal = (string) $config->telefono_fiscal;
        $this->correo_fiscal = (string) $config->correo_fiscal;
        $this->leyenda = (string) $config->leyenda;
        $this->is_active = (bool) $config->is_active;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'razon_social' => ['required', 'string', 'max:180'],
            'rtn_emisor' => ['required', 'string', 'max:32'],
            'cai' => ['required', 'string', 'max:64'],
            'establecimiento_codigo' => ['required', 'string', 'max:8'],
            'punto_emision_codigo' => ['required', 'string', 'max:8'],
            'tipo_documento_codigo' => ['required', 'string', 'max:8'],
            'rango_inicio' => ['required', 'integer', 'min:1'],
            'rango_fin' => ['required', 'integer', 'gte:rango_inicio'],
            'siguiente_numero' => ['required', 'integer', 'gte:rango_inicio', 'lte:rango_fin'],
            'fecha_limite_emision' => ['required', 'date'],
            'direccion_fiscal' => ['nullable', 'string', 'max:255'],
            'telefono_fiscal' => ['nullable', 'string', 'max:50'],
            'correo_fiscal' => ['nullable', 'email', 'max:120'],
            'leyenda' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $config = $this->configId
            ? FacturacionConfig::query()->findOrNew($this->configId)
            : new FacturacionConfig();
        $config->fill([
            'razon_social' => trim($validated['razon_social']),
            'rtn_emisor' => trim($validated['rtn_emisor']),
            'cai' => trim($validated['cai']),
            'establecimiento_codigo' => trim($validated['establecimiento_codigo']),
            'punto_emision_codigo' => trim($validated['punto_emision_codigo']),
            'tipo_documento_codigo' => trim($validated['tipo_documento_codigo']),
            'rango_inicio' => (int) $validated['rango_inicio'],
            'rango_fin' => (int) $validated['rango_fin'],
            'siguiente_numero' => (int) $validated['siguiente_numero'],
            'fecha_limite_emision' => $validated['fecha_limite_emision'],
            'direccion_fiscal' => filled($validated['direccion_fiscal'] ?? null) ? trim($validated['direccion_fiscal']) : null,
            'telefono_fiscal' => filled($validated['telefono_fiscal'] ?? null) ? trim($validated['telefono_fiscal']) : null,
            'correo_fiscal' => filled($validated['correo_fiscal'] ?? null) ? trim($validated['correo_fiscal']) : null,
            'leyenda' => filled($validated['leyenda'] ?? null) ? trim($validated['leyenda']) : null,
            'is_active' => (bool) $validated['is_active'],
        ]);
        $config->save();

        $this->configId = $config->id;

        session()->flash('message', 'Configuración de facturación actualizada correctamente.');
    }

    public function render()
    {
        $config = FacturacionConfig::query()->latest('id')->first();

        return view('livewire.facturacion.configuracion-facturacion', [
            'config' => $config,
        ])->layout('components.layouts.app');
    }
}
