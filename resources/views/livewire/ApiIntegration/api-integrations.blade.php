<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Integraciones API</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Configura las rutas, autenticación y mapeos para consultar personas según el tipo de perfil.</p>
        </div>
        <button wire:click="create" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-yellow-400">
            Nueva integración
        </button>
    </div>

    @if (session()->has('message'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                Usa `lookup_path` con `{identifier}` si el identificador viaja dentro de la ruta. En `field_map` utiliza notación punto para mapear la respuesta JSON.
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar integración o tipo de perfil..." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 sm:max-w-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-yellow-400 dark:focus:ring-yellow-500/10">
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Tipo de perfil</th>
                        <th class="px-4 py-3">Base URL</th>
                        <th class="px-4 py-3">Método</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach ($integrations as $integration)
                        <tr class="text-slate-700 dark:text-slate-200">
                            <td class="px-4 py-4 font-medium">{{ $integration->nombre }}</td>
                            <td class="px-4 py-4">{{ $integration->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</td>
                            <td class="px-4 py-4">{{ $integration->base_url }}</td>
                            <td class="px-4 py-4">{{ $integration->http_method }}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] {{ $integration->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                    {{ $integration->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $integration->id }})" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Editar</button>
                                    <button wire:click="confirmDelete({{ $integration->id }})" class="rounded-2xl border border-red-300 px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-950/20">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $integrations->links() }}
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
            <div class="max-h-full w-full max-w-5xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $integration_id ? 'Editar integración API' : 'Nueva integración API' }}</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Configura autenticación, endpoint y mapeo de respuesta para el tipo de perfil seleccionado.</p>
                    </div>
                    <button wire:click="closeModal" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="grid gap-5 px-6 py-6">
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre <span class="text-red-500">*</span></label>
                            <input wire:model.live="nombre" type="text" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            @error('nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Tipo de perfil <span class="text-red-500">*</span></label>
                            <select wire:model.live="tipoperfil_id" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                <option value="">Selecciona un tipo</option>
                                @foreach ($tiposPerfil as $tipoPerfil)
                                    <option value="{{ $tipoPerfil->id }}">{{ $tipoPerfil->tipoperfil }}</option>
                                @endforeach
                            </select>
                            @error('tipoperfil_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Base URL <span class="text-red-500">*</span></label>
                            <input wire:model.live="base_url" type="url" placeholder="https://api.midominio.hn" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            @error('base_url') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Ruta de búsqueda <span class="text-red-500">*</span></label>
                            <input wire:model.live="lookup_path" type="text" placeholder="/personas/{identifier}" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            @error('lookup_path') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Método <span class="text-red-500">*</span></label>
                            <select wire:model.live="http_method" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                                <option value="PUT">PUT</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Autenticación <span class="text-red-500">*</span></label>
                            <select wire:model.live="auth_type" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                <option value="none">Ninguna</option>
                                <option value="bearer">Bearer token</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Parámetro query</label>
                            <input wire:model.live="identifier_query_key" type="text" placeholder="identidad" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Timeout <span class="text-red-500">*</span></label>
                            <input wire:model.live="timeout_seconds" type="number" min="1" max="60" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            @error('timeout_seconds') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Token</label>
                            <textarea wire:model.live="auth_token" rows="3" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"></textarea>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Ruta interna de respuesta</label>
                            <input wire:model.live="response_path" type="text" placeholder="data.persona" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Headers JSON</label>
                            <textarea wire:model.live="headers_json" rows="10" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 font-mono text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"></textarea>
                            @error('headers_json') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Mapeo de campos JSON</label>
                            <p class="mb-2 text-xs text-slate-500 dark:text-slate-400">Usa como llaves los campos internos: primer_nombre, segundo_nombre, primer_apellido y segundo_apellido.</p>
                            <textarea wire:model.live="field_map_json" rows="10" class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 font-mono text-sm text-slate-900 shadow-sm outline-none transition focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"></textarea>
                            @error('field_map_json') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <label class="flex items-center gap-3 text-sm text-slate-700 dark:text-slate-200">
                        <input wire:model.live="is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-yellow-500 focus:ring-yellow-500">
                        Integración activa
                    </label>

                    <div class="flex flex-col gap-3 border-t border-slate-200 pt-5 dark:border-slate-800 sm:flex-row sm:justify-end">
                        <button type="button" wire:click="closeModal" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-yellow-500 px-5 py-3 text-sm font-medium text-slate-950 transition hover:bg-yellow-400">
                            Guardar integración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-6 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-[2rem] border border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Eliminar integración</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Esta acción no se puede deshacer.</p>
                </div>
                <div class="px-6 py-6 text-sm text-slate-600 dark:text-slate-300">
                    ¿Deseas eliminar la integración <span class="font-semibold text-slate-900 dark:text-slate-100">"{{ $deleteName }}"</span>?
                </div>
                <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 dark:border-slate-800 sm:flex-row sm:justify-end">
                    <button wire:click="$set('confirmingDelete', false)" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Cancelar
                    </button>
                    <button wire:click="delete" class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-red-500">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
