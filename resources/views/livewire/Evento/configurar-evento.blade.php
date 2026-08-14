<div class="space-y-5">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-200 px-5 py-5 dark:border-slate-800">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-4xl">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-yellow-500">Formulación del evento</p>
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">{{ $evento->nombreevento }}</h1>
                        <span class="inline-flex rounded-full px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.2em] {{ $evento->estado === 'publicado' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' }}">
                            {{ $evento->estado === 'publicado' ? 'Publicado' : 'Formulacion' }}
                        </span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-600 dark:text-slate-300">
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Tipo:</span> {{ $evento->tipoEvento?->tipo ?? 'Sin tipo' }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Organiza:</span> {{ $evento->organizador }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Modalidad:</span> {{ $evento->modalidad?->modalidad ?? 'Sin modalidad' }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Localidad:</span> {{ $evento->localidad_display }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Agenda:</span> {{ $evento->conferencias->count() }} conferencias/talleres</p>
                    </div>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $evento->descripcion }}</p>
                </div>

                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                    <button type="button" wire:click="editPrincipal" class="inline-flex items-center justify-center rounded-2xl border border-yellow-300 px-4 py-2.5 text-sm font-medium text-yellow-700 transition hover:bg-yellow-50 dark:border-yellow-800 dark:text-yellow-300 dark:hover:bg-yellow-950/30">
                        {{ $editingPrincipal ? 'Cerrar edicion' : 'Editar evento' }}
                    </button>
                    <a href="{{ route('eventos') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Volver al listado
                    </a>
                </div>
            </div>
        </div>

        <div class="px-5 py-5">
            @if (session()->has('message'))
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @if ($editingPrincipal)
                <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
                    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closePrincipalModal"></div>

                    <div class="relative max-h-full w-full max-w-4xl overflow-y-auto rounded-3xl border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Editar evento</h2>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Actualiza la configuración base del evento desde una sola ventana.</p>
                                </div>

                                <button type="button" wire:click="closePrincipalModal" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                    <span class="sr-only">Cerrar</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <form wire:submit.prevent="savePrincipal" class="grid gap-5 px-6 py-6">
                            <div>
                                <label for="nombreevento" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre <span class="text-red-500">*</span></label>
                                <input id="nombreevento" type="text" wire:model.live="nombreevento" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                @error('nombreevento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="descripcion" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Descripcion <span class="text-red-500">*</span></label>
                                <textarea id="descripcion" rows="4" wire:model.live="descripcion" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"></textarea>
                                @error('descripcion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid gap-4 lg:grid-cols-2">
                                <div>
                                    <label for="organizador" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Organizador <span class="text-red-500">*</span></label>
                                    <input id="organizador" type="text" wire:model.live="organizador" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                    @error('organizador') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="tipo_conferencia_id" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tipo de evento <span class="text-red-500">*</span></label>
                                    <select id="tipo_conferencia_id" wire:model.live="tipo_conferencia_id" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                        <option value="">Selecciona un tipo</option>
                                        @foreach ($tiposConferencias as $tipoConferencia)
                                            <option value="{{ $tipoConferencia->id }}">{{ $tipoConferencia->tipo }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_conferencia_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 lg:grid-cols-2">
                                <div>
                                    <label for="idmodalidad" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Modalidad <span class="text-red-500">*</span></label>
                                    <select id="idmodalidad" wire:model.live="idmodalidad" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                        <option value="">Selecciona una modalidad</option>
                                        @foreach ($modalidades as $modalidad)
                                            <option value="{{ $modalidad->id }}">{{ $modalidad->modalidad }}</option>
                                        @endforeach
                                    </select>
                                    @error('idmodalidad') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 lg:grid-cols-2">
                                <div>
                                    <label for="tipo_acceso" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tipo de acceso <span class="text-red-500">*</span></label>
                                    <select id="tipo_acceso" wire:model.live="tipo_acceso" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                        <option value="gratuita">Gratuita</option>
                                        <option value="pagada">Pagada</option>
                                    </select>
                                    @error('tipo_acceso') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="genera_diploma_participacion" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Genera diploma de participación <span class="text-red-500">*</span></label>
                                    <select id="genera_diploma_participacion" wire:model.live="genera_diploma_participacion" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                    @error('genera_diploma_participacion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="localidad_nombre" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Localidad <span class="text-red-500">*</span></label>
                                <input id="localidad_nombre" type="text" wire:model.live="localidad_nombre" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                @error('localidad_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="fechainicio" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Fecha de inicio</label>
                                    <input id="fechainicio" type="date" wire:model.live="fechainicio" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                    @error('fechainicio') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="fechafinal" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Fecha final</label>
                                    <input id="fechafinal" type="date" wire:model.live="fechafinal" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                    @error('fechafinal') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end dark:border-slate-800">
                                <button type="button" wire:click="closePrincipalModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                    Cancelar
                                </button>
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400">
                                    <span wire:loading.remove wire:target="savePrincipal">Guardar cambios</span>
                                    <span wire:loading wire:target="savePrincipal">Guardando...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="space-y-5">
                <section class="rounded-[2rem] border border-slate-200 bg-white p-4 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.24)] dark:border-slate-800 dark:bg-slate-950/50 sm:p-6">
                    <div class="overflow-x-auto pb-2">
                        <div class="mx-auto flex min-w-[44rem] items-start justify-between gap-4">
                            @foreach ($sectionStatus as $index => $section)
                                @php
                                    $number = $index + 1;
                                    $isCurrent = $activeSection === $section['key'];
                                    $isDone = $section['ready'];
                                    $isAccessible = $isDone || $isCurrent;
                                @endphp

                                <div class="flex flex-1 items-start gap-4">
                                    <button
                                        type="button"
                                        wire:click="setActiveSection('{{ $section['key'] }}')"
                                        class="flex min-w-[5.5rem] flex-col items-center text-center"
                                    >
                                        <span class="flex h-11 w-11 items-center justify-center rounded-full border-2 text-sm font-bold transition {{ $isDone ? 'border-emerald-500 bg-emerald-500 text-white' : ($isCurrent ? 'border-emerald-500 bg-white text-emerald-600 shadow-[0_0_0_6px_rgba(16,185,129,0.12)]' : 'border-slate-300 bg-slate-100 text-slate-400') }}">
                                            @if ($isDone)
                                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                                                </svg>
                                            @else
                                                {{ $number }}
                                            @endif
                                        </span>
                                        <span class="mt-3 text-[11px] font-semibold uppercase tracking-[0.18em] {{ $isAccessible ? 'text-slate-900 dark:text-slate-100' : 'text-slate-400 dark:text-slate-500' }}">Paso {{ $number }}</span>
                                        <span class="mt-1 text-xs {{ $isAccessible ? 'text-slate-500 dark:text-slate-400' : 'text-slate-400 dark:text-slate-500' }}">{{ $section['title'] }}</span>
                                    </button>

                                    @if (! $loop->last)
                                        <div class="mt-5 h-1 flex-1 rounded-full {{ $isDone ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-800' }}"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <div class="rounded-3xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950/50 sm:p-5">
                    @if ($activeSection === 'logo')
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Identidad visual del evento</h2>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Configura el banner promocional que aparecerá en el catálogo público y, si lo deseas, el logo institucional del evento.</p>
                            <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                                El <span class="font-semibold">banner promocional <span class="text-red-500">*</span></span> es obligatorio. Recomendación visual:
                                <span class="font-semibold">1920 x 1080 px</span> o una proporción equivalente 16:9. El <span class="font-semibold">logo del evento</span> es opcional y se recomienda en PNG con fondo transparente.
                            </div>

                            <div class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,2fr)]">
                                @foreach (['logo', 'banner'] as $mediaType)
                                    @php
                                        $isLogo = $mediaType === 'logo';
                                        $mediaReady = $this->mediaIsConfigured($mediaType);
                                        $mediaUrl = $this->mediaPreviewUrl($mediaType);
                                    @endphp
                                    <article class="overflow-hidden rounded-[2rem] border transition {{ $this->mediaStatusClasses($mediaType) }}">
                                        <div class="border-b border-slate-200/80 px-5 py-4 dark:border-slate-800/80">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                                                            {{ $isLogo ? 'Logo del evento' : 'Banner promocional del evento' }}
                                                            @unless($isLogo) <span class="text-red-500">*</span> @endunless
                                                        </h3>
                                                    </div>
                                                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.18em] {{ $this->mediaStatusTextClasses($mediaType) }}">
                                                        {{ $this->mediaStatusText($mediaType) }}
                                                    </p>
                                                </div>

                                                <button type="button" wire:click="openMediaModal('{{ $mediaType }}')" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-yellow-300 hover:text-yellow-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                                    {{ $mediaReady ? 'Editar' : 'Configurar' }}
                                                </button>
                                            </div>
                                        </div>

                                        <div class="p-5">
                                            <div class="flex items-center justify-center rounded-[1.75rem] border border-dashed border-slate-300 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                                @if ($isLogo)
                                                    <div
                                                        class="relative flex items-center justify-center overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50 shadow-sm dark:border-slate-700 dark:bg-slate-950"
                                                        style="width: 3in; height: 3in; max-width: min(100%, 15rem); max-height: min(100vw, 15rem);"
                                                    >
                                                        @if ($mediaUrl)
                                                            <img src="{{ $mediaUrl }}" alt="Logo del evento" class="h-full w-full object-contain p-4">
                                                        @else
                                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 via-white to-yellow-50 p-6 text-center dark:from-slate-900 dark:via-slate-950 dark:to-slate-900">
                                                                <div>
                                                                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Sin logo</p>
                                                                    <p class="mt-2 text-sm font-medium text-slate-700 dark:text-slate-200">Vista sugerida 3 x 3</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="w-full overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50 shadow-sm dark:border-slate-700 dark:bg-slate-950">
                                                        <div class="aspect-[16/9] w-full">
                                                            @if ($mediaUrl)
                                                                <img src="{{ $mediaUrl }}" alt="Banner del evento" class="h-full w-full object-cover">
                                                            @elseif ($evento->logo_url)
                                                                <div class="flex h-full w-full items-center justify-center bg-[linear-gradient(135deg,_#082f49_0%,_#0f172a_48%,_#ca8a04_100%)] p-6">
                                                                    <img src="{{ $evento->logo_url }}" alt="Logo del evento" class="max-h-[72%] max-w-[72%] object-contain">
                                                                </div>
                                                            @else
                                                                <div class="flex h-full w-full items-center justify-center bg-[linear-gradient(135deg,_#082f49_0%,_#0f172a_48%,_#ca8a04_100%)] p-6 text-center text-white">
                                                                    <div>
                                                                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-amber-200">Sin banner</p>
                                                                        <p class="mt-2 text-sm font-medium">{{ $evento->nombreevento }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-medium dark:bg-slate-800">
                                                    {{ $isLogo ? 'PNG transparente recomendado' : '1920 x 1080 recomendado' }}
                                                </span>
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-medium dark:bg-slate-800">
                                                    {{ $isLogo ? 'Opcional' : 'Obligatorio' }}
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($showMediaModal)
                        @php
                            $activeMediaUrl = $this->mediaPreviewUrl($activeMediaType);
                            $mediaUpload = $activeMediaType === 'banner' ? $banner : $logo;
                        @endphp
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
                            <div class="max-h-full w-full max-w-5xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                            {{ $this->mediaTypeTitle() }}@if($activeMediaType === 'banner') <span class="text-red-500">*</span>@endif
                                        </h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $this->mediaTypeDescription() }}</p>
                                    </div>

                                    <button type="button" wire:click="closeMediaModal" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <form wire:submit.prevent="saveLogo" class="grid gap-6 px-6 py-6 lg:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
                                    <div class="space-y-5">
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-300">
                                            {{ $this->mediaHelperText($activeMediaType) }}
                                        </div>

                                        <label for="media-upload" class="group flex cursor-pointer flex-col items-center justify-center rounded-[2rem] border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center transition hover:border-yellow-400 hover:bg-yellow-50/60 dark:border-slate-700 dark:bg-slate-900/60 dark:hover:border-yellow-600 dark:hover:bg-yellow-500/5">
                                            <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm transition group-hover:text-yellow-600 dark:bg-slate-950 dark:text-slate-300">
                                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0-4 4m4-4 4 4" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 16.5a3.5 3.5 0 0 1-3.5 3.5h-9A3.5 3.5 0 0 1 4 16.5" />
                                                </svg>
                                            </span>
                                            <span class="mt-4 text-base font-semibold text-slate-900 dark:text-slate-100">
                                                Arrastra y suelta el {{ $activeMediaType === 'banner' ? 'banner' : 'logo' }} aquí
                                            </span>
                                            <span class="mt-2 text-sm text-slate-500 dark:text-slate-400">O haz clic para buscar el archivo desde tu equipo.</span>
                                            <span class="mt-4 inline-flex rounded-full bg-yellow-500 px-4 py-2 text-sm font-medium text-slate-950 transition group-hover:bg-yellow-400">
                                                {{ $this->mediaFileLabel($activeMediaType) }}
                                            </span>
                                            <input
                                                id="media-upload"
                                                type="file"
                                                wire:model="{{ $activeMediaType }}"
                                                accept="{{ $this->mediaAccept($activeMediaType) }}"
                                                class="sr-only"
                                            >
                                        </label>

                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-300">
                                            @if ($mediaUpload)
                                                Archivo listo para guardar: {{ $mediaUpload->getClientOriginalName() }}
                                            @elseif ($activeMediaUrl)
                                                Archivo actual cargado. Puedes cambiarlo cuando quieras.
                                            @else
                                                Aún no hay archivo cargado para este elemento visual.
                                            @endif
                                        </div>

                                        @error('logo') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                        @error('banner') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="space-y-5">
                                        <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Vista previa</p>
                                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                                        {{ $activeMediaType === 'banner' ? 'Así se verá el banner en la card pública del evento.' : 'Así se verá el logo dentro del área sugerida.' }}
                                                    </p>
                                                </div>
                                                <div class="text-right text-xs text-slate-500 dark:text-slate-400">
                                                    <p>{{ $activeMediaType === 'banner' ? 'Formato panorámico' : 'Caja 3 x 3 pulgadas' }}</p>
                                                </div>
                                            </div>

                                            <div class="mt-5 flex min-h-[22rem] items-center justify-center rounded-[1.75rem] border border-dashed border-slate-300 bg-white p-6 dark:border-slate-700 dark:bg-slate-900">
                                                @if ($activeMediaType === 'banner')
                                                    <div class="w-full overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50 shadow-sm dark:border-slate-700 dark:bg-slate-950">
                                                        <div class="aspect-[16/9] w-full">
                                                            @if ($banner)
                                                                <img src="{{ $banner->temporaryUrl() }}" alt="Vista previa del banner" class="h-full w-full object-cover">
                                                            @elseif ($activeMediaUrl)
                                                                <img src="{{ $activeMediaUrl }}" alt="Banner del evento" class="h-full w-full object-cover">
                                                            @elseif ($evento->logo_url)
                                                                <div class="flex h-full w-full items-center justify-center bg-[linear-gradient(135deg,_#082f49_0%,_#0f172a_48%,_#ca8a04_100%)] p-6">
                                                                    <img src="{{ $evento->logo_url }}" alt="Logo del evento" class="max-h-[72%] max-w-[72%] object-contain">
                                                                </div>
                                                            @else
                                                                <div class="flex h-full w-full items-center justify-center bg-[linear-gradient(135deg,_#082f49_0%,_#0f172a_48%,_#ca8a04_100%)] p-6 text-center text-white">
                                                                    <div>
                                                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-200">Vista genérica</p>
                                                                        <p class="mt-2 text-lg font-medium">{{ $evento->nombreevento }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="relative flex items-center justify-center overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50 shadow-sm dark:border-slate-700 dark:bg-slate-950"
                                                        style="width: 3in; height: 3in; max-width: min(100%, 18rem); max-height: min(100vw, 18rem);"
                                                    >
                                                        @if ($logo)
                                                            <img src="{{ $logo->temporaryUrl() }}" alt="Vista previa del logo" class="h-full w-full object-contain p-4">
                                                        @elseif ($activeMediaUrl)
                                                            <img src="{{ $activeMediaUrl }}" alt="Logo del evento" class="h-full w-full object-contain p-4">
                                                        @else
                                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 via-white to-yellow-50 p-6 text-center dark:from-slate-900 dark:via-slate-950 dark:to-slate-900">
                                                                <div>
                                                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Vista genérica</p>
                                                                    <p class="mt-2 text-lg font-medium text-slate-700 dark:text-slate-200">Logo del evento</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-3 border-t border-slate-200 pt-5 dark:border-slate-800 sm:flex-row sm:justify-end">
                                            <button type="button" wire:click="closeMediaModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400">
                                                <span wire:loading.remove wire:target="saveLogo,logo,banner">Guardar {{ $activeMediaType === 'banner' ? 'banner' : 'logo' }}</span>
                                                <span wire:loading wire:target="saveLogo,logo,banner">Guardando...</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if ($activeSection === 'designs')
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Diseños graficos de evento</h2>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Solo el diploma de participacion general es obligatorio para publicar. El resto de piezas quedan opcionales y puedes configurarlas cuando las necesites.</p>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($designTypes as $designKey => $designLabel)
                                    @php
                                        $designData = $designs[$designKey] ?? null;
                                        $designReady = $this->designConfigured($designKey);
                                        $designUrl = $designData && ! empty($designData['file'])
                                            ? (\Illuminate\Support\Str::startsWith($designData['file'], ['http://', 'https://']) ? $designData['file'] : asset($designData['file']))
                                            : null;
                                        $isPdf = $designUrl && \Illuminate\Support\Str::endsWith(strtolower($designUrl), '.pdf');
                                    @endphp

                                    <article class="overflow-hidden rounded-[2rem] border transition {{ $designReady ? 'border-emerald-200 bg-emerald-50/40 dark:border-emerald-900/50 dark:bg-emerald-950/10' : 'border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/60' }}">
                                        <div class="border-b border-slate-200/80 px-5 py-4 dark:border-slate-800/80">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $designLabel }}@if ($designKey === $requiredDesignKey) <span class="text-red-500">*</span>@endif</h3>
                                                    </div>
                                                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.18em] {{ $designReady ? 'text-emerald-600 dark:text-emerald-300' : 'text-amber-600 dark:text-amber-300' }}">
                                                        {{ $designReady ? 'Configurado' : 'Pendiente' }}
                                                    </p>
                                                </div>

                                                <button type="button" wire:click="openDesignModal('{{ $designKey }}')" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-yellow-300 hover:text-yellow-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                                    {{ $designReady ? 'Editar' : 'Configurar' }}
                                                </button>
                                            </div>
                                        </div>

                                        <div class="p-5">
                                            <div class="flex items-center justify-center rounded-[1.75rem] border border-dashed border-slate-300 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                                <div class="w-full max-w-[15rem]">
                                                    <div class="relative mx-auto overflow-hidden border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950" style="{{ $this->designPreviewStyle($designData['page_size'] ?? 'Carta', $designData['orientation'] ?? 'Vertical') }}">
                                                        @if ($designUrl && ! $isPdf)
                                                            <img src="{{ $designUrl }}" alt="{{ $designLabel }}" class="h-full w-full object-contain bg-white">
                                                        @else
                                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 via-white to-yellow-50 p-6 text-center dark:from-slate-900 dark:via-slate-950 dark:to-slate-900">
                                                                <div>
                                                                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">
                                                                        {{ $isPdf ? 'PDF cargado' : 'Sin diseño' }}
                                                                    </p>
                                                                    <p class="mt-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                                                                        {{ $designData['page_size'] ?? 'Selecciona tamaño' }}
                                                                    </p>
                                                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                                        {{ $designData['orientation'] ?? 'Selecciona orientación' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="pointer-events-none absolute inset-0">
                                                            <div class="absolute rounded-xl border border-white/90 bg-white/90 px-2 py-1 font-semibold text-slate-900 shadow-sm dark:border-slate-900/80 dark:bg-slate-950/85 dark:text-white" style="{{ $this->designElementStyle($this->designStoredCoordinate($designData, 'name', 'x'), $this->designStoredCoordinate($designData, 'name', 'y'), $designData['font_size'] ?? 24, 'name') }}">
                                                                Nombre participante
                                                            </div>
                                                            <div class="absolute overflow-hidden rounded-lg border-2 border-slate-900/80 bg-white shadow-sm dark:border-white/70" style="{{ $this->designElementStyle($this->designStoredCoordinate($designData, 'qr', 'x'), $this->designStoredCoordinate($designData, 'qr', 'y'), $designData['qr_size'] ?? 18, 'qr') }}">
                                                                <div class="grid h-full w-full grid-cols-5 gap-[1px] bg-slate-900/80 p-1 dark:bg-white/70">
                                                                    @for ($index = 0; $index < 25; $index++)
                                                                        <span class="{{ ($index + ($index % 3)) % 2 === 0 ? 'bg-slate-900 dark:bg-white' : 'bg-white dark:bg-slate-900' }}"></span>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-medium dark:bg-slate-800">{{ $designData['page_size'] ?? 'Tamaño pendiente' }}</span>
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-medium dark:bg-slate-800">{{ $designData['orientation'] ?? 'Orientación pendiente' }}</span>
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-medium dark:bg-slate-800">Letra: {{ (($designData['font_size'] ?? 6) > 12) ? round(($designData['font_size'] ?? 6) / 4, 1) : ($designData['font_size'] ?? 6) }} %</span>
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-medium dark:bg-slate-800">QR: {{ $designData['qr_size'] ?? 18 }} %</span>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>

                        @if ($showDesignModal)
                            @php
                                $activeDesign = $designs[$activeDesignKey] ?? null;
                                $activeDesignUrl = $currentDesignPath
                                    ? (\Illuminate\Support\Str::startsWith($currentDesignPath, ['http://', 'https://']) ? $currentDesignPath : asset($currentDesignPath))
                                    : null;
                                $activeDesignPdf = $activeDesignUrl && \Illuminate\Support\Str::endsWith(strtolower($activeDesignUrl), '.pdf');
                            @endphp
                            <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
                                <div class="max-h-full w-full max-w-6xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                                    <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $designTypes[$activeDesignKey] }}@if ($activeDesignKey === $requiredDesignKey) <span class="text-red-500">*</span>@endif</h3>
                                            </div>
                                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Define tamaño, orientación y luego arrastra libremente el nombre y el QR sobre la plantilla para ubicarlos exactamente donde los necesitas.</p>
                                        </div>

                                        <button type="button" wire:click="closeDesignModal" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>

                                    @php
                                        $isVerticalPreview = ($design_orientation ?: 'Vertical') === 'Vertical';
                                        $previewStageHeight = $isVerticalPreview ? 'min-h-[56rem] lg:min-h-[66rem]' : 'min-h-[38rem] lg:min-h-[46rem]';
                                        $previewCanvasWidth = $isVerticalPreview ? 'max-w-[52rem]' : 'max-w-[78rem]';
                                        $previewStageAlignment = $isVerticalPreview ? 'items-start pt-4 lg:pt-6' : 'items-center';
                                    @endphp

                                    <form
                                        wire:submit.prevent="saveGraphicDesign"
                                        x-data="{
                                            dragging: null,
                                            surfaceWidth: 0,
                                            surfaceHeight: 0,
                                            nameX: @entangle('design_name_x').live,
                                            nameY: @entangle('design_name_y').live,
                                            qrX: @entangle('design_qr_x').live,
                                            qrY: @entangle('design_qr_y').live,
                                            init() {
                                                this.syncSurfaceMetrics();
                                                if (this.$refs.designSurface && window.ResizeObserver) {
                                                    this.surfaceObserver = new ResizeObserver(() => this.syncSurfaceMetrics());
                                                    this.surfaceObserver.observe(this.$refs.designSurface);
                                                }
                                            },
                                            syncSurfaceMetrics() {
                                                if (!this.$refs.designSurface) return;
                                                this.surfaceWidth = this.$refs.designSurface.clientWidth || 0;
                                                this.surfaceHeight = this.$refs.designSurface.clientHeight || 0;
                                            },
                                            pointFromEvent(event) {
                                                if (event.touches && event.touches.length) return event.touches[0];
                                                if (event.changedTouches && event.changedTouches.length) return event.changedTouches[0];
                                                return event;
                                            },
                                            clamp(value, min, max) {
                                                return Math.min(max, Math.max(min, value));
                                            },
                                            startDrag(type, event) {
                                                this.dragging = type;
                                                this.moveDrag(event);
                                            },
                                            moveDrag(event) {
                                                if (!this.dragging || !this.$refs.designSurface) return;
                                                const point = this.pointFromEvent(event);
                                                const rect = this.$refs.designSurface.getBoundingClientRect();
                                                const x = this.clamp(((point.clientX - rect.left) / rect.width) * 100, 0, 100);
                                                const y = this.clamp(((point.clientY - rect.top) / rect.height) * 100, 0, 100);
                                                if (this.dragging === 'name') {
                                                    this.nameX = Number(x.toFixed(2));
                                                    this.nameY = Number(y.toFixed(2));
                                                } else {
                                                    this.qrX = Number(x.toFixed(2));
                                                    this.qrY = Number(y.toFixed(2));
                                                }
                                            },
                                            stopDrag() {
                                                this.dragging = null;
                                            },
                                            nameFontPx() {
                                                const base = Math.min(this.surfaceWidth || 0, this.surfaceHeight || 0);
                                                const proportional = base * ((Number(this.$wire.design_font_size) || 0) / 100);
                                                return Math.max(18, proportional || 0);
                                            },
                                            nameStyle() {
                                                return `left:${this.nameX}%;top:${this.nameY}%;transform:translate(-50%,-50%);max-width:calc(100% - 1.5rem);font-size:${this.nameFontPx()}px;line-height:1.15;text-align:center;white-space:nowrap;`;
                                            },
                                            qrStyle() {
                                                return `left:${this.qrX}%;top:${this.qrY}%;width:${this.$wire.design_qr_size}%;aspect-ratio:1/1;transform:translate(-50%,-50%);`;
                                            }
                                        }"
                                        @mousemove.window="moveDrag($event)"
                                        @mouseup.window="stopDrag()"
                                        @touchmove.window.prevent="moveDrag($event)"
                                        @touchend.window="stopDrag()"
                                        class="space-y-6 px-6 py-6 lg:space-y-7"
                                    >
                                        <div class="grid gap-6 lg:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
                                            <div class="space-y-4">
                                                <div class="grid gap-4 md:grid-cols-2">
                                                    <div>
                                                        <label for="design_page_size" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tamaño de impresión <span class="text-red-500">*</span></label>
                                                        <select id="design_page_size" wire:model.live="design_page_size" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                            <option value="">Selecciona un tamaño</option>
                                                            @foreach ($pageSizes as $pageSize)
                                                                <option value="{{ $pageSize }}">{{ $pageSize }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('design_page_size') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                    </div>

                                                    <div>
                                                        <label for="design_orientation" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Orientación <span class="text-red-500">*</span></label>
                                                        <select id="design_orientation" wire:model.live="design_orientation" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                            <option value="">Selecciona una orientación</option>
                                                            @foreach ($orientations as $orientation)
                                                                <option value="{{ $orientation }}">{{ $orientation }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('design_orientation') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>

                                                <div class="grid gap-4 md:grid-cols-2">
                                                    <div>
                                                        <label for="design_font_size" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tamaño de letra proporcional <span class="text-red-500">*</span></label>
                                                        <input id="design_font_size" type="range" min="3" max="12" step="0.5" wire:model.live="design_font_size" class="block w-full accent-yellow-500">
                                                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                                            <span>3 %</span>
                                                            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $design_font_size }} %</span>
                                                            <span>12 %</span>
                                                        </div>
                                                        @error('design_font_size') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                    </div>

                                                    <div>
                                                        <label for="design_qr_size" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tamaño del QR <span class="text-red-500">*</span></label>
                                                        <input id="design_qr_size" type="range" min="12" max="28" step="1" wire:model.live="design_qr_size" class="block w-full accent-yellow-500">
                                                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                                            <span>12 %</span>
                                                            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $design_qr_size }} %</span>
                                                            <span>28 %</span>
                                                        </div>
                                                        @error('design_qr_size') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="space-y-5">
                                                <label for="graphic-design-upload" class="group flex cursor-pointer flex-col items-center justify-center rounded-[1.75rem] border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-5 text-center transition hover:border-yellow-400 hover:bg-yellow-50/60 dark:border-slate-700 dark:bg-slate-900/60 dark:hover:border-yellow-600 dark:hover:bg-yellow-500/5">
                                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm transition group-hover:text-yellow-600 dark:bg-slate-950 dark:text-slate-300">
                                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0-4 4m4-4 4 4" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 16.5a3.5 3.5 0 0 1-3.5 3.5h-9A3.5 3.5 0 0 1 4 16.5" />
                                                        </svg>
                                                    </span>
                                                    <span class="mt-3 text-sm font-semibold text-slate-900 dark:text-slate-100">Arrastra y suelta el diseño aquí</span>
                                                    <span class="mt-1 text-xs text-slate-500 dark:text-slate-400">O haz clic para buscar el archivo desde tu equipo.</span>
                                                    <span class="mt-3 inline-flex rounded-full bg-yellow-500 px-4 py-2 text-sm font-medium text-slate-950 transition group-hover:bg-yellow-400">
                                                        Seleccionar archivo
                                                    </span>
                                                    <input id="graphic-design-upload" type="file" wire:model="graphicDesignFile" class="sr-only" accept=".jpg,.jpeg,.png,.webp,.pdf">
                                                </label>
                                                @error('graphicDesignFile') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-300">
                                                    @if ($graphicDesignFile)
                                                        Archivo listo para guardar: {{ $graphicDesignFile->getClientOriginalName() }}
                                                    @elseif ($currentDesignPath)
                                                        Archivo actual cargado. Puedes cambiarlo cuando quieras.
                                                    @else
                                                        Aún no hay archivo cargado para esta pieza gráfica.
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                                            <div class="flex flex-col gap-4 border-b border-slate-200 pb-4 dark:border-slate-800 lg:flex-row lg:items-start lg:justify-between">
                                                <div>
                                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Vista previa y ubicación</p>
                                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Usa toda esta área para arrastrar el nombre y el QR sobre la plantilla hasta dejarlos en su posición final.</p>
                                                </div>
                                                <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[24rem]">
                                                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                                                        <span class="font-medium text-slate-900 dark:text-slate-100">Nombre:</span> <span x-text="`${nameX}% , ${nameY}%`"></span> · {{ $design_font_size }} %
                                                    </div>
                                                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                                                        <span class="font-medium text-slate-900 dark:text-slate-100">QR:</span> <span x-text="`${qrX}% , ${qrY}%`"></span> · {{ $design_qr_size }} %
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-5 rounded-[1.75rem] border border-dashed border-slate-300 bg-white p-4 lg:p-6 dark:border-slate-700 dark:bg-slate-900">
                                                <div class="flex {{ $previewStageHeight }} {{ $previewStageAlignment }} justify-center">
                                                    <div class="w-full {{ $previewCanvasWidth }}">
                                                        <div x-ref="designSurface" class="relative overflow-hidden border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950" style="{{ $this->designPreviewStyle($design_page_size, $design_orientation) }}">
                                                            @if ($graphicDesignFile && method_exists($graphicDesignFile, 'temporaryUrl') && ! \Illuminate\Support\Str::endsWith(strtolower($graphicDesignFile->getClientOriginalName()), '.pdf'))
                                                                <img src="{{ $graphicDesignFile->temporaryUrl() }}" alt="Vista previa del diseño" class="h-full w-full object-contain bg-white">
                                                            @elseif ($activeDesignUrl && ! $activeDesignPdf)
                                                                <img src="{{ $activeDesignUrl }}" alt="{{ $designTypes[$activeDesignKey] }}" class="h-full w-full object-contain bg-white">
                                                            @else
                                                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 via-white to-yellow-50 p-6 text-center dark:from-slate-900 dark:via-slate-950 dark:to-slate-900">
                                                                    <div>
                                                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">
                                                                            @if ($graphicDesignFile && \Illuminate\Support\Str::endsWith(strtolower($graphicDesignFile->getClientOriginalName()), '.pdf'))
                                                                                PDF seleccionado
                                                                            @elseif ($activeDesignPdf)
                                                                                PDF cargado
                                                                            @else
                                                                                Vista genérica
                                                                            @endif
                                                                        </p>
                                                                        <p class="mt-2 text-base font-medium text-slate-700 dark:text-slate-200">{{ $designTypes[$activeDesignKey] }}</p>
                                                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">El marco se ajusta según el tamaño y orientación elegidos.</p>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="absolute inset-0">
                                                                <div
                                                                    class="absolute cursor-move rounded-xl border border-emerald-300 bg-white/92 px-3 py-2 font-semibold text-slate-900 shadow-lg ring-2 ring-emerald-200/80 dark:border-emerald-700 dark:bg-slate-950/92 dark:text-white dark:ring-emerald-900/60"
                                                                    style="touch-action:none;"
                                                                    :style="nameStyle()"
                                                                    @mousedown.prevent="startDrag('name', $event)"
                                                                    @touchstart.prevent="startDrag('name', $event)"
                                                                >
                                                                    Nombre del participante
                                                                </div>

                                                                <div
                                                                    class="absolute cursor-move overflow-hidden rounded-lg border-2 border-sky-500 bg-white shadow-lg ring-2 ring-sky-200/80 dark:border-sky-400 dark:ring-sky-900/60"
                                                                    style="touch-action:none;"
                                                                    :style="qrStyle()"
                                                                    @mousedown.prevent="startDrag('qr', $event)"
                                                                    @touchstart.prevent="startDrag('qr', $event)"
                                                                >
                                                                    <div class="grid h-full w-full grid-cols-5 gap-[2px] bg-slate-900/80 p-1 dark:bg-white/70">
                                                                        @for ($index = 0; $index < 25; $index++)
                                                                            <span class="{{ ($index + ($index % 3)) % 2 === 0 ? 'bg-slate-900 dark:bg-white' : 'bg-white dark:bg-slate-900' }}"></span>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-4 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                                                Arrastra libremente el bloque <span class="font-semibold text-slate-900 dark:text-slate-100">Nombre del participante</span> y el <span class="font-semibold text-slate-900 dark:text-slate-100">QR</span> directamente sobre la plantilla. La posición se guardará exacta sobre la imagen.
                                            </div>
                                            @error('design_name_x') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror
                                            @error('design_name_y') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror
                                            @error('design_qr_x') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror
                                            @error('design_qr_y') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                                            <button type="button" wire:click="closeDesignModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400">
                                                <span wire:loading.remove wire:target="saveGraphicDesign,graphicDesignFile">Guardar diseño</span>
                                                <span wire:loading wire:target="saveGraphicDesign,graphicDesignFile">Guardando...</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if ($activeSection === 'conferencias')
                        <div class="space-y-6">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Conferencias y talleres</h2>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Organiza la agenda completa del evento desde esta pantalla. Cada día del evento muestra sus conferencias en una línea de tiempo según el rango horario configurado.</p>

                            @if (empty($eventDays))
                                <div class="rounded-3xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                                    Primero define la fecha inicial y final del evento para habilitar la agenda por días.
                                </div>
                            @else
                                @php
                                    $selectedDaySchedule = $scheduleByDay[$selectedConferenceDay] ?? [];
                                    $selectedDayMeta = collect($eventDays)->firstWhere('date', $selectedConferenceDay);
                                    $calendarBounds = $this->conferenceCalendarBounds($selectedDaySchedule);
                                    $calendarHours = $this->conferenceCalendarHours($selectedDaySchedule);
                                @endphp

                                <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,3fr)]">
                                    <aside class="rounded-[2rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                                        <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 dark:border-slate-800">
                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Agenda por día</h3>
                                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Selecciona un día para ver su calendario y administrar las conferencias.</p>
                                            </div>

                                            <button type="button" wire:click="openConferenceModal()" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-emerald-400">
                                                Agregar conferencia
                                            </button>
                                        </div>

                                        <div class="mt-5 space-y-3">
                                            @foreach ($eventDays as $day)
                                                <button
                                                    type="button"
                                                    wire:click="selectConferenceDay('{{ $day['date'] }}')"
                                                    class="w-full rounded-3xl border px-5 py-4 text-left transition {{ $selectedConferenceDay === $day['date'] ? 'border-yellow-300 bg-yellow-50 shadow-[0_18px_40px_-32px_rgba(234,179,8,0.85)] dark:border-yellow-700 dark:bg-yellow-500/10' : 'border-slate-200 bg-white hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700' }}"
                                                >
                                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">{{ $day['label'] }}</p>
                                                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $day['display'] }}</p>
                                                    <p class="mt-3 text-sm {{ $day['count'] > 0 ? 'text-emerald-600 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">
                                                        {{ $day['count'] }} {{ \Illuminate\Support\Str::plural('conferencia', $day['count']) }}
                                                    </p>
                                                </button>
                                            @endforeach
                                        </div>
                                    </aside>

                                    <div class="min-w-0 rounded-[2rem] border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-950/50">
                                        <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 dark:border-slate-800 sm:flex-row sm:items-end sm:justify-between">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">{{ $selectedDayMeta['label'] ?? 'Agenda' }}</p>
                                                <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $selectedDayMeta['display'] ?? 'Sin fecha seleccionada' }}</h3>
                                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Calendario diario visible entre las {{ strtolower($calendarBounds['start_label']) }} y las {{ strtolower($calendarBounds['end_label']) }}.</p>
                                            </div>

                                            <button type="button" wire:click="openConferenceModal()" class="inline-flex items-center justify-center rounded-2xl border border-yellow-300 px-5 py-3 text-sm font-medium text-yellow-700 transition hover:bg-yellow-50 dark:border-yellow-800 dark:text-yellow-300 dark:hover:bg-yellow-950/30">
                                                Nueva en este día
                                            </button>
                                        </div>

                                        <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/60">
                                            <div class="w-full min-w-0" style="{{ $this->conferenceCalendarTrackStyle($selectedDaySchedule) }}">
                                                <div class="grid grid-cols-[6.5rem_minmax(0,1fr)] border-b border-slate-200 dark:border-slate-800">
                                                    <div class="px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Horas</div>
                                                    <div class="px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Agenda del día</div>
                                                </div>

                                                <div class="grid grid-cols-[6.5rem_minmax(0,1fr)] overflow-visible">
                                                    <div class="border-r border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950">
                                                    @foreach ($calendarHours as $hour)
                                                        <div class="flex h-28 items-start justify-end border-b border-slate-200 px-4 py-4 text-base font-medium text-slate-500 last:border-b-0 dark:border-slate-800 dark:text-slate-400">
                                                            {{ strtolower($hour['label']) }}
                                                        </div>
                                                    @endforeach
                                                    </div>

                                                    <div class="relative min-w-0 overflow-visible bg-white px-3 dark:bg-slate-950">
                                                        @foreach ($calendarHours as $hour)
                                                            <div class="h-28 border-b border-slate-200 last:border-b-0 dark:border-slate-800"></div>
                                                        @endforeach

                                                        @foreach ($selectedDaySchedule as $conference)
                                                            @php
                                                                $cardClasses = $conference['is_pending']
                                                                    ? 'border-amber-300 bg-amber-100/95 dark:border-amber-700 dark:bg-amber-900/80'
                                                                    : 'border-emerald-300 bg-emerald-100/95 dark:border-emerald-700 dark:bg-emerald-900/85';
                                                                $timeClasses = $conference['is_pending']
                                                                    ? 'text-amber-700 dark:text-amber-200'
                                                                    : 'text-emerald-700 dark:text-emerald-200';
                                                                $isCompactConference = $conference['duration_minutes'] <= 60;
                                                            @endphp
                                                            <article
                                                                class="absolute overflow-visible rounded-2xl border shadow-sm backdrop-blur {{ $cardClasses }}"
                                                                style="{{ $this->conferenceCalendarStyle($conference, $selectedDaySchedule) }}"
                                                            >
                                                                <div class="flex h-full flex-col gap-2 px-4 py-3">
                                                                    <div class="flex items-start justify-between gap-3">
                                                                        <div class="min-w-0 flex-1">
                                                                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white" title="{{ $conference['nombre'] }}">{{ $this->truncateConferenceTitle($conference['nombre'], 28) }}</p>
                                                                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                                                                <p class="text-xs font-medium uppercase tracking-[0.16em] {{ $timeClasses }}">{{ $conference['hora_inicio'] }} - {{ $conference['hora_fin'] }}</p>
                                                                                <span class="inline-flex rounded-full bg-white/80 px-2.5 py-1 text-[11px] font-medium text-slate-700 dark:bg-slate-900/70 dark:text-slate-200">{{ $conference['lugar'] }}</span>
                                                                                @if ($conference['is_pending'])
                                                                                    <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-amber-700 dark:bg-amber-950/60 dark:text-amber-200">Pendiente</span>
                                                                                @else
                                                                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-200">Completa</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>

                                                                        <div class="flex shrink-0 items-center gap-2">
                                                                            <button
                                                                                type="button"
                                                                                wire:click="openSpeakerLinkModal({{ $conference['id'] }})"
                                                                                title="Compartir enlace de actualizacion de datos a conferencista"
                                                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-white/80 text-emerald-700 transition hover:bg-white hover:text-emerald-900 focus:outline-none focus:ring-4 focus:ring-emerald-100 dark:bg-slate-900/75 dark:text-emerald-300 dark:hover:bg-slate-900 dark:hover:text-emerald-100 dark:focus:ring-emerald-500/20"
                                                                            >
                                                                                <span class="sr-only">Compartir enlace de actualizacion de datos a conferencista</span>
                                                                                <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                                                    <path d="M8.6 13.5 15.4 17.5M15.4 6.5 8.6 10.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                    <path d="M18 8.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM18 21.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                                                                                </svg>
                                                                            </button>

                                                                            <div class="relative z-50" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                                                                            <button type="button" @click="open = !open" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/80 text-slate-600 transition hover:bg-white hover:text-slate-900 dark:bg-slate-900/75 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white">
                                                                                <span class="sr-only">Opciones de conferencia</span>
                                                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                                    <path d="M10 6a1.75 1.75 0 1 0 0-3.5A1.75 1.75 0 0 0 10 6Zm0 5.75A1.75 1.75 0 1 0 10 8.25a1.75 1.75 0 0 0 0 3.5ZM11.75 15.5a1.75 1.75 0 1 1-3.5 0 1.75 1.75 0 0 1 3.5 0Z" />
                                                                                </svg>
                                                                            </button>
                                                                            <div x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 top-11 z-[90] w-52 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl ring-1 ring-slate-200/70 dark:border-slate-700 dark:bg-slate-900 dark:ring-slate-700/70">
                                                                                <button type="button" @click="open = false" wire:click="openConferenceModal({{ $conference['id'] }})" class="flex w-full items-center rounded-xl px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                                                                    Editar conferencia
                                                                                </button>
                                                                                <button type="button" @click="open = false" wire:click="confirmDeleteConference({{ $conference['id'] }})" class="flex w-full items-center rounded-xl px-3 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-950/30">
                                                                                    Eliminar conferencia
                                                                                </button>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="min-w-0">
                                                                        <p class="truncate text-xs text-slate-600 dark:text-slate-300" title="{{ $conference['conferencista'] }}">{{ $conference['conferencista'] }}</p>
                                                                        @if (! $isCompactConference)
                                                                            <p class="mt-2 line-clamp-2 text-xs text-slate-700 dark:text-slate-200" title="{{ $conference['descripcion'] }}">{{ $conference['descripcion'] }}</p>
                                                                        @endif
                                                                        @if ($conference['is_pending'] && ! $isCompactConference)
                                                                            <span class="mt-2 block text-[11px] font-medium text-amber-700 dark:text-amber-200">El conferencista aún debe completar su perfil y la información final.</span>
                                                                        @endif
                                                                        @if ($conference['is_pending'] && $isCompactConference)
                                                                            <span class="mt-1 block truncate text-[11px] font-medium text-amber-700 dark:text-amber-200">Perfil e información final pendientes.</span>
                                                                        @endif
                                                                        @if ($conference['linkreunion'] && ! $isCompactConference)
                                                                            <a href="{{ $conference['linkreunion'] }}" target="_blank" class="mt-2 inline-flex text-[11px] font-medium text-slate-700 underline decoration-slate-300 underline-offset-2 dark:text-slate-200 dark:decoration-slate-600">
                                                                                Abrir enlace virtual
                                                                            </a>
                                                                        @endif
                                                                </div>
                                                            </article>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($showDeleteConferenceModal)
                                <div class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
                                    <div class="w-full max-w-md rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                                        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Eliminar conferencia</h3>
                                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Esta acción eliminará la conferencia del calendario del evento.</p>
                                        </div>

                                        <div class="px-6 py-6">
                                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-200">
                                                ¿Deseas eliminar <span class="font-semibold">"{{ $pendingDeleteConferenceName }}"</span>?
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 dark:border-slate-800 sm:flex-row sm:justify-end">
                                            <button type="button" wire:click="closeDeleteConferenceModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Cancelar
                                            </button>
                                            <button type="button" wire:click="deleteConferenceConfirmed" class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-red-500">
                                                Eliminar conferencia
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($showSpeakerLinkModal)
                                <div class="fixed inset-0 z-[75] flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
                                    <div class="w-full max-w-2xl rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                                        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Enlace del conferencista</h3>
                                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Gestiona el acceso para que el conferencista complete su wizard y actualice su información.</p>
                                                </div>
                                                <button type="button" wire:click="closeSpeakerLinkModal" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="space-y-5 px-6 py-6" x-data="{ copied: false }">
                                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Conferencia</p>
                                                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $speakerLinkConferenceName }}</p>
                                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                                    @if ($speakerLinkRequiresLogin)
                                                        Este conferencista ya tiene usuario en el sistema. El enlace lo llevará a iniciar sesión y luego al wizard.
                                                    @else
                                                        Este enlace abrirá directamente el wizard para completar perfil y conferencia.
                                                    @endif
                                                </p>
                                            </div>

                                            @if (session()->has('message'))
                                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
                                                    {{ session('message') }}
                                                </div>
                                            @endif

                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Enlace único generado</label>
                                                <div class="rounded-2xl border border-slate-300 bg-white px-4 py-4 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                                    <span class="break-all">{{ $speakerLinkUrl }}</span>
                                                </div>
                                            </div>

                                            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                                                <button
                                                    type="button"
                                                    @click="navigator.clipboard.writeText(@js($speakerLinkUrl)).then(() => { copied = true; setTimeout(() => copied = false, 1800) })"
                                                    class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                                                >
                                                    <span x-show="!copied">Copiar al portapapeles</span>
                                                    <span x-show="copied" x-cloak>Copiado</span>
                                                </button>
                                                <a href="{{ $speakerWhatsappUrl }}" target="_blank" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-emerald-400">
                                                    Enviar por WhatsApp
                                                </a>
                                                <button type="button" wire:click="regenerateSpeakerLink" class="inline-flex items-center justify-center rounded-2xl border border-amber-300 px-5 py-3 text-sm font-medium text-amber-700 transition hover:bg-amber-50 dark:border-amber-800 dark:text-amber-300 dark:hover:bg-amber-950/30">
                                                    Regenerar enlace
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($showConferenceModal)
                                <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
                                    <div class="max-h-full w-full max-w-3xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                                        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $conference_id ? 'Editar conferencia' : 'Nueva conferencia' }}</h3>
                                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Registra nombre, día, horario, ubicación y conferencista para mostrarlo automáticamente en la agenda del evento.</p>
                                            </div>

                                            <button type="button" wire:click="closeConferenceModal" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <form wire:submit.prevent="saveConference" class="grid gap-5 px-6 py-6">
                                            <div>
                                                <label for="conference_nombre" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre de la conferencia <span class="text-red-500">*</span></label>
                                                <input id="conference_nombre" type="text" wire:model.live="conference_nombre" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                @error('conference_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <div class="relative">
                                                <label for="conference_conferencista_nombre" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Conferencista <span class="text-red-500">*</span></label>
                                                <input id="conference_conferencista_nombre" type="text" wire:model.live.debounce.300ms="conference_conferencista_nombre" autocomplete="off" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-10 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10" placeholder="Nombre completo del conferencista">
                                                <span class="pointer-events-none absolute right-4 top-[3.15rem] text-slate-400 dark:text-slate-500">
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                @if ($conference_conferencista_id)
                                                    <div class="mt-3 flex items-center justify-between gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
                                                        <span>Se usará un usuario existente para esta agenda. Si completa todo el wizard, se convertirá en conferencista.</span>
                                                        <button type="button" wire:click="clearExistingConferenceSpeaker" class="font-medium text-emerald-700 underline underline-offset-2 dark:text-emerald-200">
                                                            Cambiar
                                                        </button>
                                                    </div>
                                                @endif
                                                @if ($this->matchingConferenceSpeakers()->isNotEmpty())
                                                    <div class="absolute left-0 right-0 z-30 mt-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_22px_60px_-28px_rgba(15,23,42,0.45)] dark:border-slate-800 dark:bg-slate-950">
                                                        <div class="border-b border-slate-100 px-4 py-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:border-slate-800 dark:text-slate-400">Usuarios encontrados</div>
                                                        <div class="max-h-64 overflow-y-auto p-2">
                                                            @foreach ($this->matchingConferenceSpeakers() as $speaker)
                                                                <button type="button" wire:click="selectExistingConferenceSpeaker({{ $speaker->id }})" class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm text-slate-700 transition hover:bg-yellow-50 dark:text-slate-200 dark:hover:bg-yellow-950/20">
                                                                    <span class="min-w-0">
                                                                        <span class="block truncate font-medium">{{ trim(($speaker->nombre ?? '') . ' ' . ($speaker->apellido ?? '')) }}</span>
                                                                        @if ($speaker->user?->email)
                                                                            <span class="mt-1 block truncate text-xs text-slate-500 dark:text-slate-400">{{ $speaker->user->email }}</span>
                                                                        @endif
                                                                    </span>
                                                                    <span class="ml-3 shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                                                                        {{ $speaker->conferencistas->isNotEmpty() ? 'Conferencista' : 'Usuario' }}
                                                                    </span>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Si no se encuentra el conferencista, escribe el nombre completo y este creará su perfil desde el enlace de completación de la conferencia.</p>
                                                @error('conference_conferencista_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <label for="conference_descripcion" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Descripción <span class="text-red-500">*</span></label>
                                                <textarea id="conference_descripcion" rows="4" wire:model.live="conference_descripcion" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"></textarea>
                                                @error('conference_descripcion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                                                <div>
                                                    <label for="conference_tipo_conferencia_id" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tipo <span class="text-red-500">*</span></label>
                                                    <select id="conference_tipo_conferencia_id" wire:model.live="conference_tipo_conferencia_id" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                        <option value="">Selecciona un tipo</option>
                                                        @foreach ($tiposConferencias as $tipoConferencia)
                                                            <option value="{{ $tipoConferencia->id }}">{{ $tipoConferencia->tipo }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('conference_tipo_conferencia_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>

                                                <div class="lg:col-span-2">
                                                    <label for="conference_fecha" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Día del evento <span class="text-red-500">*</span></label>
                                                    <select id="conference_fecha" wire:model.live="conference_fecha" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                        <option value="">Selecciona un día</option>
                                                        @foreach ($eventDays as $day)
                                                            <option value="{{ $day['date'] }}">{{ $day['label'] }} ({{ $day['display'] }})</option>
                                                        @endforeach
                                                    </select>
                                                    @error('conference_fecha') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label for="conference_hora_inicio" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Hora inicio <span class="text-red-500">*</span></label>
                                                    <input id="conference_hora_inicio" type="time" wire:model.live="conference_hora_inicio" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                    @error('conference_hora_inicio') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label for="conference_hora_fin" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Hora fin <span class="text-red-500">*</span></label>
                                                    <input id="conference_hora_fin" type="time" wire:model.live="conference_hora_fin" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                    @error('conference_hora_fin') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>
                                            </div>

                                            <div class="grid gap-4 md:grid-cols-2">
                                                <div>
                                                    <label for="conference_lugar" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Lugar <span class="text-red-500">*</span></label>
                                                    <input id="conference_lugar" type="text" wire:model.live="conference_lugar" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                    @error('conference_lugar') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>
                                            </div>

                                            @if ($this->allowsConferenceLink())
                                                <div>
                                                    <label for="conference_linkreunion" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                                                        Link de reunión @if($this->requiresConferenceLink())<span class="text-red-500">*</span>@endif
                                                    </label>
                                                    <input id="conference_linkreunion" type="url" wire:model.live="conference_linkreunion" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                                        @if ($this->requiresConferenceLink())
                                                            Este evento es virtual, por lo tanto el enlace de reunión es obligatorio.
                                                        @else
                                                            Este evento es híbrido, así que el enlace virtual es opcional.
                                                        @endif
                                                    </p>
                                                    @error('conference_linkreunion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>
                                            @endif

                                            <div class="flex flex-col gap-3 border-t border-slate-200 pt-5 dark:border-slate-800 sm:flex-row sm:justify-end">
                                                <button type="button" wire:click="closeConferenceModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                                    Cancelar
                                                </button>
                                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400">
                                                    <span wire:loading.remove wire:target="saveConference">Guardar conferencia</span>
                                                    <span wire:loading wire:target="saveConference">Guardando...</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($activeSection === 'prices')
                        <div class="space-y-5">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Precios e inscripciones</h2>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Configura tarifas usando los tipos de perfil del catálogo. Cada tipo debe tener su precio del día del evento y, si lo necesitas, rangos opcionales para fechas específicas.</p>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tarifas por tipo de perfil</h3>

                                <form wire:submit.prevent="savePrices" class="mt-5 grid gap-6">
                                    @error('profilePriceConfigurations') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                                    <div class="space-y-5">
                                        @foreach ($tiposPerfilCatalog as $tipoPerfil)
                                            @php
                                                $configuration = $profilePriceConfigurations[$tipoPerfil->id] ?? ['event_day_amount' => '', 'ranges' => []];
                                                $ranges = $configuration['ranges'] ?? [];
                                            @endphp
                                            <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                                                <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                                                    <div>
                                                        <h4 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $tipoPerfil->tipoperfil }}</h4>
                                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Define el precio para inscribirse el día del evento y agrega rangos opcionales para periodos específicos de inscripción.</p>
                                                    </div>

                                                    <div class="w-full max-w-xl">
                                                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Precio del día del evento <span class="text-red-500">*</span></label>
                                                        <div class="grid gap-3 sm:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
                                                            <select wire:model.live="profilePriceConfigurations.{{ $tipoPerfil->id }}.currency_id" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                                <option value="">Selecciona moneda</option>
                                                                @foreach ($monedasCatalog as $moneda)
                                                                    <option value="{{ $moneda->id }}">{{ $moneda->codigo }} @if($moneda->simbolo)· {{ $moneda->simbolo }} @endif</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="number" step="0.01" min="0.01" wire:model.live="profilePriceConfigurations.{{ $tipoPerfil->id }}.event_day_amount" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10" placeholder="0.00">
                                                        </div>
                                                        @error("profilePriceConfigurations.$tipoPerfil->id.currency_id") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                        @error("profilePriceConfigurations.$tipoPerfil->id.event_day_amount") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>

                                                <div class="mt-5">
                                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                                        <div>
                                                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100">Rangos opcionales de fecha</p>
                                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Úsalos si quieres cobrar distinto en ciertos periodos. Si no agregas rangos, se aplicará solo el precio del día del evento.</p>
                                                        </div>

                                                        <button type="button" wire:click="addProfilePriceRange({{ $tipoPerfil->id }})" class="inline-flex items-center justify-center rounded-2xl border border-emerald-300 px-4 py-2 text-sm font-medium text-emerald-700 transition hover:bg-emerald-50 dark:border-emerald-800 dark:text-emerald-300 dark:hover:bg-emerald-950/30">
                                                            Agregar rango
                                                        </button>
                                                    </div>

                                                    @if (count($ranges) > 0)
                                                        <div class="mt-5 space-y-4">
                                                            @foreach ($ranges as $index => $range)
                                                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                                                                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.2fr)_repeat(4,minmax(0,1fr))_auto]">
                                                                        <div>
                                                                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre del rango <span class="text-red-500">*</span></label>
                                                                            <input type="text" wire:model.live="profilePriceConfigurations.{{ $tipoPerfil->id }}.ranges.{{ $index }}.label" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10" placeholder="Ej. Preventa 1 o inscripción temprana">
                                                                            @error("profilePriceConfigurations.$tipoPerfil->id.ranges.$index.label") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                                        </div>

                                                                        <div>
                                                                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Desde <span class="text-red-500">*</span></label>
                                                                            <input type="date" wire:model.live="profilePriceConfigurations.{{ $tipoPerfil->id }}.ranges.{{ $index }}.start_date" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                                            @error("profilePriceConfigurations.$tipoPerfil->id.ranges.$index.start_date") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                                        </div>

                                                                        <div>
                                                                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Hasta <span class="text-red-500">*</span></label>
                                                                            <input type="date" wire:model.live="profilePriceConfigurations.{{ $tipoPerfil->id }}.ranges.{{ $index }}.end_date" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                                            @error("profilePriceConfigurations.$tipoPerfil->id.ranges.$index.end_date") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                                        </div>

                                                                        <div>
                                                                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Precio <span class="text-red-500">*</span></label>
                                                                            <div class="grid gap-3">
                                                                                <select wire:model.live="profilePriceConfigurations.{{ $tipoPerfil->id }}.ranges.{{ $index }}.currency_id" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
                                                                                    <option value="">Selecciona moneda</option>
                                                                                    @foreach ($monedasCatalog as $moneda)
                                                                                        <option value="{{ $moneda->id }}">{{ $moneda->codigo }} @if($moneda->simbolo)· {{ $moneda->simbolo }} @endif</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <input type="number" step="0.01" min="0.01" wire:model.live="profilePriceConfigurations.{{ $tipoPerfil->id }}.ranges.{{ $index }}.amount" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10" placeholder="0.00">
                                                                            </div>
                                                                            @error("profilePriceConfigurations.$tipoPerfil->id.ranges.$index.currency_id") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                                            @error("profilePriceConfigurations.$tipoPerfil->id.ranges.$index.amount") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                                                        </div>

                                                                        <div class="flex items-end">
                                                                            <button type="button" wire:click="removeProfilePriceRange({{ $tipoPerfil->id }}, {{ $index }})" class="inline-flex items-center justify-center rounded-2xl border border-red-300 px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-950/30">
                                                                                Quitar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="mt-5 rounded-2xl border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                                            No hay rangos adicionales para {{ strtolower($tipoPerfil->tipoperfil) }}. Se usará solo el precio del día del evento.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </form>
                            </div>
                        </div>
                    @endif

                    @if ($activeSection === 'publication')
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Publicación del evento</h2>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Revisa el resumen completo antes de publicarlo. Cuando publiques, el evento quedará visible en la portada del sistema.</p>
                            </div>

                            <div class="grid gap-4 xl:grid-cols-4">
                                <article class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Estado</p>
                                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $evento->estado === 'publicado' ? 'Publicado' : 'Listo para publicar' }}</p>
                                </article>
                                <article class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Conferencias</p>
                                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $evento->conferencias->count() }}</p>
                                </article>
                                <article class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Tipos de perfil con precio</p>
                                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $tipo_acceso === 'pagada' ? count($tiposPerfilCatalog) : 'No aplica' }}</p>
                                </article>
                                <article class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Diseños</p>
                                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $genera_diploma_participacion ? 'Configurados' : 'No aplica' }}</p>
                                </article>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Información principal</h3>
                                <div class="mt-4 grid gap-4 md:grid-cols-2">
                                    <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Evento</p>
                                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $evento->nombreevento }}</p>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $evento->descripcion }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Configuración base</p>
                                        <div class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                            <p><span class="font-semibold text-slate-900 dark:text-slate-100">Tipo:</span> {{ $evento->tipoEvento?->tipo ?? 'Sin tipo' }}</p>
                                            <p><span class="font-semibold text-slate-900 dark:text-slate-100">Organiza:</span> {{ $evento->organizador }}</p>
                                            <p><span class="font-semibold text-slate-900 dark:text-slate-100">Modalidad:</span> {{ $evento->modalidad?->modalidad ?? 'Sin modalidad' }}</p>
                                            <p><span class="font-semibold text-slate-900 dark:text-slate-100">Localidad:</span> {{ $evento->localidad_display }}</p>
                                            <p><span class="font-semibold text-slate-900 dark:text-slate-100">Tipo de acceso:</span> {{ ucfirst($tipo_acceso) }}</p>
                                            <p><span class="font-semibold text-slate-900 dark:text-slate-100">Fechas:</span> {{ $evento->fechainicio?->format('d/m/Y') ?? 'Sin fecha' }} @if($evento->fechafinal) - {{ $evento->fechafinal->format('d/m/Y') }} @endif</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Conferencias y talleres</h3>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-900 dark:text-slate-300">{{ $evento->conferencias->count() }} registradas</span>
                                </div>
                                <div class="mt-4 space-y-3">
                                    @foreach ($evento->conferencias->sortBy(fn ($conference) => sprintf('%s %s', $conference->fecha, $conference->horaInicio)) as $conference)
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                                            <div class="flex flex-col gap-2 lg:flex-row lg:items-start lg:justify-between">
                                                <div>
                                                    <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $conference->nombre }}</p>
                                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $conference->descripcion }}</p>
                                                </div>
                                                <div class="text-sm text-slate-600 dark:text-slate-300 lg:text-right">
                                                    <p>{{ \Carbon\Carbon::parse($conference->fecha)->format('d/m/Y') }}</p>
                                                    <p>{{ substr((string) $conference->horaInicio, 0, 5) }} - {{ substr((string) $conference->horaFin, 0, 5) }}</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-600 dark:text-slate-300">
                                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Tipo:</span> {{ $conference->tipoConferencia?->tipo ?? 'Sin tipo' }}</p>
                                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Conferencista:</span> {{ $conference->conferencista_nombre_invitado ?: trim(($conference->speakerPersona?->nombre ?? '') . ' ' . ($conference->speakerPersona?->apellido ?? '')) ?: 'Pendiente' }}</p>
                                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Lugar:</span> {{ $conference->lugar }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($tipo_acceso === 'pagada')
                                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Precios por tipo de perfil</h3>
                                    <div class="mt-4 space-y-4">
                                        @foreach ($tiposPerfilCatalog as $tipoPerfil)
                                            @php
                                                $prices = $evento->precios->where('IdTipoPerfil', $tipoPerfil->id)->sortBy('orden')->values();
                                                $eventDayPrice = $prices->firstWhere('es_precio_evento_dia', true);
                                                $ranges = $prices->where('es_precio_evento_dia', false)->values();
                                            @endphp
                                            <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                                    <div>
                                                        <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $tipoPerfil->tipoperfil }}</p>
                                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Precio del día del evento: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $eventDayPrice ? trim(($eventDayPrice->moneda?->simbolo ? $eventDayPrice->moneda->simbolo . ' ' : '') . number_format((float) $eventDayPrice->precio, 2) . ($eventDayPrice->moneda?->codigo ? ' ' . $eventDayPrice->moneda->codigo : '')) : '0.00' }}</span></p>
                                                    </div>
                                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $ranges->count() }} rangos opcionales</span>
                                                </div>

                                                @if ($ranges->isNotEmpty())
                                                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                                                        @foreach ($ranges as $range)
                                                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-300">
                                                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $range->categoria_nombre }}</p>
                                                                <p class="mt-1">{{ $range->fecha_inicio?->format('d/m/Y') }} - {{ $range->fecha_fin?->format('d/m/Y') }}</p>
                                                                <p class="mt-1">Precio: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ trim(($range->moneda?->simbolo ? $range->moneda->simbolo . ' ' : '') . number_format((float) $range->precio, 2) . ($range->moneda?->codigo ? ' ' . $range->moneda->codigo : '')) }}</span></p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/60 dark:bg-emerald-950/30">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-emerald-900 dark:text-emerald-200">Publicación final</h3>
                                        <p class="mt-1 text-sm text-emerald-800 dark:text-emerald-300">Si toda la información es correcta, publica el evento para habilitarlo en la página principal y permitir las inscripciones.</p>
                                    </div>
                                    @if ($allConfigSectionsReady && $evento->estado !== 'publicado')
                                        <button type="button" wire:click="publish" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-yellow-400">
                                            <span wire:loading.remove wire:target="publish">Publicar evento</span>
                                            <span wire:loading wire:target="publish">Publicando...</span>
                                        </button>
                                    @else
                                        <div class="rounded-2xl bg-white px-4 py-3 text-sm font-medium text-emerald-700 dark:bg-slate-900 dark:text-emerald-300">
                                            {{ $evento->estado === 'publicado' ? 'El evento ya está publicado.' : 'Completa los pasos anteriores para poder publicar.' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($showPublishModal)
                        <div class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
                            <div class="w-full max-w-lg rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Confirmar publicación</h3>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Antes de continuar, verifica que toda la información del evento sea correcta.</p>
                                </div>

                                <div class="px-6 py-6">
                                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                                        Al confirmar, este evento se publicará en la página principal del sistema y permitirá que las personas puedan verlo e inscribirse según la configuración registrada.
                                    </div>

                                    <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-300">
                                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Evento:</span> {{ $evento->nombreevento }}</p>
                                        <p class="mt-2"><span class="font-semibold text-slate-900 dark:text-slate-100">Conferencias:</span> {{ $evento->conferencias->count() }}</p>
                                        <p class="mt-2"><span class="font-semibold text-slate-900 dark:text-slate-100">Tipo de acceso:</span> {{ ucfirst($tipo_acceso) }}</p>
                                        <p class="mt-2"><span class="font-semibold text-slate-900 dark:text-slate-100">Estado actual:</span> {{ $evento->estado === 'publicado' ? 'Publicado' : 'Pendiente de publicación' }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 dark:border-slate-800 sm:flex-row sm:justify-end">
                                    <button type="button" wire:click="closePublishModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                        Cancelar
                                    </button>
                                    <button type="button" wire:click="confirmPublish" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400">
                                        <span wire:loading.remove wire:target="confirmPublish">Confirmar y publicar</span>
                                        <span wire:loading wire:target="confirmPublish">Publicando...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-6 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            Paso {{ collect($sectionStatus)->search(fn (array $section) => $section['key'] === $activeSection) + 1 }} de {{ count($sectionStatus) }}
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            @if (collect($sectionStatus)->search(fn (array $section) => $section['key'] === $activeSection) > 0)
                                <button type="button" wire:click="previousSectionStep" class="inline-flex items-center justify-center rounded-2xl border border-emerald-500 bg-white px-5 py-3 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-50 dark:border-emerald-700 dark:bg-transparent dark:text-emerald-300 dark:hover:bg-emerald-950/20">
                                    Paso anterior
                                </button>
                            @endif

                            @if (collect($sectionStatus)->search(fn (array $section) => $section['key'] === $activeSection) < (count($sectionStatus) - 1))
                                <button type="button" wire:click="nextSectionStep" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                                    Siguiente paso
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
