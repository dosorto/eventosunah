@extends('layouts.congreso')
@section('app-content')
        <section class="bg-gray-50 dark:bg-gray-900">
        <x-nav />
            <div class="py-8 px-4 mx-auto mt-8 max-w-screen-xl lg:py-16 grid lg:grid-cols-2 gap-8 lg:gap-16">
                <div class="flex flex-col justify-center">
                    <h1
                        class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                        El Primer Congreso de Acuicultura Honduras 2024 </h1>
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
                                    <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">  Alimentación de larvas y alevines</span></ol>
                                    <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">  Estrategias de alimentación</span></ol>
                                    <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">  Alimentos y alimentación para camarones</span></ol>
                                    <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">  Alimentos y alimentación para peces</span></ol>
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
                                    <div class="font-semibold">  7. Educación en acuícultura</div>
                                    <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400">Facultad de ciencias del mar</span></ol>
                                    <ol><span class="text-sm ml-6 text-gray-500 dark:text-gray-400"> Programa alumni acuícola</span></ol>
                                </a>
                            </li>
                        </ul>

                        <ul>
                            <li>
                                <img class="w-full h-60 object-cover rounded m-2"
                                    src="{{ asset('Logo/camarones.jpg') }}" alt="" />
                            </li>
                            <li>
                                <img class="w-full h-60 object-cover rounded m-2"
                                    src="{{ asset('Logo/laguna.png') }}" alt="" />
                            </li>
                            <li>
                                <img class="w-full h-60 object-cover rounded m-2"
                                    src="{{ asset('Logo/lagunaartifi.jpeg') }}" alt="" />
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
@endsection