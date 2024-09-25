<div x-data="{ showDeleteModal: @entangle('showDeleteModal') }">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Pago del Evento: {{ $evento->nombreevento }}
    </h2>

    <div class="dark:bg-gray-900">
        <div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800">
                @if (session()->has('message'))
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="realizarPago" class="mb-4" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block mb-1 dark:text-gray-300">Nombre completo:</label>
                        <input type="text" class="bg-gray-100 dark:bg-gray-700 dark:text-white w-full p-2 rounded" disabled value="{{ $persona->nombre }} {{ $persona->apellido }}">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 dark:text-gray-300">Correo:</label>
                        <input type="email" class="bg-gray-100 dark:bg-gray-700 dark:text-white w-full p-2 rounded" disabled value="{{ $persona->correo }}">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 dark:text-gray-300">Foto del Pago:</label>
                        <input type="file" wire:model="foto" class="bg-gray-100 dark:bg-gray-700 dark:text-white w-full p-2 rounded">
                        @error('foto') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white rounded p-2 hover:bg-blue-600">Realizar Pago</button>
                </form>
            </div>
        </div>
    </div>

    
</div>
