<div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="closeModal"></div>

    <div class="relative flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
        <form wire:submit.prevent="store">
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Seguridad</p>
                    <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $user ? 'Editar usuario' : 'Crear usuario' }}</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Configura los datos de acceso y asigna uno o más roles para controlar los permisos del usuario.</p>
                </div>
                <button
                    wire:click="closeModal"
                    type="button"
                    class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    <span class="sr-only">Cerrar modal</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-6 py-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="name" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre <span class="text-red-500">*</span></label>
                        <input
                            id="name"
                            wire:model.defer="name"
                            type="text"
                            class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                            placeholder="Nombre de usuario"
                        >
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo electrónico <span class="text-red-500">*</span></label>
                        <input
                            id="email"
                            wire:model.defer="email"
                            type="email"
                            class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                            placeholder="usuario@correo.com"
                        >
                        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">
                            Contraseña @if(!$user)<span class="text-red-500">*</span>@endif
                        </label>
                        <input
                            id="password"
                            wire:model.defer="password"
                            type="password"
                            class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                            placeholder="{{ $user ? 'Dejar vacío para conservarla' : 'Contraseña segura' }}"
                        >
                        @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Confirmar contraseña</label>
                        <input
                            id="password_confirmation"
                            wire:model.defer="password_confirmation"
                            type="password"
                            class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                            placeholder="Repite la contraseña"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Roles <span class="text-red-500">*</span></label>
                        <div class="grid gap-3 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40 sm:grid-cols-2">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 transition hover:border-violet-300 hover:bg-violet-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-violet-700 dark:hover:bg-violet-950/20">
                                    <input
                                        wire:model.defer="selectedRoles"
                                        type="checkbox"
                                        value="{{ $role->id }}"
                                        id="role-{{ $role->id }}"
                                        class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                                    >
                                    <span>{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedRoles') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        @error('selectedRoles.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
                <button
                    wire:click="closeModal"
                    type="button"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-violet-700"
                >
                    {{ $user ? 'Guardar cambios' : 'Crear usuario' }}
                </button>
            </div>
        </form>
    </div>
</div>
