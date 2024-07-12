
<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-14">
        Tipos De Perfiles
    </h2>

<div class=" dark:bg-gray-900">
    <div class="">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                    role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif




            @if($isOpen)
                @include('livewire.Tipoperfil.create')
            @endif




            <div class="relative overflow-x-auto  sm:rounded-lg  dark:bg-gray-800">
                <div
                    class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4 ">
                    <div>
                        <button wire:click="create()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded my-3">Nuevo</button>

                    </div>
                    <label for="table-search" class="sr-only">Buscar...</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input wire:model.live="search" type="text" id="table-search-users"
                            class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700  dark:text-white"
                            placeholder="Buscar...">
                    </div>
                </div>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>

                            <th scope="col" class="px-6 py-3 ">
                                No.
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tipo Perfil
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tipoperfiles as $tipoperfil)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $tipoperfil->id }}</th>
                                <td class="px-6 py-4">{{ $tipoperfil->tipoperfil }}</td>
                                <td class="px-6 py-4">
                                    <button wire:click="edit({{ $tipoperfil->id }})"
                                        class="px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 rounded-lg text-center dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800">Editar</button>
                                    <button wire:click="delete({{ $tipoperfil->id }})"
                                        class="px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">Borrar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                {{ $tipoperfiles->links()}}
                <br>
            </div>
        </div>

    </div>

</div>

</div>