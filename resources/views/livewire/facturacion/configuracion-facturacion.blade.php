<div class="space-y-6">
    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Configuración</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Facturación</h1>
        <p class="mt-3 max-w-3xl text-sm text-slate-500">
            Define los datos fiscales de Honduras para poder emitir facturas desde el módulo de pagos. Si esta configuración no existe o el rango autorizado se agota, el sistema bloqueará la generación de facturas.
        </p>
    </section>

    <section class="grid gap-4 lg:grid-cols-4">
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-white/8 dark:bg-[#10131c]/92">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Estado</p>
            <p class="mt-3 text-2xl font-semibold {{ ($config?->is_active ?? false) ? 'text-emerald-600 dark:text-emerald-300' : 'text-amber-600 dark:text-amber-300' }}">
                {{ ($config?->is_active ?? false) ? 'Activa' : 'Inactiva' }}
            </p>
        </div>
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-white/8 dark:bg-[#10131c]/92">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">CAI</p>
            <p class="mt-3 truncate text-sm font-semibold text-slate-950 dark:text-white">{{ $config?->cai ?: 'Sin definir' }}</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-white/8 dark:bg-[#10131c]/92">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Siguiente número</p>
            <p class="mt-3 text-2xl font-semibold text-slate-950 dark:text-white">{{ $config?->siguiente_numero ?: '—' }}</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-white/8 dark:bg-[#10131c]/92">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Vence</p>
            <p class="mt-3 text-2xl font-semibold text-slate-950 dark:text-white">{{ $config?->fecha_limite_emision?->format('d/m/Y') ?: '—' }}</p>
        </div>
    </section>

    <section class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-white/8 dark:bg-[#10131c]/92">
        <form wire:submit="save" class="space-y-6">
            <div class="grid gap-5 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Razón social <span class="text-red-500">*</span></label>
                    <input wire:model.defer="razon_social" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('razon_social') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">RTN emisor <span class="text-red-500">*</span></label>
                    <input wire:model.defer="rtn_emisor" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('rtn_emisor') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">CAI <span class="text-red-500">*</span></label>
                    <input wire:model.defer="cai" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('cai') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Establecimiento <span class="text-red-500">*</span></label>
                    <input wire:model.defer="establecimiento_codigo" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('establecimiento_codigo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Punto de emisión <span class="text-red-500">*</span></label>
                    <input wire:model.defer="punto_emision_codigo" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('punto_emision_codigo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Tipo de documento <span class="text-red-500">*</span></label>
                    <input wire:model.defer="tipo_documento_codigo" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('tipo_documento_codigo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Fecha límite de emisión <span class="text-red-500">*</span></label>
                    <input wire:model.defer="fecha_limite_emision" type="date" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('fecha_limite_emision') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Rango inicio <span class="text-red-500">*</span></label>
                    <input wire:model.defer="rango_inicio" type="number" min="1" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('rango_inicio') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Rango fin <span class="text-red-500">*</span></label>
                    <input wire:model.defer="rango_fin" type="number" min="1" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('rango_fin') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Siguiente número <span class="text-red-500">*</span></label>
                    <input wire:model.defer="siguiente_numero" type="number" min="1" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('siguiente_numero') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Teléfono fiscal</label>
                    <input wire:model.defer="telefono_fiscal" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('telefono_fiscal') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Correo fiscal</label>
                    <input wire:model.defer="correo_fiscal" type="email" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('correo_fiscal') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Dirección fiscal</label>
                    <input wire:model.defer="direccion_fiscal" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100">
                    @error('direccion_fiscal') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Leyenda / observación fiscal</label>
                    <textarea wire:model.defer="leyenda" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#7b5cff] focus:ring-4 focus:ring-[#7b5cff]/10 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-100"></textarea>
                    @error('leyenda') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-200">
                <input wire:model.defer="is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#7b5cff] focus:ring-[#7b5cff]">
                Configuración activa
            </label>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#6d43ff] to-[#9f7aea] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(109,67,255,0.22)] transition hover:scale-[1.01]">
                    Guardar configuración
                </button>
            </div>
        </form>
    </section>
</div>
