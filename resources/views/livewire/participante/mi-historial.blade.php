<div class="space-y-6">
    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Mis inscripciones</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Eventos en los que estoy registrado</h1>
            </div>

            <div class="w-full max-w-md">
                <label for="search-history" class="sr-only">Buscar historial</label>
                <input
                    id="search-history"
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por evento, organizador o perfil"
                    class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100"
                >
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-[32px] border border-slate-200/80 bg-white/90 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-white/10">
                <thead class="bg-slate-50/90 dark:bg-white/[0.03]">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                        <th class="px-6 py-4">Evento</th>
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Modalidad</th>
                        <th class="px-6 py-4">Perfil</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 text-sm text-slate-700 dark:divide-white/6 dark:text-slate-200">
                    @forelse ($registros as $registro)
                        <tr class="align-top">
                            <td class="px-6 py-5">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $registro->evento?->nombreevento ?? 'Evento no disponible' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $registro->evento?->organizador ?? 'Organizador no disponible' }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p>{{ optional($registro->evento?->fechainicio)?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                                @if($registro->evento?->fechafinal)
                                    <p class="mt-1 text-xs text-slate-500">Finaliza {{ $registro->evento->fechafinal->format('d/m/Y') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-5">{{ $registro->evento?->modalidad?->modalidad ?? 'Por definir' }}</td>
                            <td class="px-6 py-5">{{ $registro->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</td>
                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-300">
                                    {{ $registro->estado ?? 'Registrado' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex flex-col items-end gap-2 sm:flex-row sm:justify-end">
                                    @if($badgeService->isAvailable($registro))
                                        <a
                                            href="{{ route('mi-historial.gafete', ['registro' => $registro, 'inline' => 1]) }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                                        >
                                            Gafete
                                        </a>
                                    @else
                                        <span
                                            title="El gafete estará disponible cuando la inscripción y el pago estén confirmados."
                                            class="inline-flex cursor-not-allowed items-center justify-center rounded-full border border-slate-200 bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-400 dark:border-white/10 dark:bg-white/5 dark:text-slate-500"
                                        >
                                            Gafete pendiente
                                        </span>
                                    @endif

                                    <a
                                        href="{{ route('mi-historial.detalle', $registro) }}"
                                        class="inline-flex items-center justify-center rounded-full border border-violet-300 px-4 py-2 text-xs font-semibold text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                                    >
                                        Ver detalle
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">Aún no tienes historial registrado.</p>
                                <p class="mt-2 text-sm text-slate-500">Cuando te inscribas y participes en eventos, aparecerán aquí.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($registros->hasPages())
            <div class="border-t border-slate-200/80 px-6 py-4 dark:border-white/8">
                {{ $registros->links() }}
            </div>
        @endif
    </section>
</div>
