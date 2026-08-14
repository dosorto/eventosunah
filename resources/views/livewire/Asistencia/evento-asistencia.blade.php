<div class="space-y-6">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Asistencia del evento</p>
            <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $evento->nombreevento }}</h2>
            <div class="mt-3 flex flex-wrap gap-3 text-sm text-slate-500 dark:text-slate-400">
                <span>{{ $evento->tipoEvento?->tipo ?? 'Evento' }}</span>
                <span>{{ $evento->modalidad?->modalidad ?? 'Modalidad' }}</span>
                <span>{{ $evento->localidad_display }}</span>
                <span>{{ $evento->fechainicio?->format('d/m/Y') ?? 'Sin fecha' }} - {{ $evento->fechafinal?->format('d/m/Y') ?? 'Sin fecha' }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <div class="relative min-w-[18rem]">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar conferencia..."
                    class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                >
            </div>

            <a
                href="{{ route('asistencia') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
            >
                Volver a eventos
            </a>
        </div>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_25px_70px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Conferencias y talleres</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Selecciona la actividad donde vas a registrar la asistencia con QR.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-800 dark:bg-slate-950/60">
                <span class="text-slate-500 dark:text-slate-400">Total:</span>
                <span class="ml-2 font-semibold text-slate-900 dark:text-slate-100">{{ $totalConferencias }}</span>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            @forelse ($conferenciasPorDia as $dateKey => $conferencias)
                @php
                    $firstConference = $conferencias->first();
                    $dayLabel = $dateKey !== 'sin-fecha' && $firstConference?->fecha
                        ? \Illuminate\Support\Carbon::parse($firstConference->fecha)->translatedFormat('l d \\d\\e F \\d\\e Y')
                        : 'Sin fecha configurada';
                @endphp

                <section class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3 dark:border-slate-800">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-500">Jornada</p>
                            <h4 class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $dayLabel }}</h4>
                        </div>
                        <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700 dark:bg-violet-500/10 dark:text-violet-200">
                            {{ $conferencias->count() }} actividades
                        </span>
                    </div>

                    <div class="grid gap-4 xl:grid-cols-2">
                        @foreach ($conferencias as $conference)
                            <article class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-sky-700 dark:bg-sky-500/10 dark:text-sky-200">
                                                {{ $conference->tipoConferencia?->tipo ?? 'Conferencia' }}
                                            </span>
                                            <span class="inline-flex rounded-full bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-600 shadow-sm dark:bg-slate-900 dark:text-slate-300">
                                                {{ $conference->horaInicio ? \Illuminate\Support\Carbon::parse($conference->horaInicio)->format('h:i a') : '--' }} - {{ $conference->horaFin ? \Illuminate\Support\Carbon::parse($conference->horaFin)->format('h:i a') : '--' }}
                                            </span>
                                        </div>
                                        <h5 class="mt-3 text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $conference->nombre }}</h5>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::words($conference->descripcion ?: 'Sin descripción disponible.', 20, '...') }}</p>
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="openConference({{ $conference->id }})"
                                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-violet-500"
                                    >
                                        Pasar asistencia
                                    </button>
                                </div>

                                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Lugar</p>
                                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $conference->lugar ?: 'Por definir' }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Conferencista</p>
                                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ trim(($conference->speakerPersona?->nombre ?? '') . ' ' . ($conference->speakerPersona?->apellido ?? '')) ?: ($conference->conferencista_nombre_invitado ?: 'Pendiente') }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Asistencias</p>
                                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $conference->registro_asistencias_count }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-[1.75rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center dark:border-slate-700 dark:bg-slate-950/40">
                    <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">No hay conferencias disponibles.</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Prueba con otra búsqueda o revisa la agenda configurada del evento.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
