<div
    x-data
    x-on:open-external-link.window="
        if ($event.detail.target === 'blank') {
            window.open($event.detail.url, '_blank', 'noopener');
        } else {
            window.location.href = $event.detail.url;
        }
    "
    @if ($hasActiveDocumentTasks)
        wire:poll.1500ms
    @endif
    class="space-y-6"
>
    @if (session()->has('message') || session()->has('error'))
        <div class="pointer-events-none fixed right-4 top-4 z-[90] flex w-[min(24rem,calc(100vw-2rem))] flex-col gap-3">
            @if (session()->has('message'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-show="show"
                    x-transition.opacity.duration.300ms
                    class="pointer-events-auto rounded-[1.5rem] border border-emerald-200 bg-white/95 px-4 py-4 shadow-[0_24px_60px_rgba(16,185,129,0.18)] backdrop-blur dark:border-emerald-900/60 dark:bg-slate-900/95"
                >
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.25 7.313a1 1 0 0 1-1.42-.003L3.29 9.204a1 1 0 0 1 1.42-1.407l4.04 4.078 6.54-6.59a1 1 0 0 1 1.414.005Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Operación completada</p>
                            <p class="mt-1 text-sm leading-6 text-slate-700 dark:text-slate-200">{{ session('message') }}</p>
                        </div>
                        <button
                            type="button"
                            x-on:click="show = false"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        >
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 6500)"
                    x-show="show"
                    x-transition.opacity.duration.300ms
                    class="pointer-events-auto rounded-[1.5rem] border border-rose-200 bg-white/95 px-4 py-4 shadow-[0_24px_60px_rgba(244,63,94,0.18)] backdrop-blur dark:border-rose-900/60 dark:bg-slate-900/95"
                >
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:text-rose-300">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-.75-11a.75.75 0 0 1 1.5 0v3.75a.75.75 0 0 1-1.5 0V7Zm.75 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-rose-700 dark:text-rose-300">Revisa la acción solicitada</p>
                            <p class="mt-1 text-sm leading-6 text-slate-700 dark:text-slate-200">{{ session('error') }}</p>
                        </div>
                        <button
                            type="button"
                            x-on:click="show = false"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        >
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-600 dark:text-emerald-300">Evento publicado</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $evento->nombreevento }}</h2>
            <div class="mt-3 flex flex-wrap gap-3 text-sm text-slate-500 dark:text-slate-400">
                <span>{{ $evento->tipoEvento?->tipo ?? 'Sin tipo' }}</span>
                <span>{{ $evento->modalidad?->modalidad ?? 'Sin modalidad' }}</span>
                <span>{{ $evento->localidad_display }}</span>
                <span>{{ $evento->fechainicio?->format('d/m/Y') }} - {{ $evento->fechafinal?->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('eventos') }}"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
            >
                Volver al listado
            </a>
            <a
                href="{{ route('eventos.configurar', $evento) }}"
                class="inline-flex items-center justify-center rounded-2xl border border-yellow-300 px-4 py-2.5 text-sm font-medium text-yellow-700 transition hover:bg-yellow-50 dark:border-yellow-800 dark:text-yellow-300 dark:hover:bg-yellow-950/30"
            >
                Volver a configuración
            </a>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($this->tabItems as $key => $tab)
                <button
                    type="button"
                    wire:click="setTab('{{ $key }}')"
                    class="rounded-[1.6rem] border px-5 py-4 text-left transition {{ $activeTab === $key ? 'border-[#7b5cff] bg-[#f5f1ff] shadow-[0_16px_40px_rgba(123,92,255,0.12)] dark:border-[#8c6dff] dark:bg-[#1d1734]' : 'border-slate-200 bg-slate-50 hover:border-slate-300 hover:bg-white dark:border-slate-800 dark:bg-slate-950/60 dark:hover:border-slate-700 dark:hover:bg-slate-900' }}"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $tab['label'] }}</p>
                            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $tab['description'] }}</p>
                        </div>
                        <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full bg-white px-3 py-1 text-sm font-semibold text-slate-700 shadow-sm dark:bg-slate-900 dark:text-slate-200">
                            {{ $tab['count'] }}
                        </span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
        @if ($activeTab === 'invitados')
            <div class="space-y-6">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Invitados</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Desde aquí se administrarán las invitaciones personalizadas y los cupos especiales del evento.</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="button"
                            wire:click="openGuestListExportModal"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Descargar listado
                        </button>
                        <button
                            type="button"
                            wire:click="openDocumentGenerationConfirmModal('invitaciones_pdf')"
                            class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-5 py-3 text-sm font-semibold text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                        >
                            Generar invitaciones
                        </button>
                        <button
                            type="button"
                            wire:click="openGuestModal"
                            class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-yellow-400"
                        >
                            Agregar invitado
                        </button>
                    </div>
                </div>

                @if (isset($latestDocumentTasks['invitaciones_pdf']))
                    @php
                        $task = $latestDocumentTasks['invitaciones_pdf'];
                    @endphp
                    <div class="rounded-[1.6rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">PDF de invitaciones</p>
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $task->estado === 'completado' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : ($task->estado === 'fallido' ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300' : ($task->estado === 'cancelado' ? 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' : 'bg-violet-100 text-violet-700 dark:bg-violet-950/30 dark:text-violet-300')) }}">
                                        {{ ucfirst($task->estado) }}
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $task->procesados }} / {{ $task->total }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $task->mensaje_estado }}</p>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                    <div class="h-full rounded-full bg-gradient-to-r from-violet-600 to-fuchsia-500 transition-all duration-500" style="width: {{ $task->porcentaje }}%;"></div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-end gap-3">
                                @if ($task->esta_activo)
                                    <button
                                        type="button"
                                        wire:click="cancelDocumentTask({{ $task->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="cancelDocumentTask({{ $task->id }})"
                                        class="inline-flex items-center justify-center rounded-2xl border border-rose-300 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-950/30"
                                    >
                                        Cancelar proceso
                                    </button>
                                @endif
                                @if ($task->esta_completo)
                                    <a
                                        href="{{ route('eventos.documentos.download', $task->id) }}"
                                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                                    >
                                        Guardar PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px]">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                        </svg>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="guestSearch"
                            placeholder="Buscar por invitado, correo, teléfono o código..."
                            class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                    </div>

                    <div>
                        <select
                            wire:model.live="guestDeliveryFilter"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                            <option value="todos">Todos</option>
                            <option value="enviadas">Enviadas</option>
                            <option value="pendientes">Pendientes de enviar</option>
                        </select>
                    </div>
                </div>

                @if ($guestInvitations->count() > 0)
                    <div class="overflow-x-auto rounded-3xl border border-slate-200 dark:border-slate-800">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                <tr>
                                    <th class="px-5 py-4">Invitado</th>
                                    <th class="px-5 py-4">Correo</th>
                                    <th class="px-5 py-4">Teléfono</th>
                                    <th class="px-5 py-4">Cupos</th>
                                    <th class="px-5 py-4">Uso</th>
                                    <th class="px-5 py-4">Estado</th>
                                    <th class="px-5 py-4">Envío</th>
                                    <th class="px-5 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @foreach ($guestInvitations as $invitacion)
                                    <tr class="{{ $invitacion->enviada_at ? 'bg-emerald-50/80 text-slate-700 dark:bg-emerald-950/15 dark:text-slate-200' : 'bg-white text-slate-700 dark:bg-transparent dark:text-slate-200' }}">
                                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-slate-100">{{ $invitacion->nombre_invitado }}</td>
                                        <td class="px-5 py-4">{{ $invitacion->correo_invitado ?: 'Sin correo' }}</td>
                                        <td class="px-5 py-4">{{ $invitacion->telefono_invitado ?: 'Sin teléfono' }}</td>
                                        <td class="px-5 py-4">{{ $invitacion->cupos }}</td>
                                        <td class="px-5 py-4">{{ $invitacion->cupos_utilizados }} / {{ $invitacion->cupos }}</td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $invitacion->activa ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                                {{ $invitacion->activa ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            @if ($invitacion->enviada_at)
                                                <div class="space-y-1">
                                                    <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700 dark:bg-violet-950/40 dark:text-violet-300">
                                                        Enviada
                                                    </span>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                                        {{ ucfirst((string) $invitacion->ultimo_canal_envio) }} · {{ $invitacion->enviada_at?->format('d/m/Y H:i') }}
                                                    </p>
                                                </div>
                                            @else
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                    Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <button
                                                    type="button"
                                                    wire:click="openInvitationPreview({{ $invitacion->id }})"
                                                    class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-4 py-2 text-sm font-medium text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                                                >
                                                    Enviar invitación
                                                </button>

                                                <div
                                                    x-data="{
                                                        open: false,
                                                        menuStyle: '',
                                                        toggle() {
                                                            this.open = !this.open;
                                                            if (this.open) this.updatePosition();
                                                        },
                                                        close() {
                                                            this.open = false;
                                                        },
                                                        updatePosition() {
                                                            const rect = this.$refs.trigger.getBoundingClientRect();
                                                            this.menuStyle = `top:${rect.bottom + 10}px;left:${Math.max(16, rect.right - 240)}px;`;
                                                        }
                                                    }"
                                                    x-on:resize.window="if (open) updatePosition()"
                                                    x-on:scroll.window="if (open) updatePosition()"
                                                    class="relative inline-flex justify-end"
                                                >
                                                    <button
                                                        type="button"
                                                    x-ref="trigger"
                                                    x-on:click="toggle()"
                                                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-800"
                                                    >
                                                        <span class="sr-only">Abrir acciones</span>
                                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M10 4a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm0 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm0 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z" />
                                                        </svg>
                                                    </button>

                                                    <template x-teleport="body">
                                                        <div
                                                            x-show="open"
                                                            x-transition.opacity.duration.150ms
                                                            x-on:click.self="close()"
                                                            class="fixed inset-0 z-[85] pointer-events-auto"
                                                        >
                                                            <div
                                                                x-show="open"
                                                                x-transition.scale.origin.top.right.duration.150ms
                                                                x-bind:style="menuStyle"
                                                                class="fixed z-[90] w-60 rounded-[1.5rem] border border-slate-200 bg-white p-2 text-left shadow-[0_24px_60px_rgba(15,23,42,0.18)] dark:border-slate-700 dark:bg-slate-900"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    x-on:click="close()"
                                                                    wire:click="editGuestInvitation({{ $invitacion->id }})"
                                                                    class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
                                                                >
                                                                    Editar datos del invitado
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    x-on:click="close()"
                                                                    wire:click="confirmDeleteGuest({{ $invitacion->id }})"
                                                                    class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-950/30"
                                                                >
                                                                    Eliminar invitado
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $guestInvitations->links() }}
                    </div>
                @else
                    <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700">
                        <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $guestSearch !== '' || $guestDeliveryFilter !== 'todos' ? 'No hay coincidencias para los filtros aplicados.' : 'Aún no hay invitaciones configuradas.' }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ $guestSearch !== '' || $guestDeliveryFilter !== 'todos' ? 'Prueba con otra búsqueda o cambia el filtro de envío.' : 'Agrega el primer invitado con su correo, teléfono y cupos asignados.' }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        @if ($activeTab === 'staff')
            <div class="space-y-6">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Staff y equipo organizador</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Administra los miembros del equipo operativo. Si la persona aún no tiene usuario, se generará un enlace para completar su perfil.</p>
                    </div>
                    @can('events.manage')
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button
                                type="button"
                                wire:click="openStaffListExportModal"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                            >
                                Descargar listado
                            </button>
                            <button
                                type="button"
                                wire:click="openDocumentGenerationConfirmModal('staff_gafetes_pdf')"
                                class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-5 py-3 text-sm font-semibold text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                            >
                                Generar gafetes
                            </button>
                            <button
                                type="button"
                                wire:click="openStaffModal"
                                class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-yellow-400"
                            >
                                Agregar miembro del staff
                            </button>
                        </div>
                    @endcan
                </div>

                @if (isset($latestDocumentTasks['staff_gafetes_pdf']))
                    @php
                        $task = $latestDocumentTasks['staff_gafetes_pdf'];
                    @endphp
                    <div class="rounded-[1.6rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">PDF de gafetes del staff</p>
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $task->estado === 'completado' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : ($task->estado === 'fallido' ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300' : ($task->estado === 'cancelado' ? 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' : 'bg-violet-100 text-violet-700 dark:bg-violet-950/30 dark:text-violet-300')) }}">
                                        {{ ucfirst($task->estado) }}
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $task->procesados }} / {{ $task->total }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $task->mensaje_estado }}</p>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                    <div class="h-full rounded-full bg-gradient-to-r from-violet-600 to-fuchsia-500 transition-all duration-500" style="width: {{ $task->porcentaje }}%;"></div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-end gap-3">
                                @if ($task->esta_activo)
                                    <button
                                        type="button"
                                        wire:click="cancelDocumentTask({{ $task->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="cancelDocumentTask({{ $task->id }})"
                                        class="inline-flex items-center justify-center rounded-2xl border border-rose-300 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-950/30"
                                    >
                                        Cancelar proceso
                                    </button>
                                @endif
                                @if ($task->esta_completo)
                                    <a
                                        href="{{ route('eventos.documentos.download', $task->id) }}"
                                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                                    >
                                        Guardar PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px]">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                        </svg>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="staffSearch"
                            placeholder="Buscar por nombre, correo o teléfono..."
                            class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                    </div>

                    <div>
                        <select
                            wire:model.live="staffStatusFilter"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                            <option value="todos">Todos</option>
                            <option value="completos">Perfil completo</option>
                            <option value="pendientes">Pendientes</option>
                        </select>
                    </div>
                </div>

                @if ($staffMembers->count() > 0)
                    <div class="overflow-x-auto rounded-3xl border border-slate-200 dark:border-slate-800">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                <tr>
                                    <th class="px-5 py-4">Miembro</th>
                                    <th class="px-5 py-4">Correo</th>
                                    <th class="px-5 py-4">Teléfono</th>
                                    <th class="px-5 py-4">Usuario</th>
                                    <th class="px-5 py-4">Perfil</th>
                                    <th class="px-5 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @foreach ($staffMembers as $member)
                                    <tr class="{{ $member->perfil_completado_at ? 'bg-emerald-50/70 text-slate-700 dark:bg-emerald-950/15 dark:text-slate-200' : 'bg-white text-slate-700 dark:bg-transparent dark:text-slate-200' }}">
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $member->nombre }}</p>
                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $member->persona?->dni ?: 'Sin DNI aún' }}</p>
                                        </td>
                                        <td class="px-5 py-4">{{ $member->correo ?: 'Sin correo' }}</td>
                                        <td class="px-5 py-4">{{ $member->telefono ?: 'Sin teléfono' }}</td>
                                        <td class="px-5 py-4">
                                            @if ($member->persona?->user)
                                                <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700 dark:bg-violet-950/40 dark:text-violet-300">
                                                    {{ $member->persona->user->email }}
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                    Pendiente de registro
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $member->perfil_completado_at ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' }}">
                                                {{ $member->perfil_completado_at ? 'Completo' : 'Pendiente' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                @if ($member->perfil_completado_at)
                                                    <button
                                                        type="button"
                                                        wire:click="openStaffBadgePreview({{ $member->id }})"
                                                        class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-4 py-2 text-sm font-medium text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                                                    >
                                                        Generar gafete
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        wire:click="openStaffLinkModal({{ $member->id }})"
                                                        class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-4 py-2 text-sm font-medium text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                                                    >
                                                        Ver enlace
                                                    </button>
                                                @endif

                                                <div
                                                    x-data="{
                                                        open: false,
                                                        menuStyle: '',
                                                        toggle() {
                                                            this.open = !this.open;
                                                            if (this.open) this.updatePosition();
                                                        },
                                                        close() {
                                                            this.open = false;
                                                        },
                                                        updatePosition() {
                                                            const rect = this.$refs.trigger.getBoundingClientRect();
                                                            this.menuStyle = `top:${rect.bottom + 10}px;left:${Math.max(16, rect.right - 224)}px;`;
                                                        }
                                                    }"
                                                    x-on:resize.window="if (open) updatePosition()"
                                                    x-on:scroll.window="if (open) updatePosition()"
                                                    class="relative inline-flex justify-end"
                                                >
                                                    <button
                                                        type="button"
                                                    x-ref="trigger"
                                                    x-on:click="toggle()"
                                                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-800"
                                                    >
                                                        <span class="sr-only">Abrir acciones</span>
                                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M10 4a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm0 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm0 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z" />
                                                        </svg>
                                                    </button>

                                                    <template x-teleport="body">
                                                        <div
                                                            x-show="open"
                                                            x-transition.opacity.duration.150ms
                                                            x-on:click.self="close()"
                                                            class="fixed inset-0 z-[85] pointer-events-auto"
                                                        >
                                                            <div
                                                                x-show="open"
                                                                x-transition.scale.origin.top.right.duration.150ms
                                                                x-bind:style="menuStyle"
                                                                class="fixed z-[90] w-56 rounded-[1.5rem] border border-slate-200 bg-white p-2 text-left shadow-[0_24px_60px_rgba(15,23,42,0.18)] dark:border-slate-700 dark:bg-slate-900"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    x-on:click="close()"
                                                                    wire:click="editStaffMember({{ $member->id }})"
                                                                    class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
                                                                >
                                                                    Editar miembro
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    x-on:click="close()"
                                                                    wire:click="confirmDeleteStaff({{ $member->id }})"
                                                                    class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-950/30"
                                                                >
                                                                    Eliminar miembro
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $staffMembers->links() }}
                    </div>
                @else
                    <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700">
                        <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $staffSearch !== '' || $staffStatusFilter !== 'todos' ? 'No hay coincidencias para los filtros aplicados.' : 'Aún no hay miembros del staff registrados.' }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ $staffSearch !== '' || $staffStatusFilter !== 'todos' ? 'Prueba con otra búsqueda o cambia el filtro.' : 'Agrega el primer miembro del staff seleccionando un usuario existente o generando un enlace de completación.' }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        @if ($activeTab === 'inscripciones')
            <div class="space-y-6">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Inscripciones</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Administra los participantes inscritos, actualiza sus datos personales y genera sus gafetes de acceso.</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="button"
                            wire:click="openRegistrationListExportModal"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Descargar listado
                        </button>
                        <button
                            type="button"
                            wire:click="openDocumentGenerationConfirmModal('inscripciones_gafetes_pdf')"
                            class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-5 py-3 text-sm font-semibold text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                        >
                            Generar gafetes
                        </button>
                    </div>
                </div>

                @if (isset($latestDocumentTasks['inscripciones_gafetes_pdf']))
                    @php
                        $task = $latestDocumentTasks['inscripciones_gafetes_pdf'];
                    @endphp
                    <div class="rounded-[1.6rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">PDF de gafetes de participantes</p>
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $task->estado === 'completado' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : ($task->estado === 'fallido' ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300' : ($task->estado === 'cancelado' ? 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' : 'bg-violet-100 text-violet-700 dark:bg-violet-950/30 dark:text-violet-300')) }}">
                                        {{ ucfirst($task->estado) }}
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $task->procesados }} / {{ $task->total }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $task->mensaje_estado }}</p>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                    <div class="h-full rounded-full bg-gradient-to-r from-violet-600 to-fuchsia-500 transition-all duration-500" style="width: {{ $task->porcentaje }}%;"></div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-end gap-3">
                                @if ($task->esta_activo)
                                    <button
                                        type="button"
                                        wire:click="cancelDocumentTask({{ $task->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="cancelDocumentTask({{ $task->id }})"
                                        class="inline-flex items-center justify-center rounded-2xl border border-rose-300 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-950/30"
                                    >
                                        Cancelar proceso
                                    </button>
                                @endif
                                @if ($task->esta_completo)
                                    <a
                                        href="{{ route('eventos.documentos.download', $task->id) }}"
                                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                                    >
                                        Guardar PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px]">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                        </svg>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="registrationSearch"
                            placeholder="Buscar por participante, correo, teléfono, DNI o perfil..."
                            class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                    </div>

                    <div>
                        <select
                            wire:model.live="registrationStatusFilter"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                            <option value="todos">Todos</option>
                            <option value="pagadas">Pagadas</option>
                            <option value="pendientes">Pendientes de pago</option>
                            <option value="gratuitas">Gratuitas</option>
                        </select>
                    </div>
                </div>

                @if ($registrationRecords->count() > 0)
                    <div class="overflow-x-auto rounded-3xl border border-slate-200 dark:border-slate-800">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                <tr>
                                    <th class="px-5 py-4">Participante</th>
                                    <th class="px-5 py-4">Perfil</th>
                                    <th class="px-5 py-4">Correo</th>
                                    <th class="px-5 py-4">Teléfono</th>
                                    <th class="px-5 py-4">Pago</th>
                                    <th class="px-5 py-4">Estado</th>
                                    <th class="px-5 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @foreach ($registrationRecords as $registro)
                                    <tr class="{{ $registro->estado_pago === 'pagado' || $registro->estado_pago === 'no_aplica' ? 'bg-emerald-50/70 text-slate-700 dark:bg-emerald-950/15 dark:text-slate-200' : 'bg-white text-slate-700 dark:bg-transparent dark:text-slate-200' }}">
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-slate-900 dark:text-slate-100">
                                                {{ trim(($registro->persona?->nombre ?? '') . ' ' . ($registro->persona?->apellido ?? '')) ?: 'Participante no disponible' }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                {{ $registro->persona?->dni ?: 'Sin DNI registrado' }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-4">{{ $registro->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</td>
                                        <td class="px-5 py-4">{{ $registro->persona?->correo ?: 'Sin correo' }}</td>
                                        <td class="px-5 py-4">{{ $registro->persona?->telefono ?: 'Sin teléfono' }}</td>
                                        <td class="px-5 py-4">
                                            <div class="space-y-1">
                                                <p class="font-medium text-slate-900 dark:text-slate-100">
                                                    @if ((float) $registro->precio_aplicado > 0)
                                                        {{ $registro->formattedPrecioAplicado(true) }}
                                                    @else
                                                        Gratuita
                                                    @endif
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $registro->metodoPago?->nombre ?? ($registro->estado_pago === 'no_aplica' ? 'No aplica' : 'Pendiente de definir') }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="space-y-2">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $registro->estado === 'registrado' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $registro->estado ?: 'sin estado')) }}
                                                </span>
                                                <div>
                                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $registro->estado_pago === 'pagado' || $registro->estado_pago === 'no_aplica' ? 'bg-violet-100 text-violet-700 dark:bg-violet-950/40 dark:text-violet-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $registro->estado_pago ?: 'pendiente')) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <button
                                                    type="button"
                                                    wire:click="openRegistrationBadgePreview({{ $registro->id }})"
                                                    class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-4 py-2 text-sm font-medium text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                                                >
                                                    Generar gafete
                                                </button>

                                                <div
                                                    x-data="{
                                                        open: false,
                                                        menuStyle: '',
                                                        toggle() {
                                                            this.open = !this.open;
                                                            if (this.open) this.updatePosition();
                                                        },
                                                        close() {
                                                            this.open = false;
                                                        },
                                                        updatePosition() {
                                                            const rect = this.$refs.trigger.getBoundingClientRect();
                                                            this.menuStyle = `top:${rect.bottom + 10}px;left:${Math.max(16, rect.right - 230)}px;`;
                                                        }
                                                    }"
                                                    x-on:resize.window="if (open) updatePosition()"
                                                    x-on:scroll.window="if (open) updatePosition()"
                                                    class="relative inline-flex justify-end"
                                                >
                                                    <button
                                                        type="button"
                                                        x-ref="trigger"
                                                        x-on:click="toggle()"
                                                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-800"
                                                    >
                                                        <span class="sr-only">Abrir acciones</span>
                                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M10 4a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm0 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm0 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z" />
                                                        </svg>
                                                    </button>

                                                    <template x-teleport="body">
                                                        <div
                                                            x-show="open"
                                                            x-transition.opacity.duration.150ms
                                                            x-on:click.self="close()"
                                                            class="fixed inset-0 z-[85] pointer-events-auto"
                                                        >
                                                            <div
                                                                x-show="open"
                                                                x-transition.scale.origin.top.right.duration.150ms
                                                                x-bind:style="menuStyle"
                                                                class="fixed z-[90] w-60 rounded-[1.5rem] border border-slate-200 bg-white p-2 text-left shadow-[0_24px_60px_rgba(15,23,42,0.18)] dark:border-slate-700 dark:bg-slate-900"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    x-on:click="close()"
                                                                    wire:click="editRegistration({{ $registro->id }})"
                                                                    class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
                                                                >
                                                                    Editar datos personales
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    x-on:click="close()"
                                                                    wire:click="confirmDeleteRegistration({{ $registro->id }})"
                                                                    class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-950/30"
                                                                >
                                                                    Eliminar subscripción
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $registrationRecords->links() }}
                    </div>
                @else
                    <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700">
                        <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $registrationSearch !== '' || $registrationStatusFilter !== 'todos' ? 'No hay coincidencias para los filtros aplicados.' : 'Aún no hay inscripciones registradas.' }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ $registrationSearch !== '' || $registrationStatusFilter !== 'todos' ? 'Prueba con otra búsqueda o cambia el filtro de pago.' : 'Cuando los participantes se registren al evento publicado, aparecerán aquí.' }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <?php if ($activeTab === 'asistencia'): ?>
            <div class="space-y-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Asistencia por conferencia</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Revisa cada conferencia o taller del evento y administra el listado de participantes que asistieron.</p>
                    </div>

                    <div class="w-full lg:max-w-md">
                        <label for="attendance-search" class="sr-only">Buscar conferencia</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.473 9.765l3.63 3.631a1 1 0 0 0 1.414-1.414l-3.63-3.63A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input
                                id="attendance-search"
                                type="text"
                                wire:model.live.debounce.300ms="attendanceSearch"
                                class="block w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                                placeholder="Buscar por conferencia, tipo, lugar o conferencista..."
                            >
                        </div>
                    </div>
                </div>

                <?php if ($attendanceConferences->count()): ?>
                    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-[0.24em] text-slate-500 dark:bg-slate-950/70 dark:text-slate-400">
                                    <tr>
                                        <th class="px-6 py-4">Conferencia / taller</th>
                                        <th class="px-6 py-4">Fecha y horario</th>
                                        <th class="px-6 py-4">Lugar</th>
                                        <th class="px-6 py-4">Asistieron</th>
                                        <th class="px-6 py-4 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    <?php foreach ($attendanceConferences as $conference): ?>
                                        @php
                                            $speakerName = trim(($conference->speakerPersona?->nombre ?? '') . ' ' . ($conference->speakerPersona?->apellido ?? ''))
                                                ?: ($conference->conferencista_nombre_invitado ?: 'Conferencista por definir');
                                        @endphp
                                        <tr class="align-top">
                                            <td class="px-6 py-5">
                                                <div class="space-y-2">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-violet-700 dark:bg-violet-900/40 dark:text-violet-200">
                                                            {{ $conference->tipoConferencia?->tipo ?? 'Actividad' }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $conference->nombre }}</p>
                                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $speakerName }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <p class="font-medium text-slate-900 dark:text-slate-100">{{ $conference->fecha ? \Illuminate\Support\Carbon::parse($conference->fecha)->format('d/m/Y') : 'Sin fecha' }}</p>
                                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                    {{ $conference->horaInicio ? \Illuminate\Support\Carbon::parse($conference->horaInicio)->format('h:i a') : '--:--' }}
                                                    -
                                                    {{ $conference->horaFin ? \Illuminate\Support\Carbon::parse($conference->horaFin)->format('h:i a') : '--:--' }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-5 text-slate-600 dark:text-slate-300">
                                                {{ $conference->lugar ?: 'Lugar por definir' }}
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                                                    {{ $conference->registro_asistencias_count }} asistentes
                                                </span>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex justify-end">
                                                    <button
                                                        type="button"
                                                        wire:click="openAttendanceParticipantsModal({{ $conference->id }})"
                                                        class="inline-flex items-center justify-center rounded-2xl border border-violet-200 px-4 py-2.5 text-sm font-semibold text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-200 dark:hover:bg-violet-950/30"
                                                    >
                                                        Ver listado de participantes
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        {{ $attendanceConferences->links() }}
                    </div>
                <?php else: ?>
                    <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700">
                        <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $attendanceSearch !== '' ? 'No hay conferencias que coincidan con la búsqueda.' : 'Aún no hay conferencias registradas para este evento.' }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ $attendanceSearch !== '' ? 'Prueba con otra búsqueda.' : 'Cuando el evento tenga agenda configurada, aparecerá aquí el control de asistencia por actividad.' }}
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($activeTab === 'certificados'): ?>
            <div class="space-y-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Certificados generados</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Desde este apartado se generan y almacenan los PDFs de participación general del evento y los diplomas por conferencia.</p>
                    </div>
                </div>

                <div class="grid gap-4 xl:grid-cols-2">
                    <article class="rounded-[1.8rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Participación general</p>
                                <h4 class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Certificados del evento completo</h4>
                                <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                                    @if ($evento->genera_diploma_participacion)
                                        Genera un PDF por cada inscrito del evento usando la plantilla de participación general.
                                    @else
                                        Este evento fue configurado sin diploma de participación general.
                                    @endif
                                </p>
                            </div>
                            <button
                                type="button"
                                wire:click="openDocumentGenerationConfirmModal('certificados_generales_pdf')"
                                @disabled(! $evento->genera_diploma_participacion || ! $this->generalParticipationCertificateDesignConfigured())
                                class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-600 dark:disabled:bg-slate-800 dark:disabled:text-slate-500"
                            >
                                Generar diplomas generales
                            </button>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3 text-sm">
                            <span class="inline-flex rounded-full bg-white px-4 py-2 font-medium text-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                Inscritos: {{ $evento->registros->count() }}
                            </span>
                            <span class="inline-flex rounded-full bg-white px-4 py-2 font-medium text-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                Diseño: {{ $this->generalParticipationCertificateDesignConfigured() ? 'Configurado' : 'Pendiente' }}
                            </span>
                        </div>
                    </article>

                    <article class="rounded-[1.8rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Por conferencia</p>
                                <h4 class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Diplomas de asistencia por actividad</h4>
                                <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Genera certificados para las personas marcadas como asistentes en cada conferencia o taller.</p>
                            </div>
                            <button
                                type="button"
                                wire:click="generateConferenceParticipationCertificates"
                                wire:loading.attr="disabled"
                                wire:target="generateConferenceParticipationCertificates"
                                @disabled(! $this->participationCertificateDesignConfigured())
                                class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-5 py-3 text-sm font-semibold text-violet-700 transition hover:bg-violet-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400 disabled:hover:bg-transparent dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30 dark:disabled:border-slate-800 dark:disabled:text-slate-500"
                            >
                                <span wire:loading.remove wire:target="generateConferenceParticipationCertificates">Generar todos por conferencia</span>
                                <span wire:loading.inline-flex wire:target="generateConferenceParticipationCertificates" class="items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                                    </svg>
                                    Generando...
                                </span>
                            </button>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3 text-sm">
                            <span class="inline-flex rounded-full bg-white px-4 py-2 font-medium text-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                Conferencias: {{ $evento->conferencias->count() }}
                            </span>
                            <span class="inline-flex rounded-full bg-white px-4 py-2 font-medium text-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                Diseño: {{ $this->participationCertificateDesignConfigured() ? 'Configurado' : 'Pendiente' }}
                            </span>
                        </div>
                    </article>
                </div>

                <?php if (isset($latestDocumentTasks['certificados_generales_pdf'])): ?>
                    @php
                        $task = $latestDocumentTasks['certificados_generales_pdf'];
                    @endphp
                    <div class="rounded-[1.6rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">PDF de diplomas generales</p>
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $task->estado === 'completado' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : ($task->estado === 'fallido' ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300' : ($task->estado === 'cancelado' ? 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' : 'bg-violet-100 text-violet-700 dark:bg-violet-950/30 dark:text-violet-300')) }}">
                                        {{ ucfirst($task->estado) }}
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $task->procesados }} / {{ $task->total }}</span>
                                    @if ($task->omitidos > 0)
                                        <span class="text-xs text-amber-600 dark:text-amber-300">Omitidos: {{ $task->omitidos }}</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $task->mensaje_estado }}</p>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                    <div class="h-full rounded-full bg-gradient-to-r from-violet-600 to-fuchsia-500 transition-all duration-500" style="width: {{ $task->porcentaje }}%;"></div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-end gap-3">
                                <?php if ($task->esta_activo): ?>
                                    <button
                                        type="button"
                                        wire:click="cancelDocumentTask({{ $task->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="cancelDocumentTask({{ $task->id }})"
                                        class="inline-flex items-center justify-center rounded-2xl border border-rose-300 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-950/30"
                                    >
                                        Cancelar proceso
                                    </button>
                                <?php endif; ?>
                                <?php if ($task->esta_completo): ?>
                                    <a
                                        href="<?= e(route('eventos.documentos.download', $task->id)) ?>"
                                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                                    >
                                        Guardar PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="overflow-hidden rounded-[1.8rem] border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Generación por conferencia</h4>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Usa esta tabla si deseas generar certificados solo para actividades específicas.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                <tr>
                                    <th class="px-5 py-4">Actividad</th>
                                    <th class="px-5 py-4">Fecha</th>
                                    <th class="px-5 py-4">Asistencias</th>
                                    <th class="px-5 py-4">Certificados</th>
                                    <th class="px-5 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <?php if (count($certificateConferenceSummaries) > 0): ?>
                                    <?php foreach ($certificateConferenceSummaries as $conference): ?>
                                    <tr>
                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $conference->nombre }}</p>
                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $conference->tipoConferencia?->tipo ?? 'Actividad' }}</p>
                                        </td>
                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                                            {{ $conference->fecha ? \Illuminate\Support\Carbon::parse($conference->fecha)->format('d/m/Y') : 'Sin fecha' }}
                                        </td>
                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $conference->registro_asistencias_count }}</td>
                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $conference->generated_certificates_count }}</td>
                                        <td class="px-5 py-4 text-right">
                                            <button
                                                type="button"
                                                wire:click="generateConferenceParticipationCertificates({{ $conference->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="generateConferenceParticipationCertificates({{ $conference->id }})"
                                                @disabled(! $this->participationCertificateDesignConfigured() || $conference->registro_asistencias_count === 0)
                                                class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:border-slate-200 disabled:text-slate-400 disabled:hover:bg-transparent dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 dark:disabled:border-slate-800 dark:disabled:text-slate-500"
                                            >
                                                Generar certificados
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-400">Aún no hay conferencias configuradas para este evento.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px]">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                        </svg>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="certificateSearch"
                            placeholder="Buscar por participante, conferencia o hash..."
                            class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                    </div>
                    <div>
                        <select
                            wire:model.live="certificateTypeFilter"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                        >
                            <option value="todos">Todos</option>
                            <option value="generales">Participación general</option>
                            <option value="conferencia">Por conferencia</option>
                        </select>
                    </div>
                </div>

                <?php if ($generatedCertificates->count() > 0): ?>
                    <div class="overflow-hidden rounded-[1.8rem] border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                                    <tr>
                                        <th class="px-5 py-4">Participante</th>
                                        <th class="px-5 py-4">Tipo</th>
                                        <th class="px-5 py-4">Actividad</th>
                                        <th class="px-5 py-4">Hash</th>
                                        <th class="px-5 py-4">Generado</th>
                                        <th class="px-5 py-4 text-right">PDF</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    <?php foreach ($generatedCertificates as $certificate): ?>
                                        @php
                                            $personName = trim(($certificate->persona?->nombre ?? '') . ' ' . ($certificate->persona?->apellido ?? '')) ?: 'Participante';
                                        @endphp
                                        <tr>
                                            <td class="px-5 py-4">
                                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $personName }}</p>
                                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $certificate->persona?->dni ?: 'Sin DNI' }}</p>
                                            </td>
                                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                                                {{ $certificate->tipo === 'participacion_general' ? 'Participación general' : 'Por conferencia' }}
                                            </td>
                                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                                                {{ $certificate->conferencia?->nombre ?: 'Evento completo' }}
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-mono text-xs text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                                    {{ $certificate->hash_unico }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                                                {{ $certificate->generated_at?->format('d/m/Y h:i a') ?: 'Sin fecha' }}
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <button
                                                    type="button"
                                                    wire:click="downloadGeneratedCertificate({{ $certificate->id }})"
                                                    class="inline-flex items-center justify-center rounded-2xl border border-violet-300 px-4 py-2.5 text-sm font-semibold text-violet-700 transition hover:bg-violet-50 dark:border-violet-800 dark:text-violet-300 dark:hover:bg-violet-950/30"
                                                >
                                                    Descargar PDF
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        {{ $generatedCertificates->links() }}
                    </div>
                <?php else: ?>
                    <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700">
                        <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">Aún no se han generado certificados para este evento.</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Genera los certificados desde las acciones superiores y aquí quedarán almacenados como PDF.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    @if ($showGuestModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeGuestModal"></div>

            <div class="relative flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-yellow-600 dark:text-yellow-300">Invitación</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $editingGuestId ? 'Editar invitado' : 'Agregar invitado' }}</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Registra los datos personales del invitado. El correo y el teléfono son obligatorios para el envío futuro por correo electrónico y WhatsApp.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeGuestModal"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-6 py-6">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="guestName" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre completo <span class="text-red-500">*</span></label>
                            <input
                                id="guestName"
                                type="text"
                                wire:model.live="guestName"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                placeholder="Ej. Pedro Martínez López"
                            >
                            @error('guestName') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="guestEmail" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo electrónico <span class="text-red-500">*</span></label>
                            <input
                                id="guestEmail"
                                type="email"
                                wire:model.live="guestEmail"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                placeholder="invitado@correo.com"
                            >
                            @error('guestEmail') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="guestPhone" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Número de teléfono <span class="text-red-500">*</span></label>
                            <input
                                id="guestPhone"
                                type="text"
                                wire:model.live="guestPhone"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                                placeholder="+504 9999-9999"
                            >
                            @error('guestPhone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="guestSlots" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Cantidad de cupos <span class="text-red-500">*</span></label>
                            <input
                                id="guestSlots"
                                type="number"
                                min="1"
                                max="20"
                                wire:model.live="guestSlots"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                            >
                            @error('guestSlots') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
                    <button
                        type="button"
                        wire:click="closeGuestModal"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="saveGuestInvitation"
                        class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400"
                    >
                        {{ $editingGuestId ? 'Guardar cambios' : 'Guardar invitado' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showGeneralCertificateGenerationModal)
        @php
            $generalCertificatePercent = $generalCertificateGenerationTotal > 0
                ? (int) min(100, round(($generalCertificateGenerationProcessed / $generalCertificateGenerationTotal) * 100))
                : 0;
        @endphp
        <div class="fixed inset-0 z-[55] flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeGeneralCertificateGenerationModal"></div>

            <div class="relative w-full max-w-2xl rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div
                    @if ($generalCertificateGenerationProcessing)
                        wire:poll.900ms="processGeneralCertificateGenerationBatch"
                    @endif
                    class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800"
                >
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Certificados</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Generar certificados generales</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ $generalCertificateGenerationProcessing
                                ? 'El sistema está generando los certificados por lotes. Esta ventana se actualizará automáticamente con el avance.'
                                : 'Confirma la generación. Si el evento tiene muchos inscritos, el sistema procesará los certificados en lotes para evitar bloqueos.' }}
                        </p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeGeneralCertificateGenerationModal"
                        @disabled($generalCertificateGenerationProcessing)
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-6 px-6 py-6">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Total</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $generalCertificateGenerationTotal }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Procesados</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $generalCertificateGenerationProcessed }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Generados</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $generalCertificateGenerationGenerated }}</p>
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $generalCertificateGenerationStatus }}</p>
                            <span class="text-sm font-semibold text-violet-700 dark:text-violet-300">{{ $generalCertificatePercent }}%</span>
                        </div>
                        <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                            <div class="h-full rounded-full bg-gradient-to-r from-violet-600 to-fuchsia-500 transition-all duration-500" style="width: {{ $generalCertificatePercent }}%;"></div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3 text-xs text-slate-500 dark:text-slate-400">
                            <span>Lote: {{ $generalCertificateGenerationBatchSize }} registros</span>
                            <span>Omitidos: {{ $generalCertificateGenerationSkipped }}</span>
                            <span>Pendientes: {{ max(0, $generalCertificateGenerationTotal - $generalCertificateGenerationProcessed) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
                    @if (! $generalCertificateGenerationProcessing && ! $generalCertificateGenerationCompleted)
                        <button
                            type="button"
                            wire:click="closeGeneralCertificateGenerationModal"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            wire:click="startGeneralCertificateGeneration"
                            class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                        >
                            Confirmar generación
                        </button>
                    @elseif ($generalCertificateGenerationCompleted)
                        <button
                            type="button"
                            wire:click="closeGeneralCertificateGenerationModal"
                            class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500"
                        >
                            Cerrar
                        </button>
                    @else
                        <div class="inline-flex items-center gap-2 rounded-2xl bg-violet-50 px-4 py-3 text-sm font-medium text-violet-700 dark:bg-violet-950/30 dark:text-violet-300">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                            </svg>
                            Procesando certificados...
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($showRegistrationBadgeGenerationModal)
        @php
            $registrationBadgePercent = $registrationBadgeGenerationTotal > 0
                ? (int) min(100, round(($registrationBadgeGenerationProcessed / $registrationBadgeGenerationTotal) * 100))
                : 0;
        @endphp
        <div class="fixed inset-0 z-[56] flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeRegistrationBadgeGenerationModal"></div>

            <div class="relative w-full max-w-2xl rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div
                    @if ($registrationBadgeGenerationProcessing)
                        wire:poll.900ms="processRegistrationBadgeGenerationBatch"
                    @endif
                    class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800"
                >
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Gafetes</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Generar PDF de gafetes</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ $registrationBadgeGenerationProcessing
                                ? 'El sistema está generando el PDF de gafetes por lotes. Esta ventana se actualizará automáticamente con el avance.'
                                : ($registrationBadgeGenerationCompleted
                                    ? 'La generación finalizó. Ya puedes descargar el PDF consolidado del evento.'
                                    : 'Confirma la generación. Si el evento tiene muchos inscritos, el sistema procesará los gafetes en lotes para evitar timeouts.') }}
                        </p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeRegistrationBadgeGenerationModal"
                        @disabled($registrationBadgeGenerationProcessing)
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-6 px-6 py-6">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Total</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $registrationBadgeGenerationTotal }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Procesados</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $registrationBadgeGenerationProcessed }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Generados</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $registrationBadgeGenerationGenerated }}</p>
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $registrationBadgeGenerationStatus }}</p>
                            <span class="text-sm font-semibold text-violet-700 dark:text-violet-300">{{ $registrationBadgePercent }}%</span>
                        </div>
                        <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                            <div class="h-full rounded-full bg-gradient-to-r from-violet-600 to-fuchsia-500 transition-all duration-500" style="width: {{ $registrationBadgePercent }}%;"></div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3 text-xs text-slate-500 dark:text-slate-400">
                            <span>Lote: {{ $registrationBadgeGenerationBatchSize }} registros</span>
                            <span>Pendientes: {{ max(0, $registrationBadgeGenerationTotal - $registrationBadgeGenerationProcessed) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
                    @if (! $registrationBadgeGenerationProcessing && ! $registrationBadgeGenerationCompleted)
                        <button
                            type="button"
                            wire:click="closeRegistrationBadgeGenerationModal"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            wire:click="startRegistrationBadgeGeneration"
                            class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                        >
                            Confirmar generación
                        </button>
                    @elseif ($registrationBadgeGenerationCompleted)
                        <button
                            type="button"
                            wire:click="closeRegistrationBadgeGenerationModal"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Cerrar
                        </button>
                        <button
                            type="button"
                            wire:click="downloadGeneratedRegistrationBadgesPdf"
                            class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                        >
                            Descargar PDF final
                        </button>
                    @else
                        <div class="inline-flex items-center gap-2 rounded-2xl bg-violet-50 px-4 py-3 text-sm font-medium text-violet-700 dark:bg-violet-950/30 dark:text-violet-300">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                            </svg>
                            Generando gafetes...
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($showListExportModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeListExportModal"></div>

            <div class="relative flex w-full max-w-2xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Exportación</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">
                            Descargar listado de
                            {{
                                $listExportContext === 'staff'
                                    ? 'staff'
                                    : ($listExportContext === 'registros' ? 'inscripciones' : 'invitados')
                            }}
                        </h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Selecciona el formato de salida. Si eliges PDF, debes indicar la fecha del evento que aparecerá en el documento.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeListExportModal"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-6 px-6 py-6">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Formato de descarga <span class="text-red-500">*</span></label>
                            <select
                                wire:model.live="listExportFormat"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                            >
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Fecha a mostrar en el documento @if($listExportFormat === 'pdf')<span class="text-red-500">*</span>@endif</label>
                            <select
                                wire:model.live="listExportDate"
                                @disabled($listExportFormat !== 'pdf')
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:disabled:bg-slate-900 dark:disabled:text-slate-500 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                            >
                                <option value="">Selecciona una fecha</option>
                                @foreach ($this->listExportDateOptions() as $dateOption)
                                    <option value="{{ $dateOption['value'] }}">{{ $dateOption['label'] }}</option>
                                @endforeach
                            </select>
                            @error('listExportDate') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @if ($listExportContext === 'registros' && $listExportFormat === 'pdf')
                        <label class="flex items-start gap-3 rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300">
                            <input
                                type="checkbox"
                                wire:model.live="listExportIncludeSignature"
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500 dark:border-slate-700 dark:bg-slate-950"
                            >
                            <span>
                                <span class="block font-semibold text-slate-800 dark:text-slate-100">Agregar columna de firma</span>
                                <span class="mt-1 block text-slate-500 dark:text-slate-400">Si la marcas, el listado PDF incluirá una columna vacía para firma al final.</span>
                            </span>
                        </label>
                    @endif

                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300">
                        @if ($listExportFormat === 'pdf')
                            El PDF descargará el formato de listado de asistencia e incluirá la fecha seleccionada en el encabezado.
                        @else
                            El archivo Excel descargará toda la información disponible de
                            {{
                                $listExportContext === 'staff'
                                    ? 'los miembros del staff'
                                    : ($listExportContext === 'registros' ? 'las inscripciones del evento' : 'los invitados')
                            }}.
                        @endif
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
                    <button
                        type="button"
                        wire:click="closeListExportModal"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="exportList"
                        wire:loading.attr="disabled"
                        wire:target="exportList"
                        @disabled($listExportFormat === 'pdf' && blank($listExportDate))
                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="exportList">
                            {{ $listExportFormat === 'excel' ? 'Descargar Excel' : 'Descargar PDF' }}
                        </span>
                        <span wire:loading.inline-flex wire:target="exportList" class="items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                            </svg>
                            Generando archivo...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showStaffModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeStaffModal"></div>

            <div class="relative flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-500 dark:text-violet-300">Staff</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $editingStaffId ? 'Editar miembro del staff' : 'Agregar miembro del staff' }}</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Selecciona un usuario ya registrado. Si no aparece, llena los datos mínimos para generar el enlace de completación.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeStaffModal"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-6 py-6">
                    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Buscar usuario registrado</label>
                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="staffLookup"
                                    placeholder="Escribe nombre, correo o identidad..."
                                    class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                                >
                            </div>

                            <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/30">
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Selecciona un usuario encontrado</label>
                                <select
                                    wire:model.live="staffPersonaId"
                                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                                >
                                    <option value="">No lo encontré en la lista</option>
                                    @foreach ($staffCandidates as $candidate)
                                        <option value="{{ $candidate->id }}">
                                            {{ trim($candidate->nombre . ' ' . $candidate->apellido) }} · {{ $candidate->correo ?: $candidate->dni }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                    Se precargan 5 resultados y al escribir arriba el listado se va filtrando.
                                </p>
                                @error('staffPersonaId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            @if ($selectedStaffCandidate)
                                <div class="rounded-[1.75rem] border border-violet-200 bg-violet-50 p-5 dark:border-violet-900/50 dark:bg-violet-950/20">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div>
                                            <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ trim($selectedStaffCandidate->nombre . ' ' . $selectedStaffCandidate->apellido) }}</p>
                                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $selectedStaffCandidate->correo }} · {{ $selectedStaffCandidate->dni }}</p>
                                        </div>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $selectedStaffCandidate->user ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' }}">
                                            {{ $selectedStaffCandidate->user ? 'Usuario encontrado' : 'Persona sin usuario' }}
                                        </span>
                                    </div>

                                    <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                        @if ($selectedStaffCandidate->user)
                                            Esta persona ya tiene usuario. Se agregará al staff del evento y se habilitará su acceso.
                                        @else
                                            Esta persona existe en la base, pero aún no tiene usuario. Puedes ajustar correo y teléfono para enviarle el enlace y que cree su acceso.
                                        @endif
                                    </p>
                                </div>
                            @elseif ($staffLookup !== '')
                                <div class="rounded-[1.75rem] border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/50 dark:bg-amber-950/20">
                                    <p class="text-sm leading-6 text-amber-800 dark:text-amber-200">
                                        Si no encontraste a la persona, completa los datos mínimos de la derecha para generarle su enlace de registro.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/30">
                            <div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Datos mínimos del miembro</h4>
                                <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                                    @if ($selectedStaffCandidate && ! $selectedStaffCandidate->user)
                                        Esta persona no tiene usuario. Puedes confirmar o ajustar estos datos para enviarle el enlace y que cree su acceso.
                                    @elseif ($selectedStaffCandidate && $selectedStaffCandidate->user)
                                        Los datos se completan desde el usuario seleccionado. No es necesario llenar manualmente esta sección.
                                    @else
                                        Si no existe en la lista, escribe el nombre completo, correo y teléfono. Desde el enlace terminará de crear su perfil.
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre completo</label>
                                <input type="text" wire:model.blur="staffName" @disabled($selectedStaffCandidate?->user) class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:disabled:bg-slate-900 dark:disabled:text-slate-500 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                                @error('staffName') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo</label>
                                <input type="email" wire:model.blur="staffEmail" @disabled($selectedStaffCandidate?->user) class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:disabled:bg-slate-900 dark:disabled:text-slate-500 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                                @error('staffEmail') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Telefono</label>
                                <input type="text" wire:model.blur="staffPhone" @disabled($selectedStaffCandidate?->user) class="block w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:disabled:bg-slate-900 dark:disabled:text-slate-500 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                                @error('staffPhone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
                    <button
                        type="button"
                        wire:click="closeStaffModal"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="saveStaffMember"
                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                    >
                        {{ $editingStaffId ? 'Guardar cambios' : 'Guardar miembro' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showRegistrationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeRegistrationModal"></div>

            <div class="relative flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Inscripción</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Editar datos personales</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Actualiza la información principal del participante inscrito en este evento.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeRegistrationModal"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-6 py-6">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Primer nombre <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="registrationPrimerNombre" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                            @error('registrationPrimerNombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Segundo nombre</label>
                            <input type="text" wire:model.blur="registrationSegundoNombre" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                            @error('registrationSegundoNombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Primer apellido <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="registrationPrimerApellido" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                            @error('registrationPrimerApellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Segundo apellido</label>
                            <input type="text" wire:model.blur="registrationSegundoApellido" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                            @error('registrationSegundoApellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo <span class="text-red-500">*</span></label>
                            <input type="email" wire:model.blur="registrationCorreo" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                            @error('registrationCorreo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Teléfono <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="registrationTelefono" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                            @error('registrationTelefono') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">DNI <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="registrationDni" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                            @error('registrationDni') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Dirección</label>
                            <input type="text" wire:model.blur="registrationDireccion" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10">
                            @error('registrationDireccion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
                    <button
                        type="button"
                        wire:click="closeRegistrationModal"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="saveRegistrationData"
                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-violet-500"
                    >
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showAttendanceParticipantsModal && $selectedAttendanceConference)
        <div class="fixed inset-0 z-[65] flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeAttendanceParticipantsModal"></div>

            <div class="relative flex max-h-[94vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Asistencia</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $selectedAttendanceConference->nombre }}</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ $selectedAttendanceConference->fecha ? \Illuminate\Support\Carbon::parse($selectedAttendanceConference->fecha)->format('d/m/Y') : 'Sin fecha' }}
                            ·
                            {{ $selectedAttendanceConference->horaInicio ? \Illuminate\Support\Carbon::parse($selectedAttendanceConference->horaInicio)->format('h:i a') : '--:--' }}
                            -
                            {{ $selectedAttendanceConference->horaFin ? \Illuminate\Support\Carbon::parse($selectedAttendanceConference->horaFin)->format('h:i a') : '--:--' }}
                            ·
                            {{ $selectedAttendanceConference->lugar ?: 'Lugar por definir' }}
                        </p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeAttendanceParticipantsModal"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-6 py-6">
                    <div class="space-y-5">
                            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto]">
                                <div class="relative">
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Buscar inscrito del evento</label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            wire:model.live.debounce.300ms="attendanceParticipantLookup"
                                            class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                                            placeholder="Busca y selecciona por nombre, DNI, correo o teléfono..."
                                            autocomplete="off"
                                        >

                                        @if (count($attendanceParticipantCandidates) && trim($attendanceParticipantLookup) !== trim($attendanceParticipantSelectedLabel))
                                            <div class="absolute left-0 right-0 top-[calc(100%+0.5rem)] z-20 overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_24px_60px_rgba(15,23,42,0.14)] dark:border-slate-700 dark:bg-slate-900">
                                                <div class="max-h-72 overflow-y-auto p-2">
                                                    @foreach ($attendanceParticipantCandidates as $candidate)
                                                        @php
                                                            $candidateName = trim(($candidate->persona?->nombre ?? '') . ' ' . ($candidate->persona?->apellido ?? '')) ?: 'Participante';
                                                            $candidateProfile = $candidate->tipoPerfil?->tipoperfil ?? $candidate->persona?->tipoPerfil?->tipoperfil ?? 'Sin perfil';
                                                        @endphp
                                                        <button
                                                            type="button"
                                                            wire:click="selectAttendanceParticipant({{ $candidate->id }})"
                                                            class="flex w-full items-start justify-between gap-4 rounded-2xl px-4 py-3 text-left transition hover:bg-slate-100 dark:hover:bg-slate-800"
                                                        >
                                                            <span class="min-w-0">
                                                                <span class="block truncate text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $candidateName }}</span>
                                                                <span class="mt-1 block truncate text-xs text-slate-500 dark:text-slate-400">
                                                                    {{ $candidate->persona?->dni ?: 'Sin DNI' }} · {{ $candidate->persona?->correo ?: 'Sin correo' }} · {{ $candidate->persona?->telefono ?: 'Sin teléfono' }}
                                                                </span>
                                                            </span>
                                                            <span class="inline-flex shrink-0 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                                {{ $candidateProfile }}
                                                            </span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @error('attendanceParticipantRegistroId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex items-end">
                                    <button
                                        type="button"
                                        wire:click="addAttendanceParticipant"
                                        class="inline-flex w-full items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700 lg:w-auto"
                                    >
                                        Agregar participante
                                    </button>
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300">
                                Solo se muestran participantes ya inscritos en este evento y que todavía no han sido agregados a la asistencia de esta conferencia.
                            </div>

                            <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50/70 p-5 dark:border-emerald-500/30 dark:bg-emerald-500/10">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">Autoasistencia por QR</p>
                                        <h4 class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Código para registro autónomo</h4>
                                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                                            Los participantes inscritos y con sesión iniciada podrán escanear este QR para marcar su asistencia a esta conferencia.
                                        </p>
                                    </div>

                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <button
                                            type="button"
                                            wire:click="generateAttendanceSelfCode"
                                            class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                                        >
                                            {{ $selectedAttendanceConference->codigo_auto_asistencia ? 'Regenerar código' : 'Generar código' }}
                                        </button>

                                        @if ($selectedAttendanceConference->codigo_auto_asistencia)
                                            <button
                                                type="button"
                                                wire:click="downloadAttendanceSelfQr"
                                                class="inline-flex items-center justify-center rounded-2xl border border-emerald-300 bg-white px-5 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50 dark:border-emerald-500/40 dark:bg-slate-900 dark:text-emerald-300 dark:hover:bg-emerald-500/10"
                                            >
                                                Descargar QR
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                @if ($selectedAttendanceConference->codigo_auto_asistencia)
                                    <div class="mt-5 grid gap-4 lg:grid-cols-[auto_minmax(0,1fr)]">
                                        <div class="rounded-3xl border border-white/80 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                                            <img class="h-40 w-40" src="data:image/png;base64,{{ $attendanceSelfQrCode }}" alt="QR de autoasistencia">
                                        </div>

                                        <div class="rounded-3xl border border-emerald-100 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
                                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Código activo</p>
                                            <p class="mt-2 font-mono text-3xl font-black tracking-[0.35em] text-emerald-700 dark:text-emerald-300">{{ $selectedAttendanceConference->codigo_auto_asistencia }}</p>
                                            <p class="mt-3 break-all text-sm text-slate-500 dark:text-slate-400">{{ route('asistencia.auto', ['code' => $selectedAttendanceConference->codigo_auto_asistencia]) }}</p>
                                            <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                                                Generado {{ optional($selectedAttendanceConference->codigo_auto_asistencia_generado_en)->format('d/m/Y h:i a') ?: 'recientemente' }}.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Participantes que asistieron</h4>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Registra manualmente a quienes ya ingresaron a la conferencia o taller.</p>
                                </div>

                                <div class="flex flex-col gap-3 sm:flex-row">
                                    <div class="w-full sm:w-72">
                                        <label for="attendance-participants-search" class="sr-only">Buscar participante agregado</label>
                                        <input
                                            id="attendance-participants-search"
                                            type="text"
                                            wire:model.live.debounce.300ms="attendanceParticipantSearch"
                                            class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                                            placeholder="Buscar en la lista..."
                                        >
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="downloadAttendanceListPdf"
                                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                                    >
                                        Descargar listado
                                    </button>
                                </div>
                            </div>

                            @if ($attendanceParticipants && $attendanceParticipants->count())
                                <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-left text-sm">
                                            <thead class="bg-slate-50 text-xs uppercase tracking-[0.24em] text-slate-500 dark:bg-slate-950/70 dark:text-slate-400">
                                                <tr>
                                                    <th class="px-5 py-4">Nº.</th>
                                                    <th class="px-5 py-4">Participante</th>
                                                    <th class="px-5 py-4">Perfil</th>
                                                    <th class="px-5 py-4">DNI</th>
                                                    <th class="px-5 py-4">Telefono</th>
                                                    <th class="px-5 py-4">Marcado</th>
                                                    <th class="px-5 py-4 text-right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                                @foreach ($attendanceParticipants as $index => $attendance)
                                                    @php
                                                        $person = $attendance->eventoRegistro?->persona;
                                                        $profile = $attendance->eventoRegistro?->tipoPerfil?->tipoperfil
                                                            ?? $person?->tipoPerfil?->tipoperfil
                                                            ?? 'Sin perfil';
                                                        $rowNumber = (($attendanceParticipants->currentPage() - 1) * $attendanceParticipants->perPage()) + $index + 1;
                                                    @endphp
                                                    <tr class="align-top">
                                                        <td class="px-5 py-4 font-semibold text-slate-500 dark:text-slate-400">{{ $rowNumber }}</td>
                                                        <td class="px-5 py-4">
                                                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ trim(($person?->nombre ?? '') . ' ' . ($person?->apellido ?? '')) ?: 'Participante' }}</p>
                                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $person?->correo ?: 'Sin correo' }}</p>
                                                        </td>
                                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $profile }}</td>
                                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $person?->dni ?: 'Sin DNI' }}</td>
                                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $person?->telefono ?: 'Sin teléfono' }}</td>
                                                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $attendance->checked_in_at?->format('d/m/Y h:i a') ?: $attendance->created_at?->format('d/m/Y h:i a') }}</td>
                                                        <td class="px-5 py-4">
                                                            <div class="flex justify-end">
                                                                <div
                                                                    x-data="{
                                                                        open: false,
                                                                        menuStyle: '',
                                                                        toggle() {
                                                                            this.open = !this.open;
                                                                            if (this.open) this.updatePosition();
                                                                        },
                                                                        close() {
                                                                            this.open = false;
                                                                        },
                                                                        updatePosition() {
                                                                            const rect = this.$refs.trigger.getBoundingClientRect();
                                                                            this.menuStyle = `top:${rect.bottom + 10}px;left:${Math.max(16, rect.right - 280)}px;`;
                                                                        }
                                                                    }"
                                                                    x-on:resize.window="if (open) updatePosition()"
                                                                    x-on:scroll.window="if (open) updatePosition()"
                                                                    class="relative inline-flex justify-end"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        x-ref="trigger"
                                                                        x-on:click="toggle()"
                                                                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-800"
                                                                    >
                                                                        <span class="sr-only">Abrir acciones</span>
                                                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path d="M10 4a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm0 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm0 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z" />
                                                                        </svg>
                                                                    </button>

                                                                    <template x-teleport="body">
                                                                        <div
                                                                            x-show="open"
                                                                            x-transition.opacity.duration.150ms
                                                                            x-on:click.self="close()"
                                                                            class="fixed inset-0 z-[85] pointer-events-auto"
                                                                        >
                                                                            <div
                                                                                x-show="open"
                                                                                x-transition.scale.origin.top.right.duration.150ms
                                                                                x-bind:style="menuStyle"
                                                                                class="fixed z-[90] w-72 rounded-[1.5rem] border border-slate-200 bg-white p-2 text-left shadow-[0_24px_60px_rgba(15,23,42,0.18)] dark:border-slate-700 dark:bg-slate-900"
                                                                            >
                                                                                <button
                                                                                    type="button"
                                                                                    x-on:click="close()"
                                                                                    wire:click="editRegistration({{ $attendance->evento_registro_id }})"
                                                                                    class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
                                                                                >
                                                                                    Editar datos
                                                                                </button>
                                                                                <button
                                                                                    type="button"
                                                                                    x-on:click="close()"
                                                                                    wire:click="confirmDeleteAttendanceParticipant({{ $attendance->id }})"
                                                                                    class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-950/30"
                                                                                >
                                                                                    Quitar 
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 border-t border-slate-200 pt-4 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        Mostrando {{ $attendanceParticipants->firstItem() }} - {{ $attendanceParticipants->lastItem() }} de {{ $attendanceParticipants->total() }} asistentes registrados.
                                    </p>
                                    {{ $attendanceParticipants->links() }}
                                </div>
                            @else
                                <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700">
                                    <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $attendanceParticipantSearch !== '' ? 'No hay participantes que coincidan con la búsqueda.' : 'Aún no hay asistencias registradas para esta conferencia.' }}</p>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $attendanceParticipantSearch !== '' ? 'Prueba con otra búsqueda en el listado.' : 'Selecciona un participante inscrito del evento y agrégalo a esta lista.' }}
                                    </p>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showDeleteConfirmModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeDeleteConfirmModal"></div>

            <div class="relative w-full max-w-lg rounded-[2rem] border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rose-500 dark:text-rose-300">Confirmación</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">
                            {{ $deleteContext === 'staff' ? 'Eliminar miembro del staff' : ($deleteContext === 'registration' ? 'Eliminar inscripción' : ($deleteContext === 'attendance' ? 'Eliminar asistencia' : 'Eliminar invitado')) }}
                        </h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Esta acción eliminará el registro de <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $deleteRecordLabel }}</span>.
                            Confirma solo si deseas retirarlo de este evento.
                        </p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeDeleteConfirmModal"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        wire:click="closeDeleteConfirmModal"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="deleteSelectedRecord"
                        class="inline-flex items-center justify-center rounded-2xl bg-rose-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-rose-700"
                    >
                        Sí, eliminar registro
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showStaffLinkModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeStaffLinkModal"></div>

            <div class="relative w-full max-w-2xl rounded-[2rem] border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-500 dark:text-violet-300">Acceso del staff</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Enlace de completación</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Comparte este enlace con el miembro del staff para que complete su perfil y quede habilitado con permisos del evento.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeStaffLinkModal"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                        <p class="break-all text-sm text-slate-700 dark:text-slate-200">{{ $previewStaffLink }}</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="button"
                            x-data
                            x-on:click="navigator.clipboard.writeText(@js($previewStaffLink))"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            Copiar enlace
                        </button>
                        <a
                            href="{{ $previewStaffLink }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                        >
                            Abrir enlace
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showStaffBadgePreviewModal)
        @php
            $previewStaffMember = $this->selectedStaffMember();
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeStaffBadgePreview"></div>

            <div class="relative flex max-h-[94vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Vista previa</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Gafete del staff</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Revisa el gafete antes de enviarlo o descargarlo. El QR identifica al miembro del staff para el acceso al evento.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeStaffBadgePreview"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="grid gap-6 overflow-y-auto px-6 py-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950">
                            @if ($previewStaffBadgeImageDataUri)
                                <img src="{{ $previewStaffBadgeImageDataUri }}" alt="Vista final del gafete del staff" class="h-auto w-full object-contain bg-white">
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Detalle del miembro</p>
                            <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Nombre:</span> {{ $previewStaffMember?->nombre }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Correo:</span> {{ $previewStaffMember?->correo ?: 'Sin correo' }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Teléfono:</span> {{ $previewStaffMember?->telefono ?: 'Sin teléfono' }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Código de acceso:</span> {{ $previewStaffMember ? ('STAFF-' . strtoupper(substr((string) $previewStaffMember->staff_access_token, 0, 8))) : 'Sin código' }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Perfil:</span> {{ $previewStaffMember?->persona?->tipoPerfil?->tipoperfil ?? 'Staff' }}</p>
                            </div>
                        </div>

                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Canales de entrega</p>
                            <div class="mt-4 space-y-3">
                                <button
                                    type="button"
                                    wire:click="downloadStaffBadgeImage"
                                    class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                                >
                                    Descargar gafete en imagen
                                </button>
                                <a
                                    href="#"
                                    wire:click.prevent="sendStaffBadgeByEmail"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-500"
                                >
                                    Enviar por correo
                                </a>
                                <a
                                    href="#"
                                    wire:click.prevent="sendStaffBadgeByWhatsapp"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-400"
                                >
                                    Enviar por WhatsApp
                                </a>
                            </div>
                            <p class="mt-4 text-xs leading-6 text-slate-500 dark:text-slate-400">
                                Si el evento no tiene una plantilla de gafete del staff configurada, se usa un diseño genérico con el nombre del evento, el nombre del miembro y su QR de acceso.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showDocumentGenerationConfirmModal)
        @php
            $documentTaskDefinition = $documentGenerationDefinitions[$documentGenerationType] ?? null;
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeDocumentGenerationConfirmModal"></div>

            <div class="relative flex w-full max-w-2xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Confirmación</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">
                            {{ $documentTaskDefinition['label'] ?? 'Generar documento' }}
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                            {{ $documentTaskDefinition['description'] ?? 'Se iniciará un proceso en segundo plano.' }}
                        </p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeDocumentGenerationConfirmModal"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-6">
                    <div class="rounded-[1.5rem] border border-violet-100 bg-violet-50/70 p-5 dark:border-violet-900/40 dark:bg-violet-950/20">
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">El proceso se ejecutará en segundo plano</p>
                        <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                            <li>Podrás seguir usando esta pantalla mientras se generan los lotes del PDF.</li>
                            <li>El avance aparecerá debajo del botón correspondiente dentro de esta sección.</li>
                            <li>Cuando termine, se habilitará el botón para guardar el PDF ya generado.</li>
                        </ul>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
                    <button
                        type="button"
                        wire:click="closeDocumentGenerationConfirmModal"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="confirmDocumentGeneration"
                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                    >
                        Confirmar generación
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showRegistrationBadgePreviewModal)
        @php
            $previewRegistration = $this->selectedRegistrationRecord();
            $participantDesign = $this->participantBadgeDesign();
            $participantDesignUrl = $this->participantBadgeDesignUrl();
            $participantDesignConfigured = $this->participantBadgeDesignConfigured();
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeRegistrationBadgePreview"></div>

            <div class="relative flex max-h-[94vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Vista previa</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Gafete del participante</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Revisa el gafete antes de descargarlo. El QR identifica la inscripción del participante dentro del evento.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeRegistrationBadgePreview"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="grid gap-6 overflow-y-auto px-6 py-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="relative mx-auto w-full max-w-4xl overflow-hidden border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950" style="{{ $this->designPreviewStyle($participantDesign['page_size'] ?? 'Carta', $participantDesign['orientation'] ?? 'Vertical') }}">
                            @if ($participantDesignConfigured && $participantDesignUrl)
                                <img src="{{ $participantDesignUrl }}" alt="Plantilla de gafete de participante" class="h-full w-full object-contain bg-white">
                            @else
                                <img src="{{ $previewRegistrationBadgeImageDataUri }}" alt="Gafete del participante" class="h-full w-full object-contain bg-white">
                            @endif

                            @if ($participantDesignConfigured && $previewRegistration)
                                <div class="pointer-events-none absolute inset-0">
                                    <div class="absolute font-semibold uppercase tracking-[0.01em]" style="{{ $this->participantBadgeNameStyle($previewRegistration) }}">
                                        {{ trim(($previewRegistration->persona?->nombre ?? '') . ' ' . ($previewRegistration->persona?->apellido ?? '')) ?: 'Participante' }}
                                    </div>
                                    <div class="absolute overflow-hidden rounded-lg border-2 border-slate-900/80 bg-white shadow-sm dark:border-white/70" style="{{ $this->designElementStyle($this->participantBadgeCoordinate('qr', 'x'), $this->participantBadgeCoordinate('qr', 'y'), $participantDesign['qr_size'] ?? 18, 'qr') }}">
                                        @if ($previewRegistrationBadgeQrCode)
                                            <img src="data:image/png;base64,{{ $previewRegistrationBadgeQrCode }}" alt="Código QR del participante" class="h-full w-full object-contain bg-white">
                                        @endif
                                    </div>
                                    <div class="absolute" style="{{ $this->participantBadgeQrCaptionStyle() }}">
                                        Acceso participante
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Detalle del participante</p>
                            <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Nombre:</span> {{ trim(($previewRegistration?->persona?->nombre ?? '') . ' ' . ($previewRegistration?->persona?->apellido ?? '')) ?: 'Sin nombre' }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Perfil:</span> {{ $previewRegistration?->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Correo:</span> {{ $previewRegistration?->persona?->correo ?: 'Sin correo' }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Código de acceso:</span> {{ $previewRegistration ? ('REG-' . $evento->id . '-' . $previewRegistration->id) : 'Sin código' }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Estado:</span> {{ ucfirst(str_replace('_', ' ', $previewRegistration?->estado ?: 'sin estado')) }}</p>
                            </div>
                        </div>

                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Entrega</p>
                            <div class="mt-4 space-y-3">
                                <button
                                    type="button"
                                    wire:click="downloadRegistrationBadgeImage"
                                    class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                                >
                                    Descargar gafete en imagen
                                </button>
                            </div>
                            <p class="mt-4 text-xs leading-6 text-slate-500 dark:text-slate-400">
                                Si el evento tiene plantilla de gafete de participantes, se respeta esa configuración. En caso contrario se usa un diseño genérico claro.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showInvitationPreviewModal)
        @php
            $previewInvitation = $this->selectedInvitation();
            $design = $this->invitationDesign();
            $designUrl = $this->invitationDesignUrl();
            $designConfigured = $this->invitationDesignConfigured();
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeInvitationPreview"></div>

            <div class="relative flex max-h-[94vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Vista previa</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Invitación a enviar</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Revisa la invitación antes de enviarla por correo o WhatsApp. El QR contiene el código único de acceso del invitado.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeInvitationPreview"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="grid gap-6 overflow-y-auto px-6 py-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                        <div class="relative mx-auto w-full max-w-4xl overflow-hidden border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950" style="{{ $this->designPreviewStyle($design['page_size'] ?? 'Carta', $design['orientation'] ?? 'Horizontal') }}">
                            @if ($designConfigured && $designUrl)
                                <img src="{{ $designUrl }}" alt="Plantilla de invitación" class="h-full w-full object-contain bg-white">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(123,92,255,0.24),_transparent_26%),linear-gradient(135deg,_#0f172a_0%,_#312e81_48%,_#1d4ed8_100%)] px-8 py-10 text-white">
                                    <div class="w-full">
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-violet-200">{{ $evento->tipoEvento?->tipo ?? 'Evento' }}</p>
                                        <p class="mt-4 text-3xl font-black tracking-[-0.04em] sm:text-4xl">{{ $evento->nombreevento }}</p>
                                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-100 sm:text-base">{{ \Illuminate\Support\Str::words($evento->descripcion, 28, '...') }}</p>
                                        <div class="mt-6 flex flex-wrap gap-3 text-xs font-medium text-slate-100 sm:text-sm">
                                            <span class="rounded-full bg-white/12 px-3 py-1.5">{{ $evento->fechainicio?->format('d/m/Y') }} - {{ $evento->fechafinal?->format('d/m/Y') }}</span>
                                            <span class="rounded-full bg-white/12 px-3 py-1.5">{{ $evento->localidad_display }}</span>
                                            <span class="rounded-full bg-white/12 px-3 py-1.5">{{ $previewInvitation?->cupos }} cupos</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="pointer-events-none absolute inset-0">
                                <div class="absolute font-semibold uppercase tracking-[0.01em]" style="{{ $this->invitationNameStyle($previewInvitation) }}">
                                    {{ $previewInvitation?->nombre_invitado ?: 'Nombre del invitado' }}
                                </div>
                                <div class="absolute overflow-hidden rounded-lg border-2 border-slate-900/80 bg-white shadow-sm dark:border-white/70" style="{{ $this->designElementStyle($this->invitationCoordinate('qr', 'x'), $this->invitationCoordinate('qr', 'y'), $design['qr_size'] ?? 18, 'qr') }}">
                                    @if ($previewQrCode)
                                        <img src="data:image/png;base64,{{ $previewQrCode }}" alt="Código QR de invitación" class="h-full w-full object-contain bg-white">
                                    @endif
                                </div>
                                <div class="absolute" style="{{ $this->invitationQrCaptionStyle() }}">
                                    Válido por {{ $previewInvitation?->cupos ?? 1 }} {{ \Illuminate\Support\Str::plural('cupo', $previewInvitation?->cupos ?? 1) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Detalle del invitado</p>
                            <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Nombre:</span> {{ $previewInvitation?->nombre_invitado }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Correo:</span> {{ $previewInvitation?->correo_invitado }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Teléfono:</span> {{ $previewInvitation?->telefono_invitado }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Código único:</span> {{ $previewInvitation?->codigo }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Cupos:</span> {{ $previewInvitation?->cupos }}</p>
                                <p><span class="font-semibold text-slate-900 dark:text-slate-100">Enviada:</span> {{ $previewInvitation?->enviada_at ? $previewInvitation->enviada_at->format('d/m/Y H:i') : 'No' }}</p>
                            </div>
                        </div>

                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Canales de envío</p>
                            <div class="mt-4 space-y-3">
                                <button
                                    type="button"
                                    wire:click="downloadInvitationImage"
                                    class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                                >
                                    Descargar invitación en imagen
                                </button>
                                <a
                                    href="#"
                                    wire:click.prevent="sendInvitationByEmail"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-500"
                                >
                                    Enviar por correo
                                </a>
                                <a
                                    href="#"
                                    wire:click.prevent="sendInvitationByWhatsapp"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-400"
                                >
                                    Enviar por WhatsApp
                                </a>
                            </div>
                            <p class="mt-4 text-xs leading-6 text-slate-500 dark:text-slate-400">
                                Por ahora el envío sale vía cliente de correo o enlace de WhatsApp. Después conectaremos el envío automático desde integraciones API.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
