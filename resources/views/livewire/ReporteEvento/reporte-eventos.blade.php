<div>
    <x-layouts.reportes>
        <section
            class="dark:bg-gray-900 bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern.svg')] dark:bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern-dark.svg')]">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 z-10 relative">
                <div class="flex items-center justify-center mb-4">
                    <img class="rounded w-36 h-36 mr-4"
                        src="http://www.puertopixel.com/wp-content/uploads/2011/03/Fondos-web-Texturas-web-abtacto-17.jpg"
                        alt="Extra large avatar">
                    <h1
                        class="text-4xl font-bold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                        {{ $evento->nombreevento }}
                    </h1>
                </div>
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-200">Este
                {{ $evento->descripcion }}</p>

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
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $conferencia->nombre }}
                                    </th>
                                    <td class="px-6 py-4">
                                    {{ $conferencia->fecha }}
                                    </td>
                                    <td class="px-6 py-4">
                                    {{ $conferencia->lugar }}
                                    </td>
                                    <th scope="row"
                                        class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <img class="w-10 h-10 rounded-full"
                                            src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                                            alt="Jese image">
                                        <div class="ps-3">
                                            <di class="text-base font-semibold">
                                                @if ($conferencia->conferencista)
                                                @if ($conferencia->conferencista->persona)
                                                    {{ $conferencia->conferencista->persona->nombre }}
                                                    {{ $conferencia->conferencista->persona->apellido ?? '' }}
                                                @else
                                                    N/A
                                                @endif
                                            @else
                                                N/A
                                            @endif</div>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <div
                class="bg-gradient-to-b from-blue-50 to-transparent dark:from-blue-900 w-full h-full absolute top-0 left-0 z-0">
            </div>
        </section>

    </x-layouts.reportes>
</div>