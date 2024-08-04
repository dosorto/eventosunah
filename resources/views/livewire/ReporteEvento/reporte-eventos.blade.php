<div>
    <x-layouts.reportes>
        <section
            class="dark:bg-gray-900 bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern.svg')] dark:bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern-dark.svg')]">
            <div class="py-8 px-4 mx-auto max-w-screen-lg text-center lg:py-16 z-10 relative">
                <div class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md mb-8">
                    <div class="flex-shrink-0 mr-6">
                        <img class="w-48 h-48 object-cover rounded" src="{{ asset(str_replace('public', 'storage', $evento->logo)) }}" alt="Logo del Evento">
                    </div>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold tracking-tight leading-none text-gray-900 dark:text-white mb-4">
                            {{ $evento->nombreevento }}
                        </h1>
                        <p class="text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-200 mb-4">
                            {{ $evento->descripcion }}
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
                            <div>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Organizador:</strong> {{ $evento->organizador }}</p>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Hora de Inicio:</strong> {{ $evento->horainicio }}</p>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Hora Final:</strong> {{ $evento->horafin }}</p>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Fecha de Inicio:</strong> {{ $evento->fechainicio }}</p>
                            </div>
                            <div>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Fecha Fina:</strong> {{ $evento->fechafinal }}</p>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Modalidad:</strong> {{ $evento->modalidad->modalidad }}</p>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Localidad:</strong> {{ $evento->localidad->localidad }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-x-auto sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <caption
                            class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                            Conferencias del evento
                        </caption>
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Conferencia
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Lugar
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Conferencista
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($conferencias as $conferencia)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-normal dark:text-white break-words max-w-xs">
                                        {{ $conferencia->nombre }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap truncate">
                                        {{ $conferencia->fecha }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $conferencia->lugar }}
                                    </td>
                                    <td class="flex items-center px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                        <img class="w-10 h-10 rounded-full mr-3" src="{{ asset(str_replace('public', 'storage', $conferencia->conferencista->Foto)) }}" alt="Foto conferencista">
                                        <div class="text-base font-semibold">
                                            @if ($conferencia->conferencista)
                                                @if ($conferencia->conferencista->persona)
                                                    {{ $conferencia->conferencista->persona->nombre }} {{ $conferencia->conferencista->persona->apellido ?? '' }}
                                                @else
                                                    N/A
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-gradient-to-b from-blue-50 to-transparent dark:from-blue-900 w-full h-full absolute top-0 left-0 z-0"></div>
            <a href="{{ route('evento') }}"
                    class="absolute top-4 right-4 inline-flex items-center px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800 rounded-lg">
                    Regresar
                </a>
        </section>
    </x-layouts.reportes>
</div>
