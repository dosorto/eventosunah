<div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(123,92,255,0.12),_transparent_30%),linear-gradient(180deg,#f8fafc_0%,#eef6ff_100%)] px-4 py-6 text-slate-900 sm:px-6 sm:py-8">
    <div class="mx-auto max-w-5xl space-y-5">
        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.28)] sm:p-7">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-violet-500">Portal del staff</p>
                    <h1 class="text-2xl font-semibold sm:text-3xl">{{ $evento->nombreevento }}</h1>
                    <p class="max-w-2xl text-sm leading-6 text-slate-600">
                        @if ($isSubmitted)
                            Este enlace ya fue completado. Solo se muestra el resumen final del miembro del equipo organizador.
                        @else
                            Completa tu perfil para quedar habilitado como miembro del staff del evento.
                        @endif
                    </p>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Estado</p>
                    <p class="mt-2 text-sm font-semibold {{ $isSubmitted ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $isSubmitted ? 'Perfil completado' : 'Pendiente de completar' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] sm:p-7">
            <div class="overflow-x-auto pb-2">
                <div class="mx-auto flex min-w-[34rem] items-start justify-between gap-4">
                    @foreach ($stepDefinitions as $wizardStep)
                        @php
                            $number = $wizardStep['number'];
                            $isCurrent = $step === $number && ! $isSubmitted;
                            $isDone = $isSubmitted || $number < $step;
                        @endphp
                        <div class="flex flex-1 items-start gap-4">
                            <button
                                type="button"
                                wire:click="goToStep({{ $number }})"
                                @disabled($isSubmitted)
                                class="flex min-w-[5.5rem] flex-col items-center text-center"
                            >
                                <span class="flex h-12 w-12 items-center justify-center rounded-full border-2 text-sm font-bold transition {{ $isDone ? 'border-emerald-500 bg-emerald-500 text-white' : ($isCurrent ? 'border-violet-500 bg-white text-violet-600 shadow-[0_0_0_6px_rgba(123,92,255,0.12)]' : 'border-slate-300 bg-slate-100 text-slate-400') }}">
                                    @if ($isDone)
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
                                <div class="mt-5 h-1 flex-1 rounded-full {{ $isSubmitted || $number < $step ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        @if (session()->has('message'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('message') }}
            </div>
        @endif

        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] sm:p-7">
            @if ($isSubmitted)
                <div class="space-y-6">
                    <div class="text-center">
                        <h2 class="text-2xl font-semibold">Resumen del staff</h2>
                        <p class="mt-2 text-sm text-slate-500">Tu acceso ya quedo habilitado para este evento.</p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Datos personales</p>
                            <div class="mt-4 space-y-3 text-sm text-slate-700">
                                <p><span class="font-semibold text-slate-900">Nombre:</span> {{ trim($nombre . ' ' . $apellido) }}</p>
                                <p><span class="font-semibold text-slate-900">Correo:</span> {{ $correo }}</p>
                                <p><span class="font-semibold text-slate-900">Telefono:</span> {{ $telefono }}</p>
                                <p><span class="font-semibold text-slate-900">DNI:</span> {{ $dni }}</p>
                            </div>
                        </div>
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Perfil</p>
                            <div class="mt-4 space-y-3 text-sm text-slate-700">
                                <p><span class="font-semibold text-slate-900">Tipo:</span> {{ optional($tiposPerfil->firstWhere('id', (int) $IdTipoPerfil))->tipoperfil ?: 'Pendiente' }}</p>
                                <p><span class="font-semibold text-slate-900">Nacionalidad:</span> {{ optional($nacionalidades->firstWhere('id', (int) $IdNacionalidad))->nombreNacionalidad ?: 'Pendiente' }}</p>
                                <p><span class="font-semibold text-slate-900">Direccion:</span> {{ $direccion ?: 'Pendiente' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="openSubmitConfirmation" class="space-y-7">
                    @if ($step === 1)
                        <div wire:key="staff-step-1" class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold">Datos base</h2>
                                <p class="mt-2 text-sm text-slate-500">Completa tu información principal. Los campos obligatorios llevan <span class="text-red-500">*</span>.</p>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="staff_nombre" class="mb-2 block text-sm font-medium">Nombres <span class="text-red-500">*</span></label>
                                    <input id="staff_nombre" name="staff_nombre" type="text" autocomplete="given-name" wire:model.blur="nombre" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                    @error('nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="staff_apellido" class="mb-2 block text-sm font-medium">Apellidos <span class="text-red-500">*</span></label>
                                    <input id="staff_apellido" name="staff_apellido" type="text" autocomplete="family-name" wire:model.blur="apellido" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                    @error('apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="staff_correo" class="mb-2 block text-sm font-medium">Correo <span class="text-red-500">*</span></label>
                                    <input id="staff_correo" name="staff_correo" type="email" autocomplete="email" wire:model.blur="correo" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                    @error('correo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="staff_telefono" class="mb-2 block text-sm font-medium">Telefono <span class="text-red-500">*</span></label>
                                    <input id="staff_telefono" name="staff_telefono" type="text" autocomplete="tel" wire:model.blur="telefono" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                    @error('telefono') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    @elseif ($step === 2)
                        <div wire:key="staff-step-2" class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold">Perfil</h2>
                                <p class="mt-2 text-sm text-slate-500">Define los datos personales necesarios para habilitar tu acceso al sistema.</p>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                <div>
                                    <label for="staff_dni" class="mb-2 block text-sm font-medium">DNI <span class="text-red-500">*</span></label>
                                    <input id="staff_dni" name="staff_dni" type="text" autocomplete="off" wire:model.blur="dni" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                    @error('dni') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="staff_fecha_nacimiento" class="mb-2 block text-sm font-medium">Fecha de nacimiento</label>
                                    <input id="staff_fecha_nacimiento" name="staff_fecha_nacimiento" type="date" autocomplete="bday" wire:model.blur="fechaNacimiento" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                    @error('fechaNacimiento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="staff_sexo" class="mb-2 block text-sm font-medium">Sexo <span class="text-red-500">*</span></label>
                                    <select id="staff_sexo" name="staff_sexo" wire:model.change="sexo" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                        <option value="">Selecciona una opcion</option>
                                        <option value="Femenino">Femenino</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                    @error('sexo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="staff_nacionalidad" class="mb-2 block text-sm font-medium">Nacionalidad <span class="text-red-500">*</span></label>
                                    <select id="staff_nacionalidad" name="staff_nacionalidad" wire:model.change="IdNacionalidad" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                        <option value="">Selecciona una nacionalidad</option>
                                        @foreach ($nacionalidades as $nacionalidad)
                                            <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdNacionalidad') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="staff_tipo_perfil" class="mb-2 block text-sm font-medium">Tipo de perfil <span class="text-red-500">*</span></label>
                                    <select id="staff_tipo_perfil" name="staff_tipo_perfil" wire:model.change="IdTipoPerfil" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                        <option value="">Selecciona un tipo</option>
                                        @foreach ($tiposPerfil as $tipoPerfil)
                                            <option value="{{ $tipoPerfil->id }}">{{ $tipoPerfil->tipoperfil }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdTipoPerfil') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="lg:col-span-3">
                                    <label for="staff_direccion" class="mb-2 block text-sm font-medium">Direccion <span class="text-red-500">*</span></label>
                                    <textarea id="staff_direccion" name="staff_direccion" autocomplete="street-address" wire:model.blur="direccion" rows="3" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100"></textarea>
                                    @error('direccion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    @else
                        <div wire:key="staff-step-3" class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold">Resumen y envío</h2>
                                <p class="mt-2 text-sm text-slate-500">Revisa la información antes de enviarla.</p>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Datos personales</p>
                                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                                        <p><span class="font-semibold text-slate-900">Nombre:</span> {{ trim($nombre . ' ' . $apellido) }}</p>
                                        <p><span class="font-semibold text-slate-900">Correo:</span> {{ $correo }}</p>
                                        <p><span class="font-semibold text-slate-900">Telefono:</span> {{ $telefono }}</p>
                                        <p><span class="font-semibold text-slate-900">DNI:</span> {{ $dni }}</p>
                                    </div>
                                </div>
                                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Perfil</p>
                                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                                        <p><span class="font-semibold text-slate-900">Tipo:</span> {{ optional($tiposPerfil->firstWhere('id', (int) $IdTipoPerfil))->tipoperfil ?: 'Pendiente' }}</p>
                                        <p><span class="font-semibold text-slate-900">Nacionalidad:</span> {{ optional($nacionalidades->firstWhere('id', (int) $IdNacionalidad))->nombreNacionalidad ?: 'Pendiente' }}</p>
                                        <p><span class="font-semibold text-slate-900">Direccion:</span> {{ $direccion ?: 'Pendiente' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($requiresAccount)
                                <div class="rounded-[1.5rem] border border-violet-200 bg-violet-50 p-5">
                                    <h3 class="text-lg font-semibold text-slate-900">Credenciales de acceso</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">Como aún no tienes usuario, define tu contraseña para entrar al sistema con el correo <span class="font-semibold text-slate-900">{{ $correo }}</span>.</p>
                                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium">Contrasena <span class="text-red-500">*</span></label>
                                            <input type="password" wire:model.blur="account_password" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                            @error('account_password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium">Confirmar contrasena <span class="text-red-500">*</span></label>
                                            <input type="password" wire:model.blur="account_password_confirmation" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="flex flex-col gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-slate-500">Paso {{ $step }} de 3</div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            @if ($step > 1)
                                <button type="button" wire:click="previousStep" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                    Paso anterior
                                </button>
                            @endif

                            @if ($step < 3)
                                <button type="button" wire:click="nextStep" class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                                    Siguiente paso
                                </button>
                            @else
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                                    Enviar informacion
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </section>
    </div>

    @if ($showSubmitConfirmation)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeSubmitConfirmation"></div>
            <div class="relative w-full max-w-lg rounded-[2rem] border border-slate-200 bg-white p-6 shadow-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-500">Confirmacion</p>
                <h3 class="mt-3 text-2xl font-semibold text-slate-900">Enviar informacion del staff</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">Al confirmar, tu perfil quedará asociado al equipo organizador del evento y se habilitará tu acceso al sistema con el rol de staff.</p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="closeSubmitConfirmation" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="submitFinal" class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700">
                        Confirmar y enviar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
