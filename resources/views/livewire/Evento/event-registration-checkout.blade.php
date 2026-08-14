<div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.14),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(250,204,21,0.16),_transparent_24%),linear-gradient(180deg,_#f8fbff_0%,_#eef4ff_48%,_#f8fafc_100%)] text-slate-900 dark:bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_22%),radial-gradient(circle_at_top_right,_rgba(250,204,21,0.1),_transparent_20%),linear-gradient(180deg,_#020617_0%,_#0f172a_55%,_#111827_100%)] dark:text-slate-100">
    <header class="sticky top-0 z-30 border-b border-white/60 bg-white/80 backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-950/75">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('evento', $evento) }}" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-sm font-black tracking-[0.24em] text-white dark:bg-white dark:text-slate-950">EV</div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 dark:text-sky-300">Inscripción</p>
                    <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $evento->nombreevento }}</p>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('evento', $evento) }}" class="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Volver al evento
                </a>
                <a href="{{ auth()->user()->can('dashboard.view') ? route('dashboard') : route('eventoVista') }}" class="inline-flex items-center justify-center rounded-full bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                    {{ auth()->user()->can('dashboard.view') ? 'Mi dashboard' : 'Ver eventos publicados' }}
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @php
            $requiresApi = $selectedProfile?->requiere_api ?? false;
            $identifierLabel = $selectedProfile?->etiqueta_identificador ?: 'Identificador';
            $priceAmount = $pricePreview['amount'] ?? null;
            $priceLabel = $pricePreview['label'] ?? null;
            $priceSymbol = $pricePreview['currency_symbol'] ?? null;
            $priceCode = $pricePreview['currency_code'] ?? null;
            $priceCurrency = trim(($priceSymbol ? $priceSymbol . ' ' : '') . ($priceAmount !== null ? number_format((float) $priceAmount, 2) : '0.00') . ($priceCode ? ' ' . $priceCode : ''));
        @endphp

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            <section class="rounded-[2rem] border border-white/70 bg-white/90 p-5 shadow-[0_35px_100px_-50px_rgba(15,23,42,0.45)] backdrop-blur sm:p-6 lg:p-8 dark:border-slate-800 dark:bg-slate-900/88">
                <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 dark:border-slate-800">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-400">Formulario de inscripción</p>
                    <h1 class="text-3xl font-black tracking-[-0.04em] text-slate-950 dark:text-white">Completa tu registro al evento</h1>
                    <p class="text-sm leading-7 text-slate-500 dark:text-slate-400">Primero validamos tu perfil. Si el evento es pagado, luego seleccionas el método de pago configurado por la organización.</p>
                </div>

                @if ($registrationSuccess && $existingRegistration)
                    <div class="mt-6 rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-5 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
                        {{ $lookupMessage }}
                    </div>
                @endif

                @if ($existingRegistration)
                    <div class="mt-6 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-6 dark:border-slate-800 dark:bg-slate-950/50">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Ya tienes una inscripción registrada</h2>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Estado general</p>
                                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ str($existingRegistration->estado)->replace('_', ' ')->title() }}</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Estado del pago</p>
                                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ str($existingRegistration->estado_pago)->replace('_', ' ')->title() }}</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Tipo de perfil</p>
                                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $existingRegistration->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Método de pago</p>
                                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $existingRegistration->metodoPago?->nombre ?? 'No aplica' }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-6 space-y-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tipo de perfil <span class="text-red-500">*</span></label>
                                <select wire:model.live="tipoperfil_id" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                    <option value="">Selecciona un tipo</option>
                                    @foreach ($tiposPerfil as $tipoPerfil)
                                        <option value="{{ $tipoPerfil->id }}">{{ $tipoPerfil->tipoperfil }}</option>
                                    @endforeach
                                </select>
                                @error('tipoperfil_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $identifierLabel }} @if($selectedProfile)<span class="text-red-500">*</span>@endif</label>
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <input wire:model.live="lookup_identifier" type="text" placeholder="Ingresa el identificador" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                    @if ($selectedProfile)
                                        <button type="button" wire:click="lookupProfileData" class="inline-flex shrink-0 items-center justify-center rounded-2xl border border-sky-300 px-4 py-3 text-sm font-medium text-sky-700 transition hover:bg-sky-50 dark:border-sky-800 dark:text-sky-300 dark:hover:bg-sky-950/20">
                                            Consultar
                                        </button>
                                    @endif
                                </div>
                                @error('lookup_identifier') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                @if ($lookupMessage)
                                    <p class="mt-2 text-sm {{ $lookupResolved ? 'text-emerald-600 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">{{ $lookupMessage }}</p>
                                @endif
                                @if ($selectedProfile && ! $selectedProfile->requiere_api && $selectedProfile->permite_registro_manual && ! $manualEntryEnabled)
                                    <button type="button" wire:click="enableManualEntry" class="mt-3 text-sm font-medium text-amber-700 underline underline-offset-2 dark:text-amber-300">
                                        No aparece en la API, completar manualmente
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if ($selectedProfile)
                            <div class="rounded-[1.5rem] border {{ $requiresApi ? 'border-sky-200 bg-sky-50 dark:border-sky-900/50 dark:bg-sky-950/20' : 'border-amber-200 bg-amber-50 dark:border-amber-900/50 dark:bg-amber-950/20' }} p-4 text-sm {{ $requiresApi ? 'text-sky-800 dark:text-sky-200' : 'text-amber-800 dark:text-amber-200' }}">
                                @if ($requiresApi)
                                    Este perfil requiere validación por API antes de permitir la inscripción.
                                @else
                                    Este perfil puede completar los datos manualmente si la API no devuelve información.
                                @endif
                            </div>
                        @endif

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre <span class="text-red-500">*</span></label>
                                <input wire:model.live="nombre" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @error('nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Apellido <span class="text-red-500">*</span></label>
                                <input wire:model.live="apellido" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @error('apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo personal <span class="text-red-500">*</span></label>
                                <input wire:model.live="correo" type="email" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @error('correo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo institucional</label>
                                <input wire:model.live="correoInstitucional" type="email" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @error('correoInstitucional') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Fecha de nacimiento</label>
                                <input wire:model.live="fechaNacimiento" type="date" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @error('fechaNacimiento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Sexo <span class="text-red-500">*</span></label>
                                <input wire:model.live="sexo" type="text" placeholder="F / M / Otro" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @error('sexo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nacionalidad <span class="text-red-500">*</span></label>
                                <select wire:model.live="IdNacionalidad" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                    <option value="">Selecciona una nacionalidad</option>
                                    @foreach ($nacionalidades as $nacionalidad)
                                        <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                                    @endforeach
                                </select>
                                @error('IdNacionalidad') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Teléfono <span class="text-red-500">*</span></label>
                                <input wire:model.live="telefono" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @error('telefono') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Dirección <span class="text-red-500">*</span></label>
                                <input wire:model.live="direccion" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @error('direccion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if ($selectedProfile?->tipo_identificador === 'dni')
                            <input wire:model.live="dni" type="hidden">
                        @endif
                        @if ($selectedProfile?->tipo_identificador === 'numeroCuenta')
                            <input wire:model.live="numeroCuenta" type="hidden">
                        @endif
                        @if ($selectedProfile?->tipo_identificador === 'numeroEmpleado')
                            <input wire:model.live="numeroEmpleado" type="hidden">
                        @endif

                        @if ($evento->tipo_acceso === 'pagada')
                            <div class="space-y-4 border-t border-slate-200 pt-6 dark:border-slate-800">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-400">Pasarela de pago</p>
                                    <h2 class="mt-2 text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">Selecciona cómo deseas pagar</h2>
                                </div>

                                @if ($metodosPago->isEmpty())
                                    <div class="rounded-[1.5rem] border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 dark:border-rose-900/50 dark:bg-rose-950/20 dark:text-rose-200">
                                        Aún no hay métodos de pago configurados para este evento. El administrador debe registrarlos en la sección de configuración del sistema.
                                    </div>
                                @else
                                    <div class="grid gap-4 md:grid-cols-2">
                                        @foreach ($metodosPago as $metodo)
                                            <button type="button" wire:click="$set('metodo_pago_id', '{{ $metodo->id }}')" class="rounded-[1.5rem] border p-4 text-left transition {{ (string) $metodo->id === $metodo_pago_id ? 'border-yellow-400 bg-yellow-50 shadow-sm dark:border-yellow-500 dark:bg-yellow-500/10' : 'border-slate-200 bg-white hover:border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-slate-600' }}">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div>
                                                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $metodo->nombre }}</p>
                                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $metodo->proveedor ?: 'Proveedor directo' }}</p>
                                                    </div>
                                                    <div class="flex flex-col items-end gap-2">
                                                        <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] {{ $metodo->requiere_api ? 'bg-sky-100 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300' }}">
                                                            {{ $metodo->requiere_api ? 'API' : 'Manual' }}
                                                        </span>
                                                        <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] {{ $metodo->tipo === 'efectivo' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300' : ($metodo->tipo === 'transferencia' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300' : 'bg-violet-100 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300') }}">
                                                            {{ $metodo->tipo }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @if ($metodo->descripcion)
                                                    <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $metodo->descripcion }}</p>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                    @error('metodo_pago_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                @endif

                                @if ($selectedPaymentMethod)
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950/50">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $selectedPaymentMethod->nombre }}</h3>
                                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $selectedPaymentMethod->requiere_api ? 'Este método está preparado para integrarse con una API de cobro.' : 'Este método registra el pago para validación manual.' }}
                                                </p>
                                            </div>
                                            <div class="rounded-2xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 dark:bg-slate-900 dark:text-slate-100">
                                                Total: {{ $priceCurrency }}
                                            </div>
                                        </div>

                                        @if ($selectedPaymentMethod->instrucciones)
                                            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/20 dark:text-amber-200">
                                                {{ $selectedPaymentMethod->instrucciones }}
                                            </div>
                                        @endif

                                        @if ($selectedPaymentMethod->requiresProof())
                                            <div class="mt-4 rounded-[1.5rem] border border-violet-200 bg-violet-50/70 p-4 dark:border-violet-900/50 dark:bg-violet-950/20">
                                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                                    <div>
                                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Comprobante de pago <span class="text-red-500">*</span></p>
                                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                            Sube una imagen o PDF del comprobante para {{ $selectedPaymentMethod->tipo === 'transferencia' ? 'validar la transferencia bancaria' : 'respaldar el cobro con tarjeta' }}.
                                                        </p>
                                                    </div>
                                                    <span class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                                                        JPG, PNG o PDF
                                                    </span>
                                                </div>

                                                <div class="mt-4">
                                                    <input wire:model="paymentProof" type="file" accept=".jpg,.jpeg,.png,.pdf,application/pdf,image/png,image/jpeg" class="block w-full rounded-2xl border border-dashed border-violet-300 bg-white px-4 py-4 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-violet-500 dark:border-violet-800 dark:bg-slate-900 dark:text-slate-200">
                                                    <div wire:loading wire:target="paymentProof" class="mt-2 text-sm text-violet-600 dark:text-violet-300">Cargando comprobante...</div>
                                                    @if ($paymentProof)
                                                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                                            Archivo listo: {{ method_exists($paymentProof, 'getClientOriginalName') ? $paymentProof->getClientOriginalName() : 'Comprobante cargado' }}
                                                        </p>
                                                    @endif
                                                    @error('paymentProof') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        @endif

                                        @if (count($paymentFields))
                                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                                @foreach ($paymentFields as $field)
                                                    @php
                                                        $fieldName = $field['name'] ?? '';
                                                        $fieldType = $field['type'] ?? 'text';
                                                        $fieldLabel = $field['label'] ?? $fieldName;
                                                        $fieldPlaceholder = $field['placeholder'] ?? '';
                                                    @endphp
                                                    @if ($fieldName)
                                                        <div class="{{ ($field['span'] ?? '') === 'full' ? 'md:col-span-2' : '' }}">
                                                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $fieldLabel }} @if(!empty($field['required']))<span class="text-red-500">*</span>@endif</label>
                                                            <input wire:model.live="paymentData.{{ $fieldName }}" type="{{ $fieldType }}" placeholder="{{ $fieldPlaceholder }}" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                                            @error('paymentData.' . $fieldName) <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 dark:border-slate-800 sm:flex-row sm:justify-end">
                            <button wire:click="register" type="button" @disabled($evento->tipo_acceso === 'pagada' && $metodosPago->isEmpty()) class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 dark:disabled:bg-slate-700 dark:disabled:text-slate-400">
                                {{ $evento->tipo_acceso === 'pagada' ? 'Continuar con el pago' : 'Confirmar inscripción' }}
                            </button>
                        </div>
                    </div>
                @endif
            </section>

            <aside class="space-y-6">
                <div class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-[0_35px_100px_-50px_rgba(15,23,42,0.42)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-400">Resumen del evento</p>
                    <h2 class="mt-3 text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">{{ $evento->nombreevento }}</h2>
                    <div class="mt-5 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Modalidad:</span> {{ $evento->modalidad?->modalidad ?? 'Sin modalidad' }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Fechas:</span> {{ $evento->fechainicio?->format('d/m/Y') }} - {{ $evento->fechafinal?->format('d/m/Y') }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Ubicación:</span> {{ $evento->localidad_display }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Acceso:</span> {{ $evento->tipo_acceso === 'pagada' ? 'Evento pagado' : 'Evento gratuito' }}</p>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-[0_35px_100px_-50px_rgba(15,23,42,0.42)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-400">Costo calculado</p>
                    @if ($priceAmount !== null)
                        <p class="mt-3 text-4xl font-black tracking-[-0.04em] text-slate-950 dark:text-white">{{ $priceCurrency }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $priceLabel }}</p>
                    @else
                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Selecciona un tipo de perfil para calcular el costo correspondiente.</p>
                    @endif
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/50 dark:text-slate-300">
                    <p class="font-semibold text-slate-900 dark:text-slate-100">Importante</p>
                    <p class="mt-3 leading-7">Si tu tipo de perfil requiere verificación institucional, primero debes consultar tus datos con el identificador correspondiente. Cuando el evento sea pagado, la inscripción quedará asociada al método de pago seleccionado.</p>
                </div>
            </aside>
        </div>
    </main>
</div>
