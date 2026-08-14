<div class="space-y-6">
    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Pagos del evento</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">{{ $evento->nombreevento }}</h1>
                <p class="mt-3 text-sm text-slate-500">{{ $evento->organizador }} · {{ $evento->localidad_display }} · {{ $evento->fechainicio?->format('d/m/Y') }}@if($evento->fechafinal) - {{ $evento->fechafinal->format('d/m/Y') }}@endif</p>
            </div>

            <div class="flex flex-wrap justify-end gap-3">
                <button type="button" wire:click="openRegistrationWizardModal" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(109,67,255,0.22)] transition hover:scale-[1.01]">
                    Inscribir participante
                </button>
                <a href="{{ route('pagos.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-slate-200 dark:hover:bg-white/5">
                    Volver a pagos
                </a>
            </div>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-4">
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-white/8 dark:bg-[#10131c]/92">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Participantes</p>
            <p class="mt-3 text-3xl font-semibold text-slate-950 dark:text-white">{{ $summary['total'] }}</p>
            <p class="mt-2 text-sm text-slate-500">{{ $summary['free_count'] }} sin cobro configurado</p>
        </div>
        <div class="rounded-[28px] border border-amber-200/70 bg-amber-50/90 p-5 shadow-[0_18px_50px_rgba(245,158,11,0.08)] dark:border-amber-900/40 dark:bg-amber-950/20">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-amber-700 dark:text-amber-300">Pendientes de revisión</p>
            <p class="mt-3 text-3xl font-semibold text-amber-700 dark:text-amber-200">{{ $evento->formatMoney($summary['pending_amount'], true) }}</p>
            <p class="mt-2 text-sm text-amber-700/80 dark:text-amber-200/80">{{ $summary['pending_count'] }} participantes</p>
        </div>
        <div class="rounded-[28px] border border-emerald-200/70 bg-emerald-50/90 p-5 shadow-[0_18px_50px_rgba(16,185,129,0.08)] dark:border-emerald-900/40 dark:bg-emerald-950/20">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">Ingresado validado</p>
            <p class="mt-3 text-3xl font-semibold text-emerald-700 dark:text-emerald-200">{{ $evento->formatMoney($summary['approved_amount'], true) }}</p>
            <p class="mt-2 text-sm text-emerald-700/80 dark:text-emerald-200/80">{{ $summary['approved_count'] }} participantes</p>
        </div>
        <div class="rounded-[28px] border border-rose-200/70 bg-rose-50/90 p-5 shadow-[0_18px_50px_rgba(244,63,94,0.08)] dark:border-rose-900/40 dark:bg-rose-950/20">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-700 dark:text-rose-300">Devoluciones</p>
            <p class="mt-3 text-3xl font-semibold text-rose-700 dark:text-rose-200">{{ $evento->formatMoney($summary['refunded_amount'], true) }}</p>
            <p class="mt-2 text-sm text-rose-700/80 dark:text-rose-200/80">{{ $summary['refunded_count'] }} participantes</p>
        </div>
    </section>

    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <div class="flex flex-col gap-4">
            <div class="grid gap-3 xl:grid-cols-[minmax(0,1fr)_auto] xl:items-center">
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por participante, correo, DNI, método o referencia..."
                    name="payments_table_search"
                    autocomplete="off"
                    autocorrect="off"
                    autocapitalize="off"
                    spellcheck="false"
                    data-form-type="other"
                    class="h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100"
                >

                <div class="flex flex-wrap gap-2">
                    @php
                        $paymentTabs = [
                            'todos' => 'Todos',
                            'pendientes' => 'Pendientes de revisión',
                            'pagados' => 'Pagados',
                            'reembolsados' => 'Devoluciones',
                        ];
                    @endphp

                    @foreach($paymentTabs as $tabValue => $tabLabel)
                        <button
                            type="button"
                            wire:click="$set('statusFilter', '{{ $tabValue }}')"
                            class="{{ $statusFilter === $tabValue
                                ? 'border-[#7b5cff] bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] text-white shadow-[0_18px_40px_rgba(109,67,255,0.22)]'
                                : 'border-slate-200 bg-white text-slate-600 hover:border-[#cfc5ff] hover:text-[#6d43ff] dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-300' }} inline-flex min-h-12 items-center justify-center rounded-2xl border px-4 py-3 text-sm font-semibold transition"
                        >
                            {{ $tabLabel }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-600 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-300">
                @if($statusFilter === 'pendientes')
                    Se muestran pagos que todavía requieren revisión o validación manual.
                @elseif($statusFilter === 'pagados')
                    Se muestran únicamente cobros ya validados dentro del evento.
                @elseif($statusFilter === 'reembolsados')
                    Se muestran pagos devueltos con su trazabilidad de autorización.
                @else
                    Vista general de todos los registros de cobro del evento.
                @endif
            </div>
        </div>

        <div class="mt-6 rounded-3xl border border-slate-200/80 dark:border-slate-800">
            <div class="overflow-x-auto overflow-y-visible">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50/90 text-xs uppercase tracking-[0.2em] text-slate-500 dark:bg-white/[0.03]">
                        <tr>
                            <th class="px-5 py-4">Participante</th>
                            <th class="px-5 py-4">Perfil</th>
                            <th class="px-5 py-4">Cobro</th>
                            <th class="px-5 py-4">Comprobante</th>
                            <th class="px-5 py-4">Estado</th>
                            <th class="px-5 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 text-slate-700 dark:divide-white/6 dark:text-slate-200">
                        @forelse($registrations as $registration)
                            @php($isPaid = $registration->estado_pago === 'pagado')
                            @php($isFree = $registration->estado_pago === 'no_aplica')
                            @php($isRefunded = $registration->estado_pago === 'reembolsado')
                            <tr class="{{ $isPaid || $isFree ? 'bg-emerald-50/60 dark:bg-emerald-950/10' : ($isRefunded ? 'bg-red-50/60 dark:bg-red-950/10' : 'bg-white dark:bg-transparent') }}">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-950 dark:text-white">{{ trim(($registration->persona?->nombre ?? '') . ' ' . ($registration->persona?->apellido ?? '')) ?: 'Sin participante' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $registration->persona?->dni ?: 'Sin identidad' }} · {{ $registration->persona?->correo ?: 'Sin correo' }}</p>
                                </td>
                                <td class="px-5 py-4">{{ $registration->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-950 dark:text-white">
                                        @if((float) $registration->precio_aplicado > 0)
                                            {{ $registration->formattedPrecioAplicado(true) }}
                                        @else
                                            Gratuita
                                        @endif
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $registration->metodoPago?->nombre ?? ($registration->estado_pago === 'no_aplica' ? 'No aplica' : 'Sin método') }}</p>
                                    @if($registration->referencia_pago)
                                        <p class="mt-1 text-xs text-slate-400">Ref. {{ $registration->referencia_pago }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($registration->comprobante_pago_path)
                                        <div class="space-y-2">
                                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">{{ $this->proofMimeCategory($registration) === 'pdf' ? 'PDF' : 'Imagen' }}</p>
                                            <a href="{{ $this->proofUrl($registration) }}" target="_blank" class="inline-flex items-center justify-center rounded-full border border-violet-300 px-3 py-1.5 text-xs font-semibold text-violet-700 transition hover:bg-violet-50 dark:border-violet-700 dark:text-violet-300 dark:hover:bg-violet-950/30">
                                                Ver comprobante
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">Sin archivo</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="space-y-2">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $isPaid || $isFree ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : ($isRefunded ? 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-300' : ($registration->estado_pago === 'rechazado' ? 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $registration->estado_pago ?: 'pendiente')) }}
                                        </span>
                                        @if($registration->pagado_en)
                                            <p class="text-xs text-slate-500">Validado {{ $registration->pagado_en->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        @if($isFree)
                                            <span class="text-xs font-medium text-slate-400">Sin acciones</span>
                                        @elseif(! $isPaid && ! $isRefunded)
                                            <button type="button" wire:click="openManualPaymentModal({{ $registration->id }})" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] px-4 py-2 text-xs font-semibold text-white shadow-[0_18px_40px_rgba(109,67,255,0.22)] transition hover:scale-[1.01]">
                                                {{ $registration->comprobante_pago_path ? 'Registrar / validar pago' : 'Registrar pago' }}
                                            </button>
                                        @else
                                            <div
                                                class="relative"
                                                x-data="{
                                                    open: false,
                                                    menuStyle: '',
                                                    toggleMenu($event) {
                                                        const rect = $event.currentTarget.getBoundingClientRect();
                                                        this.menuStyle = `top:${rect.bottom + 8}px;left:${Math.max(16, rect.right - 256)}px;`;
                                                        this.open = !this.open;
                                                    }
                                                }"
                                                @click.outside="open = false"
                                            >
                                                <button type="button" @click="toggleMenu($event)" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-600 transition hover:bg-slate-50">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
                                                    </svg>
                                                </button>
                                                <div
                                                    x-cloak
                                                    x-show="open"
                                                    x-transition.origin.top.right
                                                    x-bind:style="menuStyle"
                                                    class="fixed z-[120] w-64 rounded-3xl border border-slate-200 bg-white p-2 shadow-[0_30px_80px_rgba(15,23,42,0.18)]"
                                                >
                                                    @if($isPaid)
                                                        <button type="button" wire:click="openReceiptModal({{ $registration->id }})" @click="open = false" class="flex w-full items-center rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                                            Generar recibo
                                                        </button>
                                                        <button type="button" wire:click="openInvoiceModal({{ $registration->id }})" @click="open = false" class="mt-1 flex w-full items-center rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                                            Generar factura
                                                        </button>
                                                        <button type="button" wire:click="openRefundModal({{ $registration->id }})" @click="open = false" class="mt-1 flex w-full items-center rounded-2xl px-4 py-3 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                                            Hacer devolución
                                                        </button>
                                                    @elseif($isRefunded)
                                                        <button type="button" wire:click="openRefundReceiptModal({{ $registration->id }})" @click="open = false" class="flex w-full items-center rounded-2xl px-4 py-3 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                                            Generar recibo de devolución
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <p class="text-lg font-semibold text-slate-950 dark:text-white">No hay registros para estos filtros.</p>
                                    <p class="mt-2 text-sm text-slate-500">Cuando existan inscripciones del evento, aparecerán aquí.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($registrations->hasPages())
            <div class="mt-5">
                {{ $registrations->links() }}
            </div>
        @endif
    </section>

    @if($showRegistrationWizardModal)
        @include('livewire.pago.partials.registration-wizard-modal')
    @endif

    @if($showManualPaymentModal && $selectedRegistration)
        <div class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
            <div class="flex max-h-[92vh] w-full max-w-5xl flex-col overflow-hidden rounded-[32px] border border-white/60 bg-white shadow-[0_40px_120px_rgba(15,23,42,0.35)]">
                <div class="flex shrink-0 items-start justify-between gap-4 border-b border-slate-200 px-6 py-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Cobro manual</p>
                        <h3 class="mt-1 text-2xl font-semibold text-slate-950">Registrar y validar pago</h3>
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                            <span>{{ trim(($selectedRegistration->persona?->nombre ?? '') . ' ' . ($selectedRegistration->persona?->apellido ?? '')) }}</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $selectedRegistration->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Monto actual: {{ $selectedRegistration->formattedPrecioAplicado(true) }}</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ ucfirst(str_replace('_', ' ', $selectedRegistration->estado_pago ?: 'pendiente')) }}</span>
                        </div>
                    </div>
                    <button type="button" wire:click="closeManualPaymentModal" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-50">
                        ✕
                    </button>
                </div>

                <div class="min-h-0 flex-1 overflow-y-auto px-6 py-5">
                    <div class="space-y-4">
                        @if($selectedRegistration->comprobante_pago_path && ($selectedRegistration->metodoPago?->codigo !== 'efectivo'))
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <p class="text-sm font-semibold text-slate-700">Comprobante cargado</p>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $selectedRegistration->metodoPago?->nombre ?? 'Transferencia' }}</p>
                                </div>
                                @if($this->proofUrl($selectedRegistration))
                                    @if($this->proofMimeCategory($selectedRegistration) === 'pdf')
                                        <iframe src="{{ $this->proofUrl($selectedRegistration) }}" class="mt-3 h-[320px] w-full rounded-2xl border border-slate-200 bg-white"></iframe>
                                    @else
                                        <img src="{{ $this->proofUrl($selectedRegistration) }}" alt="Comprobante" class="mt-3 h-[320px] w-full rounded-2xl border border-slate-200 bg-white object-contain">
                                    @endif
                                @endif
                            </div>
                        @else
                            <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
                                Este pago no tiene comprobante adjunto para revisión. Si fue un cobro en efectivo, puedes validarlo directamente desde esta misma ventana.
                            </div>
                        @endif

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Método de pago</label>
                                <select wire:model="manualMetodoPagoId" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10">
                                    <option value="">Selecciona un método</option>
                                    @foreach($manualPaymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('manualMetodoPagoId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-slate-700">Monto aplicado</label>
                                <input wire:model="manualAmount" type="number" step="0.01" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10">
                                @error('manualAmount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 md:col-span-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Validación automática</p>
                                <p class="mt-2 text-sm text-slate-600">
                                    La referencia del cobro será generada automáticamente si hace falta y la fecha/hora de validación se asignará con la hora actual del sistema al guardar.
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Observaciones</label>
                                <textarea wire:model="manualNotes" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10"></textarea>
                                @error('manualNotes') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 flex-col gap-3 border-t border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-end">
                    @if($selectedRegistration->comprobante_pago_path && ($selectedRegistration->metodoPago?->codigo !== 'efectivo'))
                        @if($this->proofUrl($selectedRegistration))
                            <a href="{{ $this->proofUrl($selectedRegistration) }}" target="_blank" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Abrir archivo original
                            </a>
                        @endif
                        <button type="button" wire:click="rejectPayment" class="inline-flex items-center justify-center rounded-full border border-red-300 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                            Rechazar comprobante
                        </button>
                        <button type="button" wire:click="approvePayment" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">
                            Aprobar pago
                        </button>
                    @endif
                    <button type="button" wire:click="closeManualPaymentModal" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Cancelar
                    </button>
                    <button type="button" wire:click="saveManualPayment" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(109,67,255,0.22)] transition hover:scale-[1.01]">
                        Guardar cobro
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showReceiptModal && $selectedRegistration)
        @php($receiptPreview = $this->receiptPreviewData($selectedRegistration))
        <div class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
            <div class="w-full max-w-6xl overflow-hidden rounded-[32px] border border-white/60 bg-white shadow-[0_40px_120px_rgba(15,23,42,0.35)]">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Documento de cobro</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-950">Vista previa del recibo</h3>
                        <p class="mt-2 text-sm text-slate-500">Revisa el recibo antes de descargarlo en PDF.</p>
                    </div>
                    <button type="button" wire:click="closeReceiptModal" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-50">
                        ✕
                    </button>
                </div>

                <div class="grid gap-6 px-6 py-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <div class="overflow-auto rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="mx-auto max-w-4xl rounded-[28px] bg-white p-6 shadow-[0_18px_50px_rgba(15,23,42,0.08)]">
                            @include('pdf.payments.receipt-content', ['document' => $receiptPreview])
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Resumen</p>
                            <p class="mt-3 text-sm text-slate-600">Documento {{ $receiptPreview['document_number'] }}</p>
                            <p class="mt-2 text-sm text-slate-600">Participante {{ $receiptPreview['participant_name'] }}</p>
                            <p class="mt-2 text-sm text-slate-600">Monto {{ $receiptPreview['payment_amount_formatted'] ?? $evento->formatMoney($receiptPreview['payment_amount'] ?? 0, true) }}</p>
                        </div>

                        <a href="{{ $this->receiptPdfUrl($selectedRegistration) }}" target="_blank" class="inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(109,67,255,0.22)] transition hover:scale-[1.01]">
                            Abrir recibo en PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showInvoiceModal && $selectedRegistration)
        @php($invoicePreview = $this->invoicePreviewData($selectedRegistration))
        <div class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
            <div class="w-full max-w-6xl overflow-hidden rounded-[32px] border border-white/60 bg-white shadow-[0_40px_120px_rgba(15,23,42,0.35)]">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Documento fiscal</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-950">Vista previa de la factura</h3>
                        <p class="mt-2 text-sm text-slate-500">Si descargas este documento, el correlativo se reservará de manera definitiva.</p>
                    </div>
                    <button type="button" wire:click="closeInvoiceModal" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-50">
                        ✕
                    </button>
                </div>

                <div class="grid gap-6 px-6 py-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <div class="overflow-auto rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        @if($invoicePreview['enabled'] ?? false)
                            <div class="mx-auto max-w-4xl rounded-[28px] bg-white p-6 shadow-[0_18px_50px_rgba(15,23,42,0.08)]">
                                @include('pdf.payments.invoice-content', ['document' => $invoicePreview])
                            </div>
                        @else
                            <div class="rounded-3xl border border-red-200 bg-red-50 p-6 text-sm text-red-700">
                                {{ $invoicePreview['error'] ?? 'No fue posible generar la vista previa.' }}
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Validación fiscal</p>
                            <p class="mt-3 text-sm text-slate-600">
                                @if($invoicePreview['enabled'] ?? false)
                                    Al descargar, el sistema reservará el número fiscal definitivo dentro del rango autorizado configurado.
                                @else
                                    Configura el CAI, el rango fiscal y la vigencia antes de emitir facturas.
                                @endif
                            </p>
                        </div>

                        @if($invoicePreview['enabled'] ?? false)
                            <a href="{{ $this->invoicePdfUrl($selectedRegistration) }}" target="_blank" class="inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(109,67,255,0.22)] transition hover:scale-[1.01]">
                                Abrir factura en PDF
                            </a>
                        @else
                            <span class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-full bg-slate-200 px-5 py-3 text-sm font-semibold text-slate-500">
                                Factura no disponible
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showRefundModal && $selectedRegistration)
        <div class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
            <div class="w-full max-w-3xl overflow-hidden rounded-[32px] border border-white/60 bg-white shadow-[0_40px_120px_rgba(15,23,42,0.35)]">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Devolución</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-950">Autorizar devolución del pago</h3>
                        <p class="mt-2 text-sm text-slate-500">Esta acción requiere la contraseña del usuario que creó el evento.</p>
                    </div>
                    <button type="button" wire:click="closeRefundModal" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-50">
                        ✕
                    </button>
                </div>

                <div class="space-y-5 px-6 py-6" autocomplete="off">
                    <input type="text" name="refund_fake_username" autocomplete="username" class="hidden" tabindex="-1" aria-hidden="true">
                    <input type="password" name="refund_fake_password" autocomplete="current-password" class="hidden" tabindex="-1" aria-hidden="true">

                    <div class="rounded-3xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-800">
                        Estás por revertir el cobro de <span class="font-semibold">{{ trim(($selectedRegistration->persona?->nombre ?? '') . ' ' . ($selectedRegistration->persona?->apellido ?? '')) }}</span>. El sistema marcará el registro como reembolsado y guardará la trazabilidad de la autorización.
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-700">
                        <p class="font-semibold text-slate-950">Usuario autorizador</p>
                        <p class="mt-2">Debe ingresar la contraseña de <span class="font-semibold">{{ $eventCreatorName }}</span>, que es la persona creadora de este evento.</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Motivo de la devolución <span class="text-red-500">*</span></label>
                        <textarea wire:model.defer="refundReason" rows="4" name="refund_reason_notes" autocomplete="off" autocorrect="off" autocapitalize="sentences" spellcheck="true" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10"></textarea>
                        @error('refundReason') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Contraseña del creador del evento <span class="text-red-500">*</span></label>
                        <input wire:model.defer="refundCreatorPassword" type="password" name="refund_event_authorizer_password" autocomplete="new-password" autocorrect="off" autocapitalize="off" spellcheck="false" data-form-type="other" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10">
                        @error('refundCreatorPassword') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-5">
                    <button type="button" wire:click="closeRefundModal" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Cancelar
                    </button>
                    <button type="button" wire:click="processRefund" class="inline-flex items-center justify-center rounded-full border border-red-300 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                        Confirmar devolución
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showRefundReceiptModal && $selectedRegistration)
        @php($refundReceiptPreview = $this->refundReceiptPreviewData($selectedRegistration))
        <div class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
            <div class="w-full max-w-6xl overflow-hidden rounded-[32px] border border-white/60 bg-white shadow-[0_40px_120px_rgba(15,23,42,0.35)]">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Documento de devolución</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-950">Vista previa del recibo de devolución</h3>
                        <p class="mt-2 text-sm text-slate-500">Abre el PDF en otra pestaña para imprimirlo y solicitar la firma de quien recibe el dinero.</p>
                    </div>
                    <button type="button" wire:click="closeRefundReceiptModal" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-50">
                        ✕
                    </button>
                </div>

                <div class="grid gap-6 px-6 py-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <div class="overflow-auto rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="mx-auto max-w-4xl rounded-[28px] bg-white p-6 shadow-[0_18px_50px_rgba(15,23,42,0.08)]">
                            @include('pdf.payments.refund-receipt-content', ['document' => $refundReceiptPreview])
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Resumen</p>
                            <p class="mt-3 text-sm text-slate-600">Documento {{ $refundReceiptPreview['document_number'] ?? '---' }}</p>
                            <p class="mt-2 text-sm text-slate-600">Participante {{ $refundReceiptPreview['participant_name'] ?? '---' }}</p>
                            <p class="mt-2 text-sm text-slate-600">Monto {{ $refundReceiptPreview['payment_amount_formatted'] ?? $evento->formatMoney($refundReceiptPreview['payment_amount'] ?? 0, true) }}</p>
                        </div>

                        <a href="{{ $this->refundReceiptPdfUrl($selectedRegistration) }}" target="_blank" class="inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(109,67,255,0.22)] transition hover:scale-[1.01]">
                            Abrir recibo de devolución
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
