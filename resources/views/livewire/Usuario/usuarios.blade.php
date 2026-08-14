<div class="space-y-6">
    @if (session()->has('message'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Seguridad</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Usuarios del sistema</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Administra accesos, correos y roles asignados a cada usuario registrado en la plataforma.</p>
        </div>

        <button
            type="button"
            wire:click="create"
            class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-yellow-400"
        >
            Nuevo usuario
        </button>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px]">
            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Buscar por nombre o correo..."
                    class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                >
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400">
                {{ $users->total() }} usuarios encontrados
            </div>
        </div>

        <div class="mt-5 overflow-x-auto rounded-3xl border border-slate-200 dark:border-slate-800">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/60 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Usuario</th>
                        <th class="px-5 py-4">Correo</th>
                        <th class="px-5 py-4">Roles</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($users as $user)
                        <tr class="bg-white text-slate-700 dark:bg-transparent dark:text-slate-200">
                            <td class="px-5 py-4 font-medium text-slate-900 dark:text-slate-100">{{ $user->id }}</td>
                            <td class="px-5 py-4">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $user->persona ? 'Vinculado a persona' : 'Sin persona vinculada' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse ($user->roles as $role)
                                        <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700 dark:bg-violet-950/40 dark:text-violet-300">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                            Sin roles
                                        </span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                    Activo
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $user->id }})"
                                        class="inline-flex items-center justify-center rounded-2xl border border-yellow-300 px-4 py-2 text-sm font-medium text-yellow-700 transition hover:bg-yellow-50 dark:border-yellow-800 dark:text-yellow-300 dark:hover:bg-yellow-950/30"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $user->id }})"
                                        class="inline-flex items-center justify-center rounded-2xl border border-red-300 px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-950/30"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">No hay usuarios para mostrar.</p>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Prueba con otra búsqueda o crea un nuevo usuario.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $users->links() }}
        </div>
    </div>

    @if($isOpen)
        @include('livewire.usuario.create')
    @endif

    @if ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="$set('confirmingDelete', false)"></div>

            <div class="relative w-full max-w-lg rounded-[2rem] border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Confirmar eliminación</h3>
                <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                    ¿Estás seguro de que deseas eliminar el usuario <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $nombreAEliminar }}</span>? Esta acción no se puede deshacer.
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
                        Eliminar usuario
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
