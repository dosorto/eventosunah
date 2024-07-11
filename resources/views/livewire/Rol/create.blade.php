<div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="isOpen">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form wire:submit.prevent="store" class="bg-white p-4">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold">Nombre del rol</label>
                    <input wire:model="name" type="text" name="name" class="form-input mt-1 block w-full rounded-md border-gray-300" id="name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Selecciona los permisos</label>
                    <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($permissions as $permission)
                            <label class="flex items-center">
                                <input wire:model="selectedPermissions" type="checkbox" name="selectedPermissions[]" value="{{ $permission->id }}" class="form-checkbox">
                                <span class="ml-2 text-gray-800">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedPermissions')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="button" wire:click="$set('isOpen', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg mr-2">Cancelar</button>
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
