<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Asistencia</p>
            <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">Eventos con control de acceso</h2>
            <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">Selecciona un evento para ver sus conferencias y registrar asistencia por medio del QR del gafete del participante.</p>
        </div>

        <div class="relative w-full lg:max-w-md">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por evento, organizador, lugar o tipo..."
                class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
            >
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($eventos as $evento)
            <article
                wire:click="openEvent({{ $evento->id }})"
                class="group cursor-pointer overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_25px_70px_rgba(15,23,42,0.08)] transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-[0_28px_80px_rgba(91,33,182,0.18)] dark:border-slate-800 dark:bg-slate-900 dark:hover:border-violet-500/60"
            >
                <div class="relative aspect-[16/8] overflow-hidden bg-slate-100 dark:bg-slate-950">
                    @if ($evento->banner_url)
                        <img src="{{ $evento->banner_url }}" alt="{{ $evento->nombreevento }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                    @elseif ($evento->logo_url)
                        <img src="{{ $evento->logo_url }}" alt="{{ $evento->nombreevento }}" class="h-full w-full object-contain p-8">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 via-white to-violet-50 text-xs font-semibold uppercase tracking-[0.26em] text-slate-400 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 dark:text-slate-500">
                            Sin imagen
                        </div>
                    @endif

                    <div class="absolute left-4 top-4 inline-flex items-center rounded-full bg-white/92 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-slate-700 shadow-sm dark:bg-slate-950/80 dark:text-slate-200">
                        {{ $evento->tipoEvento?->tipo ?? 'Evento' }}
                    </div>
                </div>

                <div class="space-y-4 p-5">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $evento->nombreevento }}</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $evento->organizador }}</p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Fechas</p>
                            <p class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">
                                {{ $evento->fechainicio?->format('d/m/Y') ?? 'Sin fecha' }} - {{ $evento->fechafinal?->format('d/m/Y') ?? 'Sin fecha' }}
                            </p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Conferencias</p>
                            <p class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $evento->conferencias_count }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl border border-violet-200 bg-violet-50/80 px-4 py-3 text-sm text-violet-700 dark:border-violet-500/30 dark:bg-violet-500/10 dark:text-violet-200">
                        <span>{{ $evento->modalidad?->modalidad ?? 'Modalidad por definir' }}</span>
                        <span class="font-semibold">Abrir</span>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-900">
                <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">No hay eventos disponibles para asistencia.</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Ajusta la búsqueda o verifica que el usuario haya sido agregado como staff del evento.</p>
            </div>
        @endforelse
    </div>

    <div>
        {{ $eventos->links() }}
    </div>
</div>
