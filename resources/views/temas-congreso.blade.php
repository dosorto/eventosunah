@extends('layouts.congreso')
@section('app-content')
<section class="bg-gray-50 dark:bg-gray-900">
    <x-nav />
    <div class="py-8 px-4 mx-auto mt-11 max-w-screen-xl lg:py-16 grid lg:grid-cols-2 gap-8 lg:gap-16">
        <div class="flex flex-col justify-center">
            <h1
                class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                Temas a abordar </h1>
            <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-400">Contará con
                conferencias impartidas por expertos e investigadores nacionales e
                internacionales abordando los siguientes temas: </p>

        </div>
        <div id="mega-menu-full-dropdown"
            class="mt-1 bg-white border-gray-200 shadow-sm rounded-lg border-y dark:bg-gray-800 dark:border-gray-600">
            <div
                class="grid max-w-screen-xl mt-4 px-4 py-5 mx-auto text-gray-900 dark:text-white sm:grid-cols-2 md:grid-cols-2 md:px-6">
                <ul aria-labelledby="mega-menu-full-dropdown-button">
                    <li>
                        <a href="#" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="font-semibold">1. Cultivo de peces</div>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Tilapia</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Peces marinos</span>
                            </ol>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="font-semibold"> 2. Nutrición y alimenttación acuícola</div>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Alimentación de larvas y
                                    alevines</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Estrategias de
                                    alimentación</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Alimentos y alimentación
                                    para camarones</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Alimentos y alimentación
                                    para peces</span></ol>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="font-semibold">3. Acuícultura sustentable</div>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Acuícultura y cambio
                                    climático</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Gestión de la
                                    calidad de
                                    agua</span></ol>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="font-semibold"> 4. Salud y enfermedades</div>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Salud de
                                    peces</span>
                            </ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Salud de
                                    camarones</span>
                            </ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Control
                                    zoosanitario</span></ol>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="font-semibold"> 5. Recursos marino costeros</div>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Recursos
                                    costeros</span>
                            </ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Recursos
                                    marinos</span>
                            </ol>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="font-semibold"> 6. Innovación y competitividad</div>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Certificaciones y
                                    competitividad</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Innovación en
                                    productividad</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Procesamiento de
                                    productos acuícolas</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Mercadeo</span>
                            </ol>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="font-semibold"> 7. Educación en acuícultura</div>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Facultad de ciencias del
                                    mar</span></ol>
                            <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Programa alumni
                                    acuícola</span></ol>
                        </a>
                    </li>
                </ul>

                <ul>
                    <li>
                        <img class="w-full h-60 object-cover rounded m-2" src="{{ asset('Logo/tilapia.jpg') }}"
                            alt="" />
                    </li>
                    <li>
                        <img class="w-full h-60 object-cover rounded m-2" src="{{ asset('Logo/camaron.jpg') }}"
                            alt="" />
                    </li>
                    <li>
                        <img class="w-full h-60 object-cover rounded m-2" src="{{ asset('Logo/produccion.jpg') }}"
                            alt="" />
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="flex">
    <footer class="bg-white rounded-lg w-full shadow m-4 dark:bg-gray-800">
            <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-1 mb-4 md:mb-0">
                    <div>
                        <img src="{{ asset('Logo/baner1.jpg') }}" class="h-16 md:h-24 lg:h-28 me-4" alt="Logo" />
                    </div>
                    <div>
                        <img src="{{ asset('Logo/Cargil.jpg') }}" class="h-16 md:h-24 lg:h-28 me-4" alt="Logo" />
                    </div>
                    <div>
                        <img src="{{ asset('Logo/Regal.jpg') }}" class="h-16 md:h-24 lg:h-28 me-4" alt="Logo" />
                    </div>
                </div>
                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">Primer congreso de Acuícultura
                    Honduras 2024.
                </span>
                <ul
                    class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
                    <p class="mr-4">Para más información: </p>

                    <li>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                        </svg>

                    </li>
                    <li>
                        <p class="hover:underline me-4 md:me-6">+504 9827-0241</p>
                    </li>
                    <li>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11v5m0 0 2-2m-2 2-2-2M3 6v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1Zm2 2v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8H5Z" />
                        </svg>

                    </li>
                    <li>
                        <p class="hover:underline me-4 md:me-6">edgar.carranza@unah.edu.hn</p>
                    </li>
                </ul>
            </div>
        </footer>
    </div>
</section>
@endsection