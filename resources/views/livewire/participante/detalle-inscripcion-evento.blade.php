<div class="space-y-6">
    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Mi inscripción</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">{{ $registro->evento?->nombreevento ?? 'Evento' }}</h1>
                <p class="mt-3 text-sm text-slate-500">{{ $registro->evento?->organizador }} · {{ $registro->evento?->tipoEvento?->tipo ?? 'Tipo por definir' }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('mi-historial') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-slate-200 dark:hover:bg-white/5">
                    Volver a mis inscripciones
                </a>
                @if ($badgeAvailable)
                    <a href="{{ route('mi-historial.gafete', $registro) }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(109,67,255,0.28)] transition hover:scale-[1.01]">
                        Descargar gafete
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
        <article class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Detalle del evento</h2>
            <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl border border-slate-200/80 bg-slate-50/90 p-4 dark:border-white/8 dark:bg-white/[0.03]">
                    <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Fecha</dt>
                    <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">
                        {{ $registro->evento?->fechainicio?->format('d/m/Y') ?? 'Sin fecha' }}
                        @if($registro->evento?->fechafinal)
                            <span class="font-normal text-slate-500">- {{ $registro->evento->fechafinal->format('d/m/Y') }}</span>
                        @endif
                    </dd>
                </div>
                <div class="rounded-3xl border border-slate-200/80 bg-slate-50/90 p-4 dark:border-white/8 dark:bg-white/[0.03]">
                    <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Modalidad</dt>
                    <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $registro->evento?->modalidad?->modalidad ?? 'Por definir' }}</dd>
                </div>
                <div class="rounded-3xl border border-slate-200/80 bg-slate-50/90 p-4 dark:border-white/8 dark:bg-white/[0.03]">
                    <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Lugar</dt>
                    <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $registro->evento?->localidad_display ?? 'Por definir' }}</dd>
                </div>
                <div class="rounded-3xl border border-slate-200/80 bg-slate-50/90 p-4 dark:border-white/8 dark:bg-white/[0.03]">
                    <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Perfil aplicado</dt>
                    <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $registro->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</dd>
                </div>
            </dl>

            @if($registro->evento?->descripcion)
                <div class="mt-6 rounded-3xl border border-slate-200/80 bg-slate-50/90 p-5 dark:border-white/8 dark:bg-white/[0.03]">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Descripción</p>
                    <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $registro->evento->descripcion }}</p>
                </div>
            @endif
        </article>

        <aside class="space-y-6">
            <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Pago e inscripción</p>
                <div class="mt-4 space-y-4">
                    <div>
                        <p class="text-sm text-slate-500">Estado de inscripción</p>
                        <p class="mt-1 text-lg font-semibold text-slate-950 dark:text-white">{{ ucfirst(str_replace('_', ' ', $registro->estado ?? 'registrado')) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Estado de pago</p>
                        <p class="mt-1 text-lg font-semibold {{ in_array($registro->estado_pago, ['pagado', 'no_aplica'], true) ? 'text-emerald-600 dark:text-emerald-300' : 'text-amber-600 dark:text-amber-300' }}">
                            {{ ucfirst(str_replace('_', ' ', $registro->estado_pago ?? 'pendiente')) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Monto</p>
                        <p class="mt-1 text-lg font-semibold text-slate-950 dark:text-white">
                            @if((float) $registro->precio_aplicado > 0)
                                {{ $registro->formattedPrecioAplicado(true) }}
                            @else
                                Gratuita
                            @endif
                        </p>
                        <p class="mt-1 text-sm text-slate-500">{{ $registro->detalle_precio ?: 'Sin detalle de tarifa' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Método de pago</p>
                        <p class="mt-1 text-base font-semibold text-slate-950 dark:text-white">{{ $registro->metodoPago?->nombre ?? ($registro->estado_pago === 'no_aplica' ? 'No aplica' : 'Pendiente de definir') }}</p>
                    </div>
                    @if($registro->referencia_pago)
                        <div>
                            <p class="text-sm text-slate-500">Referencia</p>
                            <p class="mt-1 text-base font-semibold text-slate-950 dark:text-white">{{ $registro->referencia_pago }}</p>
                        </div>
                    @endif
                    @if($registro->pagado_en)
                        <div>
                            <p class="text-sm text-slate-500">Pago validado</p>
                            <p class="mt-1 text-base font-semibold text-slate-950 dark:text-white">{{ $registro->pagado_en->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </section>

            @if ($proofUrl)
                <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Comprobante cargado</p>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $registro->comprobante_pago_nombre ?? 'Comprobante disponible' }}</p>
                    <a href="{{ $proofUrl }}" target="_blank" class="mt-4 inline-flex items-center justify-center rounded-full border border-violet-300 px-5 py-3 text-sm font-semibold text-violet-700 transition hover:bg-violet-50 dark:border-violet-700 dark:text-violet-300 dark:hover:bg-violet-950/30">
                        Ver comprobante
                    </a>
                </section>
            @endif
        </aside>
    </section>
</div>
