<div class="space-y-6">
    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Mis certificados</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Diplomas y constancias disponibles</h1>
            </div>

            <div class="w-full max-w-md">
                <label for="search-certificates" class="sr-only">Buscar certificados</label>
                <input
                    id="search-certificates"
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por evento o conferencia"
                    class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100"
                >
            </div>
        </div>
    </section>

    <section class="grid gap-5 lg:grid-cols-2">
        @forelse ($certificados as $certificado)
            <article class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_20px_70px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Certificado</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">{{ $certificado->evento?->nombreevento ?? 'Evento no disponible' }}</h2>
                        <p class="mt-2 text-sm text-slate-500">
                            {{ $certificado->tipo === 'participacion_general' ? 'Participación general del evento' : ($certificado->conferencia?->nombre ?? 'Conferencia no disponible') }}
                        </p>
                    </div>

                    <span class="inline-flex rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-300">
                        Disponible
                    </span>
                </div>

                <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Generado</dt>
                        <dd class="mt-1 text-sm text-slate-700 dark:text-slate-200">
                            {{ $certificado->generated_at?->format('d/m/Y h:i a') ?: 'Sin fecha' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Hash único</dt>
                        <dd class="mt-1 text-sm text-slate-700 dark:text-slate-200">
                            <span class="font-mono">{{ $certificado->hash_unico }}</span>
                        </dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <button
                        type="button"
                        wire:click="downloadCertificate({{ $certificado->id }})"
                        class="inline-flex items-center justify-center rounded-2xl bg-[#7b5cff] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#6546ff]"
                    >
                        Descargar PDF
                    </button>
                </div>
            </article>
        @empty
            <div class="rounded-[32px] border border-dashed border-slate-300 bg-white/80 px-6 py-14 text-center text-slate-500 lg:col-span-2 dark:border-white/10 dark:bg-white/[0.02]">
                <p class="text-lg font-semibold text-slate-900 dark:text-white">No tienes certificados disponibles por ahora.</p>
                <p class="mt-2 text-sm">Aquí aparecerán cuando el organizador genere tus certificados oficiales en PDF.</p>
            </div>
        @endforelse
    </section>

    @if ($certificados->hasPages())
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 px-6 py-4 shadow-sm dark:border-white/8 dark:bg-[#10131c]/92">
            {{ $certificados->links() }}
        </div>
    @endif
</div>
