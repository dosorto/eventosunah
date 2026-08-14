<div class="space-y-6">
    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Pagos</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Gestión de cobros e inscripciones pagadas</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-500">Selecciona un evento para registrar pagos manuales, revisar comprobantes de transferencia o aprobar cobros pendientes.</p>
            </div>

            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_220px]">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por evento, organizador o lugar..."
                    class="h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100"
                >
                <select
                    wire:model.live="statusFilter"
                    class="h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100"
                >
                    <option value="todos">Todos</option>
                    <option value="publicados">Publicados</option>
                    <option value="pagados">De pago</option>
                    <option value="gratuitos">Gratuitos</option>
                </select>
            </div>
        </div>
    </section>

    <section class="grid gap-5 md:grid-cols-2 2xl:grid-cols-3">
        @forelse ($events as $event)
            <a href="{{ route('pagos.evento', $event) }}" class="group rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-[0_28px_90px_rgba(109,67,255,0.16)] dark:border-white/8 dark:bg-[#10131c]/92">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $event->tipo_acceso === 'pagada' ? 'Evento de pago' : 'Evento gratuito' }}</p>
                        <h2 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">{{ $event->nombreevento }}</h2>
                    </div>
                    <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-violet-700 dark:bg-violet-950/40 dark:text-violet-300">
                        {{ $event->estado }}
                    </span>
                </div>

                <div class="mt-4 flex flex-wrap gap-3 text-sm text-slate-500">
                    <span>{{ $event->organizador }}</span>
                    <span>·</span>
                    <span>{{ $event->localidad_display }}</span>
                </div>

                <dl class="mt-6 grid grid-cols-3 gap-3">
                    <div class="rounded-3xl border border-slate-200/80 bg-slate-50/90 p-4 text-center dark:border-white/8 dark:bg-white/[0.03]">
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Registros</dt>
                        <dd class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $event->registros_count }}</dd>
                    </div>
                    <div class="rounded-3xl border border-slate-200/80 bg-amber-50/90 p-4 text-center dark:border-amber-900/40 dark:bg-amber-950/20">
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-700 dark:text-amber-300">Pendientes</dt>
                        <dd class="mt-2 text-2xl font-semibold text-amber-700 dark:text-amber-200">{{ $event->pagos_pendientes_count }}</dd>
                    </div>
                    <div class="rounded-3xl border border-slate-200/80 bg-emerald-50/90 p-4 text-center dark:border-emerald-900/40 dark:bg-emerald-950/20">
                        <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Validados</dt>
                        <dd class="mt-2 text-2xl font-semibold text-emerald-700 dark:text-emerald-200">{{ $event->pagos_aprobados_count }}</dd>
                    </div>
                </dl>

                <div class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-violet-700 dark:text-violet-300">
                    Abrir gestión de pagos
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.22 4.22a.75.75 0 0 1 1.06 0l5 5a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 1 1-1.06-1.06l3.72-3.72H3a.75.75 0 0 1 0-1.5h11.94l-3.72-3.72a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </a>
        @empty
            <div class="md:col-span-2 2xl:col-span-3 rounded-[32px] border border-dashed border-slate-300 bg-white/80 p-12 text-center dark:border-white/10 dark:bg-[#10131c]/80">
                <p class="text-lg font-semibold text-slate-950 dark:text-white">No hay eventos para mostrar.</p>
                <p class="mt-2 text-sm text-slate-500">Ajusta la búsqueda o publica un evento para comenzar a gestionar pagos.</p>
            </div>
        @endforelse
    </section>

    @if ($events->hasPages())
        <section class="rounded-[32px] border border-slate-200/80 bg-white/90 px-6 py-4 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
            {{ $events->links() }}
        </section>
    @endif
</div>
