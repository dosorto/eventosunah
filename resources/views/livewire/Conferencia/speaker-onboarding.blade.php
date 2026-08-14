<div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.12),_transparent_30%),linear-gradient(180deg,#f8fafc_0%,#eef6ff_100%)] px-4 py-6 text-slate-900 sm:px-6 sm:py-8">
    <div class="mx-auto max-w-5xl space-y-5">
        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.28)] sm:p-7">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-500">Portal del conferencista</p>
                    <h1 class="text-2xl font-semibold sm:text-3xl">{{ $conference_nombre ?: $conferencia->nombre }}</h1>
                    <p class="max-w-2xl text-sm leading-6 text-slate-600">
                        @if ($isSubmitted)
                            Este enlace ya fue enviado. Solo se muestra el resumen final registrado para esta conferencia.
                        @else
                            Completa el wizard paso a paso. Tu avance se guarda automaticamente para que puedas continuar luego desde donde te quedaste.
                        @endif
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[22rem]">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Evento</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $evento->nombreevento }}</p>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ optional($evento->fechainicio)->format('d/m/Y') }}
                            @if ($evento->fechafinal && $evento->fechafinal->format('Y-m-d') !== optional($evento->fechainicio)->format('Y-m-d'))
                                - {{ $evento->fechafinal->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Estado</p>
                        <p class="mt-2 text-sm font-semibold {{ $isSubmitted ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $isSubmitted ? 'Enviado' : 'Borrador en progreso' }}
                        </p>
                        @if ($draftSavedAt && ! $isSubmitted)
                            <p class="mt-1 text-xs text-slate-500">Ultimo guardado: {{ $draftSavedAt }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] sm:p-7">
            <div class="overflow-x-auto pb-2">
                <div class="mx-auto flex min-w-[44rem] items-start justify-between gap-4">
                    @foreach ($stepDefinitions as $index => $wizardStep)
                        @php
                            $number = $wizardStep['number'];
                            $isCurrent = $step === $number && ! $isSubmitted;
                            $isDone = $isSubmitted || $number < $step;
                            $isUpcoming = ! $isDone && ! $isCurrent;
                        @endphp

                        <div class="flex flex-1 items-start gap-4">
                            <button
                                type="button"
                                wire:click="goToStep({{ $number }})"
                                @disabled($isSubmitted)
                                class="flex min-w-[5.5rem] flex-col items-center text-center {{ $isSubmitted ? 'cursor-default' : '' }}"
                            >
                                <span class="flex h-12 w-12 items-center justify-center rounded-full border-2 text-sm font-bold transition {{ $isDone ? 'border-emerald-500 bg-emerald-500 text-white' : ($isCurrent ? 'border-emerald-500 bg-white text-emerald-600 shadow-[0_0_0_6px_rgba(16,185,129,0.12)]' : 'border-slate-300 bg-slate-100 text-slate-400') }}">
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

        @if (session()->has('warning'))
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                {{ session('warning') }}
            </div>
        @endif

        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] sm:p-7">
            @if ($isSubmitted)
                <div class="space-y-6">
                    <div class="overflow-hidden rounded-[1.75rem] border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-sky-50 p-6 text-center shadow-[0_20px_60px_-36px_rgba(16,185,129,0.55)]">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg shadow-emerald-200">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 12.5l4 4L19 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.28em] text-emerald-700">Enviado correctamente</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-950">Informacion recibida</h2>
                        <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-slate-600">Tu actualizacion fue registrada. Este enlace ahora solo muestra el resumen final; para editar los datos debes solicitar un nuevo enlace al administrador.</p>
                        <a href="{{ route('welcome') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-600">
                            Ir a la pagina principal
                        </a>
                    </div>

                    <div class="grid gap-5 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
                        <div class="space-y-4">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Datos personales</p>
                                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nombre</p><p class="mt-1 font-medium text-slate-900">{{ $this->speakerFullName() ?: 'Pendiente' }}</p></div>
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">DNI</p><p class="mt-1 font-medium text-slate-900">{{ $dni ?: 'Pendiente' }}</p></div>
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Correo</p><p class="mt-1 font-medium text-slate-900">{{ $correo ?: 'Pendiente' }}</p></div>
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Tipo de perfil</p><p class="mt-1 font-medium text-slate-900">{{ $this->profileTypeName() ?: 'Pendiente' }}</p></div>
                                    @if ($this->requiresProfileNumber())
                                        <div class="sm:col-span-2"><p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $this->profileNumberLabel() }}</p><p class="mt-1 font-medium text-slate-900">{{ $numeroCuenta ?: 'Pendiente' }}</p></div>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Perfil profesional</p>
                                <div class="mt-4 space-y-3">
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nivel academico</p><p class="mt-1 font-medium text-slate-900">{{ $nivel_academico ?: 'Pendiente' }}</p></div>
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Resumen</p><p class="mt-1 text-sm leading-6 text-slate-700">{{ $descripcion_perfil ?: 'Pendiente' }}</p></div>
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Conferencia</p>
                                <div class="mt-4 space-y-3">
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nombre</p><p class="mt-1 font-medium text-slate-900">{{ $conference_nombre ?: 'Pendiente' }}</p></div>
                                    <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Descripcion</p><p class="mt-1 text-sm leading-6 text-slate-700">{{ $conference_descripcion ?: 'Pendiente' }}</p></div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Foto del conferencista</p>
                                <div class="mt-4 flex items-center justify-center rounded-[1.25rem] border border-dashed border-slate-300 bg-white p-4">
                                    <div class="aspect-square w-full max-w-[18rem] overflow-hidden rounded-[1rem] border border-slate-200 bg-slate-100">
                                        @if ($currentSpeakerPhoto)
                                            <img src="{{ \Illuminate\Support\Str::startsWith($currentSpeakerPhoto, ['http://', 'https://']) ? $currentSpeakerPhoto : asset($currentSpeakerPhoto) }}" alt="Foto del conferencista" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center px-4 text-center text-sm text-slate-400">Sin foto cargada</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Imagen de la conferencia</p>
                                <div class="mt-4 flex items-center justify-center rounded-[1.25rem] border border-dashed border-slate-300 bg-white p-4">
                                    <div class="aspect-square w-full max-w-[18rem] overflow-hidden rounded-[1rem] border border-slate-200 bg-slate-100">
                                        @if ($currentConferencePhoto)
                                            <img src="{{ \Illuminate\Support\Str::startsWith($currentConferencePhoto, ['http://', 'https://']) ? $currentConferencePhoto : asset($currentConferencePhoto) }}" alt="Imagen de la conferencia" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center px-4 text-center text-sm text-slate-400">Sin imagen cargada</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="openSubmitConfirmation" class="space-y-7">
                    @if ($step === 1)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold">Datos personales</h2>
                                <p class="mt-2 text-sm text-slate-500">Completa tu informacion base. Todos los campos obligatorios llevan <span class="text-red-500">*</span>.</p>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Primer nombre <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.blur="primer_nombre" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('primer_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Segundo nombre</label>
                                    <input type="text" wire:model.blur="segundo_nombre" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('segundo_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Primer apellido <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.blur="primer_apellido" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('primer_apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Segundo apellido</label>
                                    <input type="text" wire:model.blur="segundo_apellido" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('segundo_apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">DNI <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.blur="dni" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('dni') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Correo personal <span class="text-red-500">*</span></label>
                                    <input type="email" wire:model.blur="correo" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('correo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Correo institucional</label>
                                    <input type="email" wire:model.blur="correoInstitucional" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('correoInstitucional') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Telefono <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.blur="telefono" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('telefono') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Fecha de nacimiento</label>
                                    <input type="date" wire:model.blur="fechaNacimiento" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('fechaNacimiento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Sexo <span class="text-red-500">*</span></label>
                                    <select wire:model.change="sexo" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        <option value="">Selecciona una opcion</option>
                                        <option value="Femenino">Femenino</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                    @error('sexo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Nacionalidad <span class="text-red-500">*</span></label>
                                    <select wire:model.change="IdNacionalidad" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        <option value="">Selecciona una nacionalidad</option>
                                        @foreach ($nacionalidades as $nacionalidad)
                                            <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdNacionalidad') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Tipo de perfil <span class="text-red-500">*</span></label>
                                    <select wire:model.change="IdTipoPerfil" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        <option value="">Selecciona un tipo</option>
                                        @foreach ($tiposPerfil as $tipoPerfil)
                                            <option value="{{ $tipoPerfil->id }}">{{ $tipoPerfil->tipoperfil }}</option>
                                        @endforeach
                                    </select>
                                    @error('IdTipoPerfil') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                @if ($this->requiresProfileNumber())
                                    <div>
                                        <label class="mb-2 block text-sm font-medium">{{ $this->profileNumberLabel() }} <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.blur="numeroCuenta" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        @if ($this->profileNumberHelp())
                                            <p class="mt-2 text-xs text-slate-500">{{ $this->profileNumberHelp() }}</p>
                                        @endif
                                        @error('numeroCuenta') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                @endif

                                <div class="md:col-span-2 lg:col-span-3">
                                    <label class="mb-2 block text-sm font-medium">Direccion <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.blur="direccion" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                    @error('direccion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($step === 2)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold">Perfil profesional</h2>
                                <p class="mt-2 text-sm text-slate-500">Sube tu foto y completa el perfil profesional. La vista previa muestra el espacio real de 3 x 3 pulgadas.</p>
                            </div>

                            <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_17rem]">
                                <div class="space-y-4">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium">Nivel maximo academico alcanzado <span class="text-red-500">*</span></label>
                                        <select wire:model.change="nivel_academico" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                            <option value="">Selecciona una opcion</option>
                                            @foreach ($academicLevels as $academicLevel)
                                                <option value="{{ $academicLevel }}">{{ $academicLevel }}</option>
                                            @endforeach
                                        </select>
                                        @error('nivel_academico') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium">Breve perfil profesional <span class="text-red-500">*</span></label>
                                        <textarea rows="6" wire:model.blur="descripcion_perfil" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"></textarea>
                                        @error('descripcion_perfil') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium">Foto del conferencista <span class="text-red-500">*</span></label>
                                        <label class="relative flex min-h-[14rem] cursor-pointer flex-col items-center justify-center rounded-[1.75rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-emerald-400 hover:bg-emerald-50/40">
                                            <input type="file" wire:model.live="speaker_photo" accept=".jpg,.jpeg,.png,image/png,image/jpeg" class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                                            <span class="rounded-full bg-white p-4 shadow-sm">
                                                <svg class="h-8 w-8 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0l-4 4m4-4l4 4M5 15v3a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3"/>
                                                </svg>
                                            </span>
                                            <p class="mt-4 text-base font-semibold text-slate-900">Arrastra o selecciona la foto</p>
                                            <p class="mt-2 text-sm text-slate-500">PNG o JPG, preferiblemente cuadrada.</p>
                                        </label>
                                        @error('speaker_photo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Vista 3 x 3 pulgadas</p>
                                    <div class="mt-4 flex items-center justify-center rounded-[1.25rem] border border-dashed border-slate-300 bg-white p-3">
                                        <div class="aspect-square w-full max-w-[18rem] overflow-hidden rounded-[1rem] border border-slate-200 bg-slate-100">
                                            @if ($speaker_photo)
                                                <img src="{{ $speaker_photo->temporaryUrl() }}" alt="Vista previa de foto del conferencista" class="h-full w-full object-cover">
                                            @elseif ($currentSpeakerPhoto)
                                                <img src="{{ \Illuminate\Support\Str::startsWith($currentSpeakerPhoto, ['http://', 'https://']) ? $currentSpeakerPhoto : asset($currentSpeakerPhoto) }}" alt="Foto actual del conferencista" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center px-4 text-center text-sm text-slate-400">Sin foto cargada</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($step === 3)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold">Datos de la conferencia</h2>
                                <p class="mt-2 text-sm text-slate-500">Configura el nombre definitivo, descripcion e imagen principal de la conferencia.</p>
                            </div>

                            <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_17rem]">
                                <div class="space-y-4">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium">Nombre de la conferencia <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.blur="conference_nombre" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                                        @error('conference_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium">Descripcion de la conferencia <span class="text-red-500">*</span></label>
                                        <textarea rows="7" wire:model.blur="conference_descripcion" class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"></textarea>
                                        @error('conference_descripcion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium">Imagen de la conferencia <span class="text-red-500">*</span></label>
                                        <label class="relative flex min-h-[14rem] cursor-pointer flex-col items-center justify-center rounded-[1.75rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-emerald-400 hover:bg-emerald-50/40">
                                            <input type="file" wire:model.live="conference_photo" accept=".jpg,.jpeg,.png,image/png,image/jpeg" class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                                            <span class="rounded-full bg-white p-4 shadow-sm">
                                                <svg class="h-8 w-8 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0l-4 4m4-4l4 4M5 15v3a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3"/>
                                                </svg>
                                            </span>
                                            <p class="mt-4 text-base font-semibold text-slate-900">Arrastra o selecciona la imagen</p>
                                            <p class="mt-2 text-sm text-slate-500">PNG o JPG con buena resolucion.</p>
                                        </label>
                                        @error('conference_photo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Vista 3 x 3 pulgadas</p>
                                    <div class="mt-4 flex items-center justify-center rounded-[1.25rem] border border-dashed border-slate-300 bg-white p-3">
                                        <div class="aspect-square w-full max-w-[18rem] overflow-hidden rounded-[1rem] border border-slate-200 bg-slate-100">
                                            @if ($conference_photo)
                                                <img src="{{ $conference_photo->temporaryUrl() }}" alt="Vista previa de imagen de conferencia" class="h-full w-full object-cover">
                                            @elseif ($currentConferencePhoto)
                                                <img src="{{ \Illuminate\Support\Str::startsWith($currentConferencePhoto, ['http://', 'https://']) ? $currentConferencePhoto : asset($currentConferencePhoto) }}" alt="Imagen actual de la conferencia" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center px-4 text-center text-sm text-slate-400">Sin imagen cargada</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($step === 4)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-semibold">Resumen final</h2>
                                <p class="mt-2 text-sm text-slate-500">Revisa cuidadosamente toda la informacion antes de enviarla.</p>
                            </div>

                            <div class="grid gap-5 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)]">
                                <div class="space-y-4">
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Datos personales</p>
                                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nombre</p><p class="mt-1 font-medium text-slate-900">{{ $this->speakerFullName() ?: 'Pendiente' }}</p></div>
                                            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">DNI</p><p class="mt-1 font-medium text-slate-900">{{ $dni ?: 'Pendiente' }}</p></div>
                                            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Correo</p><p class="mt-1 font-medium text-slate-900">{{ $correo ?: 'Pendiente' }}</p></div>
                                            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Tipo de perfil</p><p class="mt-1 font-medium text-slate-900">{{ $this->profileTypeName() ?: 'Pendiente' }}</p></div>
                                        </div>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Perfil profesional</p>
                                        <div class="mt-4 space-y-3">
                                            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nivel academico</p><p class="mt-1 font-medium text-slate-900">{{ $nivel_academico ?: 'Pendiente' }}</p></div>
                                            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Resumen</p><p class="mt-1 text-sm leading-6 text-slate-700">{{ $descripcion_perfil ?: 'Pendiente' }}</p></div>
                                        </div>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Conferencia</p>
                                        <div class="mt-4 space-y-3">
                                            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Nombre</p><p class="mt-1 font-medium text-slate-900">{{ $conference_nombre ?: 'Pendiente' }}</p></div>
                                            <div><p class="text-xs uppercase tracking-[0.18em] text-slate-500">Descripcion</p><p class="mt-1 text-sm leading-6 text-slate-700">{{ $conference_descripcion ?: 'Pendiente' }}</p></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Foto del conferencista</p>
                                        <div class="mt-4 flex items-center justify-center rounded-[1.25rem] border border-dashed border-slate-300 bg-white p-4">
                                            <div class="aspect-square w-full max-w-[18rem] overflow-hidden rounded-[1rem] border border-slate-200 bg-slate-100">
                                                @if ($speaker_photo)
                                                    <img src="{{ $speaker_photo->temporaryUrl() }}" alt="Foto del conferencista" class="h-full w-full object-cover">
                                                @elseif ($currentSpeakerPhoto)
                                                    <img src="{{ \Illuminate\Support\Str::startsWith($currentSpeakerPhoto, ['http://', 'https://']) ? $currentSpeakerPhoto : asset($currentSpeakerPhoto) }}" alt="Foto actual del conferencista" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center px-4 text-center text-sm text-slate-400">Sin foto cargada</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Imagen de la conferencia</p>
                                        <div class="mt-4 flex items-center justify-center rounded-[1.25rem] border border-dashed border-slate-300 bg-white p-4">
                                            <div class="aspect-square w-full max-w-[18rem] overflow-hidden rounded-[1rem] border border-slate-200 bg-slate-100">
                                                @if ($conference_photo)
                                                    <img src="{{ $conference_photo->temporaryUrl() }}" alt="Imagen de la conferencia" class="h-full w-full object-cover">
                                                @elseif ($currentConferencePhoto)
                                                    <img src="{{ \Illuminate\Support\Str::startsWith($currentConferencePhoto, ['http://', 'https://']) ? $currentConferencePhoto : asset($currentConferencePhoto) }}" alt="Imagen actual de la conferencia" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center px-4 text-center text-sm text-slate-400">Sin imagen cargada</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-slate-500">Paso {{ $step }} de 4</div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            @if ($step > 1)
                                <button type="button" wire:click="previousStep" class="inline-flex items-center justify-center rounded-2xl border border-emerald-500 bg-white px-5 py-3 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-50">
                                    Paso anterior
                                </button>
                            @endif

                            @if ($step < 4)
                                <button type="button" wire:click="nextStep" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                                    Siguiente paso
                                </button>
                            @else
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                                    Enviar
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </section>
    </div>

    @if ($showSubmitConfirmation)
        <div class="fixed inset-0 z-[90] flex items-end justify-center bg-slate-950/50 px-4 py-6 sm:items-center">
            <div class="w-full max-w-lg rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-500">Confirmacion final</p>
                <h3 class="mt-3 text-2xl font-semibold text-slate-900">Enviar informacion</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Una vez enviada la informacion no podras editarla con este enlace. Si luego necesitas cambios, deberas solicitar un nuevo enlace al administrador del sistema.
                </p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="closeSubmitConfirmation" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Cancelar
                    </button>
                    <button type="button" wire:click="submitFinal" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                        Confirmar y enviar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
