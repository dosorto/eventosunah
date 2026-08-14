<?php

namespace App\Livewire\MetodoPago;

use App\Models\MetodoPago;
use Livewire\Component;

class MetodosPago extends Component
{
    public bool $isOpen = false;
    public ?int $metodo_pago_id = null;
    public string $nombre = '';
    public string $codigo = '';
    public string $descripcion = '';
    public string $tipo = MetodoPago::TYPE_CASH;
    public bool $is_active = true;
    public string $documento_cobro_tipo = 'recibo';
    public string $banco_nombre = '';
    public string $numero_cuenta = '';
    public string $titular_cuenta = '';
    public string $detalle_transferencia_internacional = '';
    public string $instrucciones = '';

    public function mount(): void
    {
        $this->ensureSystemMethods();
    }

    public function render()
    {
        return view('livewire.MetodoPago.metodos-pago', [
            'metodosPago' => MetodoPago::query()
                ->whereIn('codigo', array_keys($this->systemMethodDefinitions()))
                ->orderBy('orden')
                ->orderBy('id')
                ->get(),
        ])->layout('components.layouts.app');
    }

    public function openConfig(int $id): void
    {
        $metodo = MetodoPago::query()->findOrFail($id);

        $this->metodo_pago_id = $metodo->id;
        $this->nombre = (string) $metodo->nombre;
        $this->codigo = (string) $metodo->codigo;
        $this->descripcion = (string) $metodo->descripcion;
        $this->tipo = (string) $metodo->tipo;
        $this->is_active = (bool) $metodo->is_active;
        $this->documento_cobro_tipo = (string) ($metodo->documento_cobro_tipo ?: 'recibo');
        $this->banco_nombre = (string) $metodo->banco_nombre;
        $this->numero_cuenta = (string) $metodo->numero_cuenta;
        $this->titular_cuenta = (string) $metodo->titular_cuenta;
        $this->detalle_transferencia_internacional = (string) $metodo->detalle_transferencia_internacional;
        $this->instrucciones = (string) $metodo->instrucciones;
        $this->resetValidation();
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $metodo = MetodoPago::query()->findOrFail($this->metodo_pago_id);

        $rules = [
            'descripcion' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'instrucciones' => ['nullable', 'string'],
        ];

        if ($metodo->isCash()) {
            $rules['documento_cobro_tipo'] = ['required', 'in:recibo,factura'];
        }

        if ($metodo->isTransfer()) {
            $rules['banco_nombre'] = ['required', 'string', 'max:150'];
            $rules['numero_cuenta'] = ['required', 'string', 'max:150'];
            $rules['titular_cuenta'] = ['required', 'string', 'max:150'];
            $rules['detalle_transferencia_internacional'] = ['nullable', 'string'];
        }

        $validated = $this->validate($rules);

        $metodo->update([
            'descripcion' => filled($validated['descripcion'] ?? null) ? trim($validated['descripcion']) : null,
            'is_active' => (bool) $this->is_active,
            'documento_cobro_tipo' => $metodo->isCash() ? $this->documento_cobro_tipo : null,
            'banco_nombre' => $metodo->isTransfer() ? trim($this->banco_nombre) : null,
            'numero_cuenta' => $metodo->isTransfer() ? trim($this->numero_cuenta) : null,
            'titular_cuenta' => $metodo->isTransfer() ? trim($this->titular_cuenta) : null,
            'detalle_transferencia_internacional' => $metodo->isTransfer() && filled($this->detalle_transferencia_internacional)
                ? trim($this->detalle_transferencia_internacional)
                : null,
            'instrucciones' => filled($validated['instrucciones'] ?? null) ? trim($validated['instrucciones']) : null,
            'requiere_comprobante' => $metodo->isTransfer(),
            'requiere_api' => $metodo->isCard(),
        ]);

        session()->flash('message', 'Método de pago actualizado correctamente.');
        $this->closeModal();
    }

    private function ensureSystemMethods(): void
    {
        foreach ($this->systemMethodDefinitions() as $codigo => $definition) {
            $method = MetodoPago::query()->firstOrNew(['codigo' => $codigo]);

            $method->nombre = $definition['nombre'];
            $method->tipo = $definition['tipo'];
            $method->orden = $definition['orden'];
            $method->requiere_api = $definition['requiere_api'];
            $method->requiere_comprobante = $definition['requiere_comprobante'];
            $method->documento_cobro_tipo = $method->isCash()
                ? ($method->documento_cobro_tipo ?: $definition['documento_cobro_tipo'])
                : null;
            $method->is_active = $method->exists ? (bool) $method->is_active : (bool) $definition['is_active'];

            if (! filled($method->descripcion)) {
                $method->descripcion = $definition['descripcion'];
            }

            $method->save();
        }
    }

    private function systemMethodDefinitions(): array
    {
        return [
            'efectivo' => [
                'nombre' => 'Pago en efectivo',
                'tipo' => MetodoPago::TYPE_CASH,
                'descripcion' => 'El participante reserva su lugar y paga presencialmente durante el evento.',
                'requiere_api' => false,
                'requiere_comprobante' => false,
                'documento_cobro_tipo' => 'recibo',
                'orden' => 1,
                'is_active' => true,
            ],
            'transferencia' => [
                'nombre' => 'Transferencia bancaria',
                'tipo' => MetodoPago::TYPE_TRANSFER,
                'descripcion' => 'El participante sube el comprobante y el pago se valida después desde el módulo de pagos.',
                'requiere_api' => false,
                'requiere_comprobante' => true,
                'orden' => 2,
                'is_active' => true,
            ],
            'tarjeta_online' => [
                'nombre' => 'Tarjeta de crédito / débito',
                'tipo' => MetodoPago::TYPE_CARD,
                'descripcion' => 'Pago online. Se deja preparado para una integración futura.',
                'requiere_api' => true,
                'requiere_comprobante' => false,
                'orden' => 3,
                'is_active' => false,
            ],
        ];
    }
}
