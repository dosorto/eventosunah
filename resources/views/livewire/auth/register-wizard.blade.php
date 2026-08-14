<div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.12),_transparent_30%),linear-gradient(180deg,#f8fafc_0%,#eef6ff_100%)] px-4 py-5 text-slate-900 sm:px-6 sm:py-8">
    <div class="mx-auto max-w-6xl space-y-4 sm:space-y-5">
        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.28)] sm:p-7">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-500">Registro de participante</p>
                    <h1 class="text-2xl font-semibold sm:text-3xl">Crea tu cuenta para inscribirte a eventos</h1>
                    <p class="max-w-2xl text-sm leading-6 text-slate-600">
                        Ingresa tu identidad, confirma tus datos personales y crea las credenciales de acceso.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[22rem]">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Perfil</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">Detectado automaticamente</p>
                        <p class="mt-1 text-xs text-slate-500">Empleado, estudiante o externo según la información encontrada.</p>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Proceso</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">4 pasos guiados</p>
                        <p class="mt-1 text-xs text-slate-500">Optimizado para escritorio y móvil.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-slate-200 bg-white p-4 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] sm:p-6">
            <div class="overflow-x-auto">
                <div class="flex min-w-[36rem] items-start gap-4">
                    @foreach ($stepDefinitions as $wizardStep)
                        @php
                            $number = $wizardStep['number'];
                            $isCurrent = $step === $number;
                            $isDone = $number < $step;
                        @endphp

                        <div class="flex flex-1 items-start gap-4">
                            <button type="button" wire:click="goToStep({{ $number }})" class="flex min-w-[5.5rem] flex-col items-center text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full border-2 text-sm font-bold transition {{ $isDone ? 'border-emerald-500 bg-emerald-500 text-white' : ($isCurrent ? 'border-emerald-500 bg-white text-emerald-600 shadow-[0_0_0_6px_rgba(16,185,129,0.12)]' : 'border-slate-300 bg-slate-100 text-slate-400') }}">
                                    @if ($isDone)
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                                        </svg>
                                    @else
                                        {{ $number }}
                                    @endif
                                </span>
                                <span class="mt-3 text-xs font-semibold uppercase tracking-[0.18em] {{ $isCurrent || $isDone ? 'text-slate-900' : 'text-slate-400' }}">{{ $wizardStep['title'] }}</span>
                                <span class="mt-1 text-xs {{ $isCurrent || $isDone ? 'text-slate-500' : 'text-slate-400' }}">{{ $wizardStep['label'] }}</span>
                            </button>

                            @if (! $loop->last)
                                <div class="mt-5 h-1 flex-1 rounded-full {{ $number < $step ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        @if (session()->has('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($lookupMessage !== '')
            <div class="rounded-2xl px-4 py-3 text-sm {{ $lookupSuccess ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-amber-200 bg-amber-50 text-amber-700' }}">
                {{ $lookupMessage }}
            </div>
        @endif

        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] sm:p-7">
            <form wire:submit.prevent="openConfirmationModal" class="space-y-7">
                @if ($step === 1)
                    <div class="mx-auto max-w-4xl space-y-6">
                        <div class="text-center">
                            <h2 class="text-2xl font-semibold sm:text-3xl">Paso 1. Ingresa tu número de identidad</h2>
                            <p class="mt-2 text-sm text-slate-500">
                                El sistema consultará la base local y luego la API configurada para completar tus datos.
                            </p>
                        </div>

                        <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4 sm:p-5">
                            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Número de identidad <span class="text-red-500">*</span></label>
                                    <input wire:model.live="lookup_identifier" type="text" autocomplete="off" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Ingresa tu número de identidad">
                                    @error('lookup_identifier') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <button type="button" wire:click="verifyIdentity" @disabled(trim($lookup_identifier) === '') class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:bg-slate-300">
                                    <span wire:loading.remove wire:target="verifyIdentity">Buscar identidad</span>
                                    <span wire:loading wire:target="verifyIdentity">Buscando...</span>
                                </button>
                            </div>
                            <p class="mt-4 text-xs leading-5 text-slate-500">
                                Si no se encuentra información, continuarás como perfil externo y podrás completar los datos manualmente.
                            </p>
                        </div>
                    </div>
                @endif

                @if ($step === 2)
                    <div class="space-y-6">
                        <div class="text-center">
                            <h2 class="text-2xl font-semibold sm:text-3xl">Paso 2. Confirma tus datos personales</h2>
                            <p class="mt-2 text-sm text-slate-500">El perfil fue definido automáticamente a partir de la información encontrada.</p>
                        </div>

                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                Perfil: {{ $this->inferredProfileName() }}
                            </span>
                            @if ($lookupSuccess)
                                <span class="inline-flex items-center rounded-full bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 ring-1 ring-sky-200">Datos encontrados en API</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 ring-1 ring-amber-200">Completar manualmente</span>
                            @endif
                        </div>

                        @error('tipoperfil_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-4 sm:p-5">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Primer nombre <span class="text-red-500">*</span></label>
                                    <input wire:model.blur="primer_nombre" type="text" autocomplete="given-name" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('primer_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Segundo nombre</label>
                                    <input wire:model.blur="segundo_nombre" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('segundo_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Primer apellido <span class="text-red-500">*</span></label>
                                    <input wire:model.blur="primer_apellido" type="text" autocomplete="family-name" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('primer_apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Segundo apellido</label>
                                    <input wire:model.blur="segundo_apellido" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('segundo_apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Identidad <span class="text-red-500">*</span></label>
                                    <input wire:model.blur="dni" type="text" readonly class="block w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-600 outline-none">
                                    @error('dni') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                @if ($this->showNumeroEmpleado())
                                    <div>
                                        <label class="mb-2 block text-sm font-medium">Número de empleado</label>
                                        <input wire:model.blur="numeroEmpleado" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        @error('numeroEmpleado') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                @endif

                                @if ($this->showNumeroCuenta())
                                    <div>
                                        <label class="mb-2 block text-sm font-medium">Número de cuenta</label>
                                        <input wire:model.blur="numeroCuenta" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        @error('numeroCuenta') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                @endif

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Fecha de nacimiento <span class="text-red-500">*</span></label>
                                    <input wire:model.blur="fechaNacimiento" type="date" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('fechaNacimiento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Sexo <span class="text-red-500">*</span></label>
                                    <select wire:model.change="sexo" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        <option value="">Selecciona una opción</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                    @error('sexo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Teléfono <span class="text-red-500">*</span></label>
                                    <input wire:model.blur="telefono" type="text" autocomplete="tel" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('telefono') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Nacionalidad <span class="text-red-500">*</span></label>
                                    <select wire:model.change="IdNacionalidad" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        <option value="">Selecciona una nacionalidad</option>
                                        @foreach ($nacionalidades as $nacionalidad)
                                            <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdNacionalidad') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Correo institucional</label>
                                    <input wire:model.blur="correoInstitucional" type="email" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('correoInstitucional') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2 lg:col-span-3">
                                    <label class="mb-2 block text-sm font-medium">Dirección <span class="text-red-500">*</span></label>
                                    <textarea wire:model.blur="direccion" rows="3" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"></textarea>
                                    @error('direccion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($step === 3)
                    <div class="mx-auto max-w-4xl space-y-6">
                        <div class="text-center">
                            <h2 class="text-2xl font-semibold sm:text-3xl">Paso 3. Configura tu usuario</h2>
                            <p class="mt-2 text-sm text-slate-500">Define el correo y contraseña para ingresar al sistema.</p>
                        </div>

                        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-4 sm:p-5">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-medium">Correo del usuario <span class="text-red-500">*</span></label>
                                    <input wire:model.blur="email" type="email" autocomplete="email" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Contraseña <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input wire:model.blur="password" type="{{ $showPassword ? 'text' : 'password' }}" autocomplete="new-password" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-20 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        <button type="button" wire:click="togglePasswordVisibility" class="absolute inset-y-0 right-3 inline-flex items-center text-xs font-semibold text-slate-500">
                                            {{ $showPassword ? 'Ocultar' : 'Mostrar' }}
                                        </button>
                                    </div>
                                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium">Confirmar contraseña <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input wire:model.blur="password_confirmation" type="{{ $showPasswordConfirmation ? 'text' : 'password' }}" autocomplete="new-password" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-20 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        <button type="button" wire:click="togglePasswordConfirmationVisibility" class="absolute inset-y-0 right-3 inline-flex items-center text-xs font-semibold text-slate-500">
                                            {{ $showPasswordConfirmation ? 'Ocultar' : 'Mostrar' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @if (\Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                <label class="mt-5 flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                                    <input wire:model="terms" type="checkbox" class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span>Acepto los términos y condiciones de uso del sistema.</span>
                                </label>
                                @error('terms') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>
                    </div>
                @endif

                @if ($step === 4)
                    <div class="space-y-6">
                        <div class="text-center">
                            <h2 class="text-2xl font-semibold sm:text-3xl">Paso 4. Revisa y confirma</h2>
                            <p class="mt-2 text-sm text-slate-500">Antes de guardar, revisa que la información corresponde a tu persona.</p>
                        </div>

                        <div class="grid gap-5 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Datos personales</p>
                                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Perfil</p><p class="mt-1 font-medium text-slate-900">{{ $this->inferredProfileName() }}</p></div>
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Identidad</p><p class="mt-1 font-medium text-slate-900">{{ $dni ?: 'Pendiente' }}</p></div>
                                    <div class="sm:col-span-2"><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nombre completo</p><p class="mt-1 font-medium text-slate-900">{{ $this->fullNameForBackend() ?: 'Pendiente' }}</p></div>
                                    @if ($this->showNumeroEmpleado())
                                        <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Número empleado</p><p class="mt-1 font-medium text-slate-900">{{ $numeroEmpleado ?: 'Pendiente' }}</p></div>
                                    @endif
                                    @if ($this->showNumeroCuenta())
                                        <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Número cuenta</p><p class="mt-1 font-medium text-slate-900">{{ $numeroCuenta ?: 'Pendiente' }}</p></div>
                                    @endif
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Sexo</p><p class="mt-1 font-medium text-slate-900">{{ $sexo ?: 'Pendiente' }}</p></div>
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nacimiento</p><p class="mt-1 font-medium text-slate-900">{{ $fechaNacimiento ?: 'Pendiente' }}</p></div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Contacto y acceso</p>
                                    <div class="mt-4 space-y-3">
                                        <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Correo usuario</p><p class="mt-1 font-medium text-slate-900">{{ $email ?: 'Pendiente' }}</p></div>
                                        <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Teléfono</p><p class="mt-1 font-medium text-slate-900">{{ $telefono ?: 'Pendiente' }}</p></div>
                                        <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nacionalidad</p><p class="mt-1 font-medium text-slate-900">{{ $this->nationalityName() }}</p></div>
                                        <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Dirección</p><p class="mt-1 text-sm leading-6 text-slate-700">{{ $direccion ?: 'Pendiente' }}</p></div>
                                    </div>
                                </div>

                                <div class="rounded-[1.5rem] border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-800">
                                    Confirma que la identidad y los datos proporcionados son correctos y te pertenecen. Esta información se usará para inscripciones, pagos y certificados.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-slate-500">Paso {{ $step }} de 4</div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        @if ($step > 1)
                            <button type="button" wire:click="previousStep" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Paso anterior
                            </button>
                        @endif

                        <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Cancelar
                        </a>

                        @if ($step < 4)
                            <button type="button" wire:click="nextStep" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                                Siguiente paso
                            </button>
                        @else
                            <button type="button" wire:click="openConfirmationModal" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                                Guardar cuenta
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </section>
    </div>

    @if ($showConfirmationModal)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 p-4 backdrop-blur-sm sm:items-center">
            <div class="w-full max-w-xl overflow-hidden rounded-[2rem] bg-white shadow-[0_30px_90px_-30px_rgba(15,23,42,0.55)]">
                <div class="border-b border-slate-200 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-500">Confirmación</p>
                    <h3 class="mt-2 text-2xl font-semibold text-slate-950">Confirmar creación de cuenta</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Antes de guardar, confirma que la identidad y datos proporcionados son correctos y no pertenecen a otra persona.
                    </p>
                </div>

                <div class="space-y-4 p-6">
                    <div class="rounded-[1.5rem] border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                        Al confirmar se creará tu usuario participante con el perfil <strong>{{ $this->inferredProfileName() }}</strong> y serás enviado al inicio de sesión.
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                        <p><strong>Identidad:</strong> {{ $dni }}</p>
                        <p class="mt-1"><strong>Nombre:</strong> {{ $this->fullNameForBackend() }}</p>
                        <p class="mt-1"><strong>Correo:</strong> {{ $email }}</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 p-6 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="closeConfirmationModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Revisar datos
                    </button>
                    <button type="button" wire:click="register" wire:loading.attr="disabled" wire:target="register" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-wait disabled:opacity-70">
                        <span wire:loading.remove wire:target="register">Confirmar y guardar</span>
                        <span wire:loading wire:target="register" class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                            </svg>
                            Guardando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
