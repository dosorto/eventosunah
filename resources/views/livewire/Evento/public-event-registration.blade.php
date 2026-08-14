<div class="space-y-4">
    @if ($registrationSuccess)
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
            {{ $lookupMessage }}
        </div>
    @endif

    @if ($alreadyRegistered)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
            Ya existe una inscripción registrada para esta persona en el evento.
        </div>
    @endif

    <button wire:click="openRegistrationModal" class="inline-flex items-center justify-center rounded-full bg-yellow-400 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-yellow-300">
        Inscribirme a este evento
    </button>

    @if ($showRegistrationModal)
        @php
            $requiresApi = $selectedProfile?->requiere_api ?? false;
            $identifierLabel = $selectedProfile?->etiqueta_identificador ?: 'Identificador';
            $priceAmount = $pricePreview['amount'] ?? null;
            $priceLabel = $pricePreview['label'] ?? null;
            $coreReadOnly = false;
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
            <div class="max-h-full w-full max-w-5xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Inscripción al evento</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">La inscripción ahora se realiza a nivel de evento. Completa el perfil del participante y confirma el registro.</p>
                    </div>
                    <button wire:click="closeRegistrationModal" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/></svg>
                    </button>
                </div>

                <div class="grid gap-5 px-6 py-6 lg:grid-cols-[minmax(0,0.95fr)_minmax(320px,0.65fr)]">
                    <div class="space-y-5">
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
                                <div class="flex gap-2">
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

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre <span class="text-red-500">*</span></label>
                                <input wire:model.live="nombre" type="text" @if($coreReadOnly) readonly @endif class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 read-only:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:read-only:bg-slate-800">
                                @error('nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Apellido <span class="text-red-500">*</span></label>
                                <input wire:model.live="apellido" type="text" @if($coreReadOnly) readonly @endif class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 read-only:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:read-only:bg-slate-800">
                                @error('apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo personal <span class="text-red-500">*</span></label>
                                <input wire:model.live="correo" type="email" @if($coreReadOnly) readonly @endif class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 read-only:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:read-only:bg-slate-800">
                                @error('correo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo institucional</label>
                                <input wire:model.live="correoInstitucional" type="email" @if($coreReadOnly) readonly @endif class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 read-only:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:read-only:bg-slate-800">
                                @error('correoInstitucional') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Fecha de nacimiento <span class="text-red-500">*</span></label>
                                <input wire:model.live="fechaNacimiento" type="date" @if($coreReadOnly) readonly @endif class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 read-only:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:read-only:bg-slate-800">
                                @error('fechaNacimiento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Sexo <span class="text-red-500">*</span></label>
                                <input wire:model.live="sexo" type="text" placeholder="F / M / Otro" @if($coreReadOnly) readonly @endif class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 read-only:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:read-only:bg-slate-800">
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
                    </div>

                    <aside class="space-y-4">
                        <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Resumen de inscripción</p>
                            <h4 class="mt-3 text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $evento->nombreevento }}</h4>
                            <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Modalidad:</span> {{ $evento->modalidad?->modalidad ?? 'Sin modalidad' }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Fechas:</span> {{ $evento->fechainicio?->format('d/m/Y') }} - {{ $evento->fechafinal?->format('d/m/Y') }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Localidad:</span> {{ $evento->localidad_display }}</p>
                            </div>
                        </div>

                        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Costo aplicable</p>
                            @if ($priceAmount !== null)
                                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ trim((($pricePreview['currency_symbol'] ?? '') ? $pricePreview['currency_symbol'] . ' ' : '') . number_format((float) $priceAmount, 2) . (($pricePreview['currency_code'] ?? '') ? ' ' . $pricePreview['currency_code'] : '')) }}</p>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $priceLabel }}</p>
                            @else
                                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Selecciona un tipo de perfil para calcular el costo.</p>
                            @endif
                        </div>

                        <div class="rounded-[1.75rem] border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                            Para perfiles que requieren API, primero debes consultar los datos con el identificador indicado. Los perfiles externos pueden completar manualmente si no están disponibles en la API.
                        </div>
                    </aside>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 dark:border-slate-800 sm:flex-row sm:justify-end">
                    <button wire:click="closeRegistrationModal" type="button" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Cancelar
                    </button>
                    <button wire:click="register" type="button" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400">
                        Confirmar inscripción
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
