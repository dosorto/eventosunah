<div>
    <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400" x-data="{ refreshAfterCreate: @entangle('isOpen') }" x-init="refreshAfterCreate = false">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <form wire:submit.prevent="store">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-900">
                        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user ? 'Editar Usuario' : 'Crear Usuario' }}</h3>
                            <button wire:click="$emit('closeModal')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Cerrar modal</span>
                            </button>
                        </div>
                        <div>
                            <!-- Name Field -->
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-semibold">Nombre</label>
                                <input wire:model.defer="name" type="text" name="name" class="form-input mt-1 block w-full rounded-md border-gray-300" id="name">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-semibold">Correo electrónico</label>
                                <input wire:model.defer="email" type="email" name="email" class="form-input mt-1 block w-full rounded-md border-gray-300" id="email">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 font-semibold">Contraseña</label>
                                <input wire:model.defer="password" type="password" name="password" class="form-input mt-1 block w-full rounded-md border-gray-300" id="password">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Password Confirmation Field -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-gray-700 font-semibold">Confirmar Contraseña</label>
                                <input wire:model.defer="password_confirmation" type="password" name="password_confirmation" class="form-input mt-1 block w-full rounded-md border-gray-300" id="password_confirmation">
                                @error('password_confirmation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Roles Field -->
                            <div class="mb-4">
                                <label for="roles" class="block text-gray-700 font-semibold">Roles</label>
                                @foreach($roles as $role)
                                    <div class="flex items-center">
                                        <input wire:model.defer="selectedRoles" type="checkbox" value="{{ $role->id }}" id="role-{{ $role->id }}">
                                        <label for="role-{{ $role->id }}" class="ml-2">{{ $role->name }}</label>
                                    </div>
                                @endforeach
                                @error('selectedRoles')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse  dark:bg-gray-700">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-yellow-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Guardar
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button wire:click="closeModal()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancelar
                        </button>
                    </span>
                </div>
                </form>
            </div>
        </div>
    </div>