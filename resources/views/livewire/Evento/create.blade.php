<div class="fixed inset-0 z-50 overflow-hidden">
    <div class="flex min-h-screen items-center justify-center px-4 py-4 sm:py-6">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeModal"></div>

        <div class="relative flex max-h-[calc(100vh-2rem)] w-full max-w-3xl flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:max-h-[calc(100vh-3rem)]">
            <div class="shrink-0 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-yellow-500">Fase 3</p>
                        <h3 class="mt-1 text-2xl font-semibold text-slate-900 dark:text-slate-100">Nuevo evento</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Registra solo la informacion minima. La configuracion completa y la publicacion se hacen en el siguiente paso.
                        </p>
                    </div>

                    <button
                        type="button"
                        wire:click="closeModal"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <form wire:submit.prevent="store" class="flex min-h-0 flex-1 flex-col">
                <div class="min-h-0 flex-1 overflow-y-auto px-6 py-6">
                    <div class="grid gap-5">
                        <div>
                            <label for="nombreevento" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre del evento <span class="text-red-500">*</span></label>
                            <input
                                id="nombreevento"
                                type="text"
                                wire:model.live="nombreevento"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                placeholder="Ej. Jornada de Innovacion UNAH"
                            >
                            @error('nombreevento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="descripcion" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Descripcion <span class="text-red-500">*</span></label>
                            <textarea
                                id="descripcion"
                                rows="5"
                                wire:model.live="descripcion"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                placeholder="Resume el objetivo y alcance del evento."
                            ></textarea>
                            @error('descripcion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="organizador" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Organizador <span class="text-red-500">*</span></label>
                                <input
                                    id="organizador"
                                    type="text"
                                    wire:model.live="organizador"
                                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                    placeholder="Unidad o responsable"
                                >
                                @error('organizador') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tipo_conferencia_id" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tipo de evento <span class="text-red-500">*</span></label>
                                <select
                                    id="tipo_conferencia_id"
                                    wire:model.live="tipo_conferencia_id"
                                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                >
                                    <option value="">Selecciona un tipo de evento</option>
                                    @foreach ($tiposConferencias as $tipoConferencia)
                                        <option value="{{ $tipoConferencia->id }}">{{ $tipoConferencia->tipo }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_conferencia_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="modalidadSelect" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Modalidad <span class="text-red-500">*</span></label>
                                <select
                                    id="modalidadSelect"
                                    wire:model.live="idmodalidad"
                                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                >
                                    <option value="">Selecciona una modalidad</option>
                                    @foreach ($modalidades as $modalidad)
                                        <option value="{{ $modalidad->id }}">{{ $modalidad->modalidad }}</option>
                                    @endforeach
                                </select>
                                @error('idmodalidad') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="fechainicio" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Fecha de inicio <span class="text-red-500">*</span></label>
                                <input
                                    id="fechainicio"
                                    type="date"
                                    wire:model.live="fechainicio"
                                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                >
                                @error('fechainicio') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="fechafinal" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Fecha final <span class="text-red-500">*</span></label>
                                <input
                                    id="fechafinal"
                                    type="date"
                                    wire:model.live="fechafinal"
                                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                >
                                @error('fechafinal') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-800 dark:bg-slate-950/60">
                            <span class="font-medium text-slate-700 dark:text-slate-200">Cantidad de días del evento:</span>
                            <span class="ml-2 text-slate-600 dark:text-slate-300">
                                {{ $this->eventDurationDays ? $this->eventDurationDays . ' ' . \Illuminate\Support\Str::plural('día', $this->eventDurationDays) : 'Define ambas fechas para calcularlo.' }}
                            </span>
                        </div>

                        <div>
                            <label for="localidad" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Localidad <span class="text-red-500">*</span></label>
                            <input
                                id="localidad"
                                type="text"
                                wire:model.live="localidad_nombre"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                placeholder="Ej. Auditorio Alma Mater, Zoom o Centro de Convenciones"
                            >
                            @error('localidad_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="tipo_acceso" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tipo de acceso <span class="text-red-500">*</span></label>
                                <select
                                    id="tipo_acceso"
                                    wire:model.live="tipo_acceso"
                                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                >
                                    <option value="gratuita">Gratuita</option>
                                    <option value="pagada">Pagada</option>
                                </select>
                                @error('tipo_acceso') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="genera_diploma_participacion" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Genera diploma de participación <span class="text-red-500">*</span></label>
                                <select
                                    id="genera_diploma_participacion"
                                    wire:model.live="genera_diploma_participacion"
                                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                >
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                                @error('genera_diploma_participacion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 border-t border-slate-200 bg-white px-6 py-5 dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400 disabled:cursor-not-allowed disabled:opacity-70"
                        >
                            <span wire:loading.remove wire:target="store">Crear y configurar</span>
                            <span wire:loading wire:target="store">Procesando...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
