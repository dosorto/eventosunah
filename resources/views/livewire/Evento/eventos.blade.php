<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Eventos</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Crea eventos, completa su formulación y entra a su configuración para publicarlos.</p>
        </div>
        <button
            type="button"
            wire:click="create"
            class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-yellow-400"
        >
            Nuevo evento
        </button>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                Busca rápidamente por nombre del evento, organizador, descripción, localidad o tipo.
            </div>
            <div class="relative w-full sm:max-w-sm">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar evento..."
                    class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10"
                >
            </div>
        </div>

        <div class="mt-5">
            @if (session()->has('message'))
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-5 grid gap-4 sm:grid-cols-3">
                <article class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Total filtrado</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $stats['total'] }}</p>
                </article>
                <article class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">En formulacion</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $stats['formulacion'] }}</p>
                </article>
                <article class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Publicados</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $stats['publicados'] }}</p>
                </article>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                        <tr class="text-left text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                            <th class="px-6 py-4">
                                <button type="button" wire:click="sortBy('nombreevento')" class="inline-flex items-center gap-2 transition hover:text-slate-900 dark:hover:text-white">
                                    Evento
                                    @if ($sortField === 'nombreevento')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-4">
                                <button type="button" wire:click="sortBy('estado')" class="inline-flex items-center gap-2 transition hover:text-slate-900 dark:hover:text-white">
                                    Estado
                                    @if ($sortField === 'estado')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-4">Clasificacion</th>
                            <th class="px-6 py-4">
                                <button type="button" wire:click="sortBy('organizador')" class="inline-flex items-center gap-2 transition hover:text-slate-900 dark:hover:text-white">
                                    Organizacion
                                    @if ($sortField === 'organizador')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-4">
                                <button type="button" wire:click="sortBy('published_at')" class="inline-flex items-center gap-2 transition hover:text-slate-900 dark:hover:text-white">
                                    Publicacion
                                    @if ($sortField === 'published_at')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse ($eventos as $evento)
                            <tr
                                wire:click="openEvent({{ $evento->id }})"
                                class="cursor-pointer text-slate-700 transition hover:bg-slate-50/90 dark:text-slate-200 dark:hover:bg-slate-800/30"
                            >
                                <td class="px-6 py-5">
                                    <div class="flex items-start gap-4">
                                        <div class="h-14 w-14 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 dark:border-slate-800 dark:bg-slate-950">
                                            @if ($evento->banner_url)
                                                <img
                                                    src="{{ $evento->banner_url }}"
                                                    alt="{{ $evento->nombreevento }}"
                                                    class="h-full w-full object-cover"
                                                >
                                            @elseif ($evento->logo_url)
                                                <img
                                                    src="{{ $evento->logo_url }}"
                                                    alt="{{ $evento->nombreevento }}"
                                                    class="h-full w-full object-cover"
                                                >
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">
                                                    Sin logo
                                                </div>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $evento->nombreevento }}</p>
                                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $evento->organizador }}</p>
                                            <p class="mt-2 line-clamp-3 text-sm text-slate-500 dark:text-slate-400">{{ $evento->descripcion }}</p>
                                            <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">{{ $evento->conferencias_count }} conferencias</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $evento->estado === 'publicado' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' }}">
                                        {{ $evento->estado === 'publicado' ? 'Publicado' : 'Formulacion' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $evento->tipoEvento?->tipo ?? 'Sin tipo' }}</p>
                                    <p>{{ $evento->modalidad?->modalidad ?? 'Sin modalidad' }}</p>
                                    <p class="mt-1">{{ $evento->localidad_display }}</p>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $evento->organizador }}</p>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">
                                    <p class="font-medium text-slate-900 dark:text-slate-100">
                                        {{ $evento->published_at?->format('d/m/Y H:i') ?? 'Pendiente' }}
                                    </p>
                                </td>
                                <td class="px-6 py-5">
                                    <button
                                        type="button"
                                        wire:click.stop="openEvent({{ $evento->id }})"
                                        class="inline-flex items-center justify-center rounded-2xl border border-yellow-300 px-4 py-2 text-sm font-medium text-yellow-700 transition hover:bg-yellow-50 dark:border-yellow-800 dark:text-yellow-300 dark:hover:bg-yellow-950/30"
                                    >
                                        {{ $evento->estado === 'publicado' ? 'Gestionar' : 'Configuración' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                    No hay eventos registrados con los filtros actuales.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $eventos->links() }}
            </div>
        </div>
    </div>

    @if ($isOpen)
        @include('livewire.Evento.create')
    @endif

    @if ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="$set('confirmingDelete', false)"></div>

            <div class="relative w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-500">Eliminar</p>
                <h3 class="mt-2 text-xl font-semibold text-slate-900 dark:text-slate-100">Confirmar eliminacion</h3>
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">
                    Se eliminara el evento <span class="font-medium text-slate-900 dark:text-slate-100">{{ $nombreEventoAEliminar }}</span>. Esta accion no se puede deshacer.
                </p>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        wire:click="$set('confirmingDelete', false)"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="delete"
                        class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-red-500"
                    >
                        Eliminar evento
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
