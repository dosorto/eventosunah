<div class="space-y-6">
    <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-[#12131d]">
        <div class="max-w-3xl">
            <h2 class="text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">Métodos de pago</h2>
            <p class="mt-2 text-sm leading-7 text-slate-500 dark:text-slate-400">
                El sistema manejará únicamente tres métodos fijos: efectivo, transferencia bancaria y tarjeta en línea.
                Desde aquí solo se habilitan o deshabilitan y se completa la información operativa necesaria.
            </p>
        </div>
    </section>

    @if (session()->has('message'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    <section class="grid gap-5 xl:grid-cols-3">
        @foreach ($metodosPago as $metodo)
            <article class="rounded-[2rem] border {{ $metodo->is_active ? 'border-violet-200 bg-violet-50/60 dark:border-violet-500/30 dark:bg-violet-500/10' : 'border-slate-200 bg-white dark:border-white/10 dark:bg-[#12131d]' }} p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{{ strtoupper($metodo->tipo) }}</p>
                        <h3 class="mt-3 text-xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">{{ $metodo->nombre }}</h3>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $metodo->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">
                        {{ $metodo->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <p class="mt-4 text-sm leading-7 text-slate-500 dark:text-slate-400">
                    {{ $metodo->descripcion ?: 'Sin descripción configurada.' }}
                </p>

                <div class="mt-5 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @if ($metodo->isCash())
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-slate-950/40">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Documento al cobrar</p>
                            <p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ ucfirst($metodo->documento_cobro_tipo ?: 'recibo') }}</p>
                        </div>
                    @endif

                    @if ($metodo->isTransfer())
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-slate-950/40">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Banco</p>
                            <p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $metodo->banco_nombre ?: 'Pendiente' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-slate-950/40">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Cuenta / titular</p>
                            <p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $metodo->numero_cuenta ?: 'Pendiente' }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $metodo->titular_cuenta ?: 'Titular pendiente' }}</p>
                        </div>
                    @endif

                    @if ($metodo->isCard())
                        <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-3 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                            Método reservado para integración futura con pasarela de pago.
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <button wire:click="openConfig({{ $metodo->id }})" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Configurar
                    </button>
                </div>
            </article>
        @endforeach
    </section>

    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
            <div class="max-h-full w-full max-w-3xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-violet-600 dark:text-violet-300">{{ strtoupper($tipo) }}</p>
                        <h3 class="mt-2 text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">{{ $nombre }}</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Actualiza la configuración operativa visible durante el cobro o validación.</p>
                    </div>
                    <button wire:click="closeModal" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/></svg>
                    </button>
                </div>

                <div class="space-y-5 px-6 py-6">
                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 dark:border-slate-700 dark:text-slate-200">
                        <input wire:model.live="is_active" type="checkbox" class="rounded border-slate-300 text-violet-600 focus:ring-violet-400">
                        Método habilitado para nuevas inscripciones
                    </label>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Descripción visible</label>
                        <textarea wire:model.live="descripcion" rows="3" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white"></textarea>
                        @error('descripcion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @if ($tipo === 'efectivo')
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Documento al cobrar <span class="text-red-500">*</span></label>
                                <select wire:model.live="documento_cobro_tipo" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                    <option value="recibo">Recibo</option>
                                    <option value="factura">Factura</option>
                                </select>
                                @error('documento_cobro_tipo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400">
                                El participante podrá reservar su lugar y el cobro quedará pendiente para ejecutarse presencialmente dentro del evento.
                            </div>
                        </div>
                    @endif

                    @if ($tipo === 'transferencia')
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Banco <span class="text-red-500">*</span></label>
                                <input wire:model.live="banco_nombre" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('banco_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Número de cuenta <span class="text-red-500">*</span></label>
                                <input wire:model.live="numero_cuenta" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('numero_cuenta') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Titular o beneficiario <span class="text-red-500">*</span></label>
                                <input wire:model.live="titular_cuenta" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                @error('titular_cuenta') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Datos para transferencias internacionales</label>
                                <textarea wire:model.live="detalle_transferencia_internacional" rows="4" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white" placeholder="SWIFT, IBAN, dirección del banco, observaciones adicionales..."></textarea>
                                @error('detalle_transferencia_internacional') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    @if ($tipo === 'tarjeta')
                        <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-4 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                            Este método queda reservado para la futura integración online. Por ahora puede dejarse inactivo para que no se muestre al participante.
                        </div>
                    @endif

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Instrucciones visibles para el participante</label>
                        <textarea wire:model.live="instrucciones" rows="4" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white" placeholder="Indicaciones adicionales para completar el pago..."></textarea>
                        @error('instrucciones') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 dark:border-slate-800 sm:flex-row sm:justify-end">
                    <button wire:click="closeModal" type="button" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Cancelar
                    </button>
                    <button wire:click="save" type="button" class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-violet-500">
                        Guardar configuración
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
