<div class="space-y-6">
    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Mis pagos</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Historial de pagos realizados</h1>
            </div>

            <div class="w-full max-w-md">
                <label for="search-payments" class="sr-only">Buscar pagos</label>
                <input
                    id="search-payments"
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por evento o referencia"
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
                        <th class="px-6 py-4">Perfil</th>
                        <th class="px-6 py-4">Método</th>
                        <th class="px-6 py-4">Monto</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4">Referencia</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 text-sm text-slate-700 dark:divide-white/6 dark:text-slate-200">
                    @forelse ($pagos as $pago)
                        <tr class="align-top">
                            <td class="px-6 py-5">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $pago->evento?->nombreevento ?? 'Evento no disponible' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ optional($pago->created_at)->format('d/m/Y h:i A') }}</p>
                            </td>
                            <td class="px-6 py-5">{{ $pago->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</td>
                            <td class="px-6 py-5">{{ $pago->metodoPago?->nombre ?? 'No definido' }}</td>
                            <td class="px-6 py-5">{{ $pago->formattedPrecioAplicado(true) }}</td>
                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ ($pago->estado_pago ?? 'pendiente') === 'pagado' ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300' : 'bg-amber-500/10 text-amber-700 dark:text-amber-300' }}">
                                    {{ $pago->estado_pago ?? 'pendiente' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">{{ $pago->referencia_pago ?: 'Sin referencia' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">No tienes pagos registrados.</p>
                                <p class="mt-2 text-sm text-slate-500">Los pagos de tus inscripciones aparecerán aquí.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pagos->hasPages())
            <div class="border-t border-slate-200/80 px-6 py-4 dark:border-white/8">
                {{ $pagos->links() }}
            </div>
        @endif
    </section>
</div>
