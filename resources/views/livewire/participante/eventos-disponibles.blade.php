<div class="space-y-6">
    <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0_25px_70px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-violet-600 dark:text-violet-300">Catálogo interno</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">Eventos</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Explora los eventos publicados sin salir del sistema y completa tu inscripción desde aquí.</p>
            </div>

            <div class="w-full max-w-2xl">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="relative min-w-0 flex-1">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                        </svg>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Buscar por nombre, organizador, lugar o tipo..."
                            class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-5">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($eventos as $evento)
                @php
                    $registered = in_array($evento->id, $registeredEventIds, true);
                    $description = trim((string) $evento->descripcion);
                    $words = preg_split('/\s+/', $description, -1, PREG_SPLIT_NO_EMPTY) ?: [];
                    $shortDescription = count($words) > 10 ? implode(' ', array_slice($words, 0, 10)) . '...' : $description;
                    $banner = $evento->banner_url ?: ($evento->logo_url ?: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80');
                @endphp

                <article
                    role="link"
                    tabindex="0"
                    aria-label="Ver detalle de {{ $evento->nombreevento }}"
                    onclick="window.location.href='{{ route('evento', ['evento' => $evento->id]) }}'"
                    onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href='{{ route('evento', ['evento' => $evento->id]) }}'; }"
                    class="group cursor-pointer overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_25px_70px_rgba(15,23,42,0.08)] transition focus:outline-none focus:ring-4 focus:ring-violet-500/20 hover:-translate-y-1 hover:border-violet-300 hover:shadow-[0_34px_100px_rgba(124,58,237,0.22)] dark:border-slate-800 dark:bg-slate-900 dark:hover:border-violet-500/40"
                >
                    <div class="relative aspect-[16/9] overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img src="{{ $banner }}" alt="{{ $evento->nombreevento }}" class="h-full w-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/65 via-slate-950/10 to-transparent"></div>

                        <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                            <span class="rounded-full bg-white/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-700">
                                {{ $evento->tipoEvento?->tipo ?? 'Evento' }}
                            </span>
                            @if ($registered)
                                <span class="rounded-full bg-emerald-500/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white">
                                    Ya inscrito
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="flex flex-wrap gap-3 text-xs font-medium text-slate-500 dark:text-slate-400">
                            <span>{{ optional($evento->fechainicio)->format('d/m/Y') ?: 'Sin fecha' }}</span>
                            <span>{{ $evento->modalidad?->modalidad ?? 'Sin modalidad' }}</span>
                            <span>{{ $evento->localidad_display }}</span>
                        </div>

                        <div class="space-y-2">
                            <h2 class="text-xl font-semibold leading-tight text-slate-900 transition group-hover:text-violet-600 dark:text-white dark:group-hover:text-violet-300">{{ $evento->nombreevento }}</h2>
                            <p class="text-sm leading-6 text-slate-600 dark:text-slate-300" title="{{ $description }}">
                                {{ $shortDescription !== '' ? $shortDescription : 'Sin descripción disponible.' }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-slate-900 dark:text-slate-100">{{ $evento->organizador ?: 'Organizador no definido' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $evento->tipo_acceso === 'pagada' ? 'Evento pagado' : 'Evento gratuito' }}</p>
                            </div>

                            <a
                                href="{{ route('evento', ['evento' => $evento->id]) }}"
                                class="inline-flex shrink-0 items-center justify-center rounded-2xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-500"
                                onclick="event.stopPropagation()"
                            >
                                {{ $registered ? 'Ver detalle' : 'Inscribirme' }}
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="md:col-span-2 xl:col-span-3">
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-400">
                        No se encontraron eventos publicados con ese criterio de búsqueda.
                    </div>
                </div>
            @endforelse
        </div>

        <div>
            {{ $eventos->links() }}
        </div>
    </section>
</div>
