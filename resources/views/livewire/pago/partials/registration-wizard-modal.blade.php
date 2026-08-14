@php
    $registrationStepDefinitions = [
        1 => ['title' => 'Paso 1', 'label' => 'Identidad'],
        2 => ['title' => 'Paso 2', 'label' => 'Participante'],
        3 => ['title' => 'Paso 3', 'label' => 'Cobro'],
    ];

    $registrationParticipantName = trim(collect([
        $registrationPrimerNombre ?? null,
        $registrationSegundoNombre ?? null,
        $registrationPrimerApellido ?? null,
        $registrationSegundoApellido ?? null,
    ])->filter(fn ($part) => filled($part))->implode(' '));
    $registrationFoundLocally = $registrationPersonaExists;
    $registrationFoundInApi = $registrationLookupResolved && ! $registrationPersonaExists;
    $registrationMessageState = $registrationExistingEventRegistration
        ? 'error'
        : ($registrationLookupResolved ? 'success' : ($registrationManualEntryEnabled ? 'warning' : 'info'));

    $registrationMessageClasses = match ($registrationMessageState) {
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'error' => 'border-red-200 bg-red-50 text-red-700',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-700',
        default => 'border-slate-200 bg-slate-50 text-slate-600',
    };

    $registrationPriceAmount = $registrationPricePreview['amount'] ?? null;
    $registrationPriceCurrency = trim(
        (($registrationPricePreview['currency_symbol'] ?? '') ? $registrationPricePreview['currency_symbol'] . ' ' : '')
        . ($registrationPriceAmount !== null ? number_format((float) $registrationPriceAmount, 2) : '')
        . (($registrationPricePreview['currency_code'] ?? '') ? ' ' . $registrationPricePreview['currency_code'] : '')
    );
@endphp

<div class="fixed inset-0 z-[90] overflow-y-auto bg-slate-950/60 px-4 py-5 backdrop-blur-sm sm:px-6 sm:py-8">
    <div class="mx-auto w-full max-w-7xl space-y-4 sm:space-y-5">
        <section class="rounded-[2rem] border border-slate-200 bg-white p-4 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.28)] sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-500">Inscripción manual</p>
                    <h3 class="text-2xl font-semibold text-slate-950 sm:text-3xl">Registrar participante y cobrar evento</h3>
                    <p class="max-w-3xl text-sm leading-6 text-slate-600">
                        Busca por número de identidad en la base local, consulta externos si es necesario y finaliza el cobro en un flujo de tres pasos.
                    </p>
                </div>

                <button type="button" wire:click="closeRegistrationWizardModal" class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-[1.75rem] border border-slate-300 text-2xl text-slate-500 transition hover:bg-slate-50">
                    ×
                </button>
            </div>
        </section>

        <section class="rounded-[2rem] border border-slate-200 bg-white p-4 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] sm:p-6">
            <div class="overflow-x-auto">
                <div class="flex min-w-[44rem] items-start gap-4">
                    @foreach ($registrationStepDefinitions as $wizardStep => $wizardMeta)
                        @php
                            $isCurrent = $registrationStep === $wizardStep;
                            $isDone = $wizardStep < $registrationStep;
                        @endphp

                        <div class="flex flex-1 items-start gap-4">
                            <div class="flex min-w-[6.5rem] flex-col items-center text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full border-2 text-sm font-bold transition {{ $isDone ? 'border-emerald-500 bg-emerald-500 text-white' : ($isCurrent ? 'border-emerald-500 bg-white text-emerald-600 shadow-[0_0_0_6px_rgba(16,185,129,0.12)]' : 'border-slate-300 bg-slate-100 text-slate-400') }}">
                                    @if ($isDone)
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                                        </svg>
                                    @else
                                        {{ $wizardStep }}
                                    @endif
                                </span>
                                <span class="mt-3 text-xs font-semibold uppercase tracking-[0.18em] {{ $isCurrent || $isDone ? 'text-slate-900' : 'text-slate-400' }}">{{ $wizardMeta['title'] }}</span>
                                <span class="mt-1 text-xs {{ $isCurrent || $isDone ? 'text-slate-500' : 'text-slate-400' }}">{{ $wizardMeta['label'] }}</span>
                            </div>

                            @if (! $loop->last)
                                <div class="mt-5 h-1 flex-1 rounded-full {{ $isDone ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] sm:p-7">
            <div class="space-y-7">
                @if($registrationLookupMessage)
                    <div class="rounded-2xl border px-4 py-3 text-sm {{ $registrationMessageClasses }}">
                        {{ $registrationLookupMessage }}
                    </div>
                @endif

                @switch($registrationStep)
                    @case(1)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold text-slate-950 sm:text-3xl">Paso 1. Busca por identidad</h2>
                                <p class="mt-2 text-sm text-slate-500">El sistema revisará primero la base local. Si no existe el participante, consultará la API de externos.</p>
                            </div>

                            <div class="mx-auto max-w-4xl rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 sm:p-6">
                                <label class="mb-2 block text-sm font-medium text-slate-700">Número de identidad <span class="text-red-500">*</span></label>
                                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-end">
                                    <div>
                                        <input wire:model.defer="registrationLookupIdentifier" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Ingresa el número de identidad">
                                        @error('registrationLookupIdentifier') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <button type="button" wire:click="lookupRegistrationParticipant" wire:loading.attr="disabled" wire:target="lookupRegistrationParticipant" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-70">
                                        <span wire:loading.remove wire:target="lookupRegistrationParticipant">Buscar participante</span>
                                        <span wire:loading wire:target="lookupRegistrationParticipant">Buscando...</span>
                                    </button>
                                </div>

                                <p class="mt-3 text-xs text-slate-500">Si no se encuentra en la base local ni en externos, el siguiente paso permitirá completar los datos manualmente.</p>
                            </div>
                        </div>
                    @break

                    @case(2)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold text-slate-950 sm:text-3xl">Paso 2. Confirma la información del participante</h2>
                                <p class="mt-2 text-sm text-slate-500">
                                    @if($registrationFoundLocally)
                                        Se encontró una persona registrada localmente. Revisa la información antes de pasar al cobro.
                                    @elseif($registrationFoundInApi)
                                        La información llegó desde la API de externos y puede ajustarse antes de continuar.
                                    @else
                                        No se encontró el participante. Completa manualmente los datos para registrarlo.
                                    @endif
                                </p>
                            </div>

                            @if($registrationFoundLocally)
                                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4">
                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Perfil</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationSelectedProfile?->tipoperfil ?: 'Sin perfil' }}</p>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4">
                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Identidad</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationDni ?: 'Sin identidad' }}</p>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4">
                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Correo</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationCorreo ?: 'Sin correo' }}</p>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4">
                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Teléfono</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationTelefono ?: 'Sin teléfono' }}</p>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 md:col-span-2">
                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Nombre completo</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationParticipantName ?: 'Sin nombre' }}</p>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 md:col-span-2">
                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Dirección</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationDireccion ?: 'Sin dirección' }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5">
                                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Primer nombre <span class="text-red-500">*</span></label>
                                            <input wire:model.defer="registrationPrimerNombre" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                            @error('registrationPrimerNombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Segundo nombre</label>
                                            <input wire:model.defer="registrationSegundoNombre" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                            @error('registrationSegundoNombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Primer apellido <span class="text-red-500">*</span></label>
                                            <input wire:model.defer="registrationPrimerApellido" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                            @error('registrationPrimerApellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Segundo apellido</label>
                                            <input wire:model.defer="registrationSegundoApellido" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                            @error('registrationSegundoApellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Identidad <span class="text-red-500">*</span></label>
                                            <input wire:model.defer="registrationDni" type="text" readonly class="block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none">
                                            @error('registrationDni') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Correo <span class="text-red-500">*</span></label>
                                            <input wire:model.defer="registrationCorreo" type="email" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                            @error('registrationCorreo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Teléfono <span class="text-red-500">*</span></label>
                                            <input wire:model.defer="registrationTelefono" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                            @error('registrationTelefono') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Fecha de nacimiento</label>
                                            <input wire:model.defer="registrationFechaNacimiento" type="date" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                            @error('registrationFechaNacimiento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Sexo <span class="text-red-500">*</span></label>
                                            <select wire:model.defer="registrationSexo" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                                <option value="">Selecciona una opción</option>
                                                <option value="M">Masculino</option>
                                                <option value="F">Femenino</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                            @error('registrationSexo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Nacionalidad <span class="text-red-500">*</span></label>
                                            <select wire:model.defer="registrationIdNacionalidad" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                                <option value="">Selecciona una nacionalidad</option>
                                                @foreach($registrationNationalities as $nationality)
                                                    <option value="{{ $nationality->id }}">{{ $nationality->nombreNacionalidad }}</option>
                                                @endforeach
                                            </select>
                                            @error('registrationIdNacionalidad') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="md:col-span-2 xl:col-span-3">
                                            <label class="mb-2 block text-sm font-medium text-slate-700">Dirección <span class="text-red-500">*</span></label>
                                            <textarea wire:model.defer="registrationDireccion" rows="3" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"></textarea>
                                            @error('registrationDireccion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @break

                    @case(3)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold text-slate-950 sm:text-3xl">Paso 3. Confirma el cobro</h2>
                                <p class="mt-2 text-sm text-slate-500">Revisa el participante, registra el método de pago y completa la inscripción.</p>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
                                <div class="space-y-5">
                                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Participante</p>
                                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Nombre</p>
                                                <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationParticipantName ?: 'Pendiente' }}</p>
                                            </div>
                                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Perfil</p>
                                                <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationSelectedProfile?->tipoperfil ?: 'Externo' }}</p>
                                            </div>
                                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Identidad</p>
                                                <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationDni ?: 'Pendiente' }}</p>
                                            </div>
                                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Correo</p>
                                                <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationCorreo ?: 'Pendiente' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($evento->tipo_acceso === 'pagada')
                                        <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5">
                                            <div class="grid gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">Método de cobro <span class="text-red-500">*</span></label>
                                                    <select wire:model.live="registrationMetodoPagoId" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                                        <option value="">Selecciona un método</option>
                                                        @foreach($registrationPaymentMethods as $method)
                                                            <option value="{{ $method->id }}">{{ $method->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('registrationMetodoPagoId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">Monto a cobrar <span class="text-red-500">*</span></label>
                                                    <input wire:model.defer="registrationAmount" type="number" step="0.01" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                                    @error('registrationAmount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>
                                            </div>

                                            @if($registrationSelectedPaymentMethod?->isTransfer())
                                                <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-5">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Transferencia bancaria</p>
                                                    <p class="mt-3 text-sm text-slate-600">Comparte estos datos con el participante y adjunta el comprobante para dejar el pago en revisión.</p>

                                                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Banco</p>
                                                            <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationSelectedPaymentMethod->banco_nombre ?: 'Sin banco configurado' }}</p>
                                                        </div>
                                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Número de cuenta</p>
                                                            <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationSelectedPaymentMethod->numero_cuenta ?: 'Sin cuenta configurada' }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Titular</p>
                                                        <p class="mt-2 text-sm font-semibold text-slate-950">{{ $registrationSelectedPaymentMethod->titular_cuenta ?: 'Sin titular configurado' }}</p>
                                                    </div>

                                                    <div class="mt-5">
                                                        <label class="mb-2 block text-sm font-medium text-slate-700">Comprobante de pago <span class="text-red-500">*</span></label>
                                                        <label class="flex cursor-pointer flex-col items-center justify-center rounded-[1.75rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center transition hover:border-emerald-400 hover:bg-emerald-50/40">
                                                            <input wire:model="registrationPaymentProof" type="file" accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                                                            <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm">
                                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0 4 4m-4-4L8 8" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16.5A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5" />
                                                                </svg>
                                                            </span>
                                                            <span class="mt-4 text-lg font-semibold text-slate-950">Arrastra o selecciona el comprobante</span>
                                                            <span class="mt-2 text-sm text-slate-500">JPG, PNG o PDF. Máximo 10 MB.</span>
                                                            @if($registrationPaymentProof)
                                                                <span class="mt-4 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $registrationPaymentProof->getClientOriginalName() }}</span>
                                                            @endif
                                                        </label>
                                                        @error('registrationPaymentProof') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>
                                            @endif

                                            @if($registrationSelectedPaymentMethod?->isCash())
                                                <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                                                    El cobro en efectivo se registrará como pagado al guardar la inscripción.
                                                </div>
                                            @endif

                                            <div class="mt-5">
                                                <label class="mb-2 block text-sm font-medium text-slate-700">Observaciones del cobro</label>
                                                <textarea wire:model.defer="registrationPaymentNotes" rows="4" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"></textarea>
                                                @error('registrationPaymentNotes') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                                            Este evento es gratuito. Al guardar, la persona quedará inscrita inmediatamente.
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-5">
                                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Resumen del cobro</p>
                                        <dl class="mt-4 space-y-3 text-sm text-slate-600">
                                            <div>
                                                <dt class="text-slate-500">Evento</dt>
                                                <dd class="font-semibold text-slate-950">{{ $evento->nombreevento }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-slate-500">Precio aplicable</dt>
                                                <dd class="font-semibold text-slate-950">{{ $registrationPriceCurrency !== '' ? $registrationPriceCurrency : 'Sin tarifario' }}</dd>
                                                @if($registrationPricePreview['label'] ?? null)
                                                    <p class="mt-1 text-xs text-slate-500">{{ $registrationPricePreview['label'] }}</p>
                                                @endif
                                            </div>
                                            <div>
                                                <dt class="text-slate-500">Método</dt>
                                                <dd class="font-semibold text-slate-950">{{ $evento->tipo_acceso === 'pagada' ? ($registrationSelectedPaymentMethod?->nombre ?: 'Pendiente') : 'Evento gratuito' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-slate-500">Monto final</dt>
                                                <dd class="font-semibold text-slate-950">{{ $evento->formatMoney((float) ($registrationAmount ?: 0), true) }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-slate-500">Resultado</dt>
                                                <dd class="font-semibold text-slate-950">
                                                    @if($evento->tipo_acceso !== 'pagada')
                                                        Inscrito
                                                    @elseif($registrationSelectedPaymentMethod?->isCash())
                                                        Pagado e inscrito
                                                    @elseif($registrationSelectedPaymentMethod?->isTransfer())
                                                        Pendiente de validación
                                                    @else
                                                        Pendiente
                                                    @endif
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                                        Si la persona aún no tiene cuenta, el sistema la creará automáticamente y enviará sus credenciales por correo.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @break
                @endswitch

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 pt-5">
                    <div class="text-sm text-slate-500">Paso {{ $registrationStep }} de 3</div>
                    <div class="flex flex-wrap items-center gap-3">
                        @if($registrationStep > 1)
                            <button type="button" wire:click="previousRegistrationWizardStep" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Paso anterior
                            </button>
                        @endif

                        <button type="button" wire:click="closeRegistrationWizardModal" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Cancelar
                        </button>

                        @if($registrationStep < 3)
                            <button type="button" wire:click="continueRegistrationWizard" class="inline-flex items-center justify-center rounded-full bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                                Siguiente paso
                            </button>
                        @else
                            <button type="button" wire:click="saveRegistrationWizard" wire:loading.attr="disabled" wire:target="saveRegistrationWizard" class="inline-flex items-center justify-center rounded-full bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-70">
                                <span wire:loading.remove wire:target="saveRegistrationWizard">Guardar inscripción</span>
                                <span wire:loading wire:target="saveRegistrationWizard">Guardando...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
