<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Asistencias
    </h2>

    <div class="dark:bg-gray-900">
        <div class="">
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

                <div class="relative overflow-x-auto sm:rounded-lg">
                    <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input wire:model="search" type="text" id="table-search-asistencias" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white" placeholder="Buscar...">
                        </div>
                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No.</th>
                                <th scope="col" class="px-6 py-3">Fecha</th>
                                <th scope="col" class="px-6 py-3">Asistencia</th>
                                <th scope="col" class="px-6 py-3">IdSuscripción</th>
                                <th scope="col" class="px-6 py-3">Persona</th>
                                <th scope="col" class="px-6 py-3">Conferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asistencias as $asistencia)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $asistencia->id }}
                                    </td>
                                    <td class="px-6 py-4">{{ $asistencia->Fecha ? \Carbon\Carbon::parse($asistencia->Fecha)->format('d/m/Y') : 'No disponible' }}</td>
                                    <td class="px-6 py-4">{{ $asistencia->Asistencia ? 'Presente' : 'Ausente' }}</td>
                                    <td class="px-6 py-4">{{ $asistencia->suscripcion->id ?? 'No disponible' }}</td>
                                    <td class="px-6 py-4">{{ $asistencia->suscripcion->persona->nombre ?? 'No disponible' }} {{ $asistencia->suscripcion->persona->apellido ?? 'No disponible' }}</td>
                                    <td class="px-6 py-4">{{ $asistencia->suscripcion->conferencia->nombre ?? 'No disponible' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">No hay asistencias registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    {{ $asistencias->links() }}
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
