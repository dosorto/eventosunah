@extends('layouts.congreso')
@section('app-content')
<section class="bg-gray-50 dark:bg-gray-900">
    <x-nav />
    <div class="py-8 px-4 mx-auto mt-11 max-w-screen-xl lg:py-16 grid lg:grid-cols-2 gap-8 lg:gap-16">
        <div class="flex flex-col justify-center">
            <h1
                class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                Conferencistas </h1>
            <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-400">Cada una de las conferencias
                será impartida por expertos e investigadores nacionales e internacionales: </p>

        </div>
        <div
            class="grid mb-8 border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 md:mb-12 md:grid-cols-2 bg-white dark:bg-gray-800">
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-ss-lg md:border-e dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <img class="rounded-md w-32 h-32 mx-auto" src="{{ asset('Logo/JulioCastañeda.png') }}"
                        alt="profile picture">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Julio Castañeda</h3>
                    <p class="my-4">Acuicultor del Centro de Estudios del Mar y Acuicultura de la Universidad de San
                        Carlos de Guatemala, y pionero en la industria acuícola de Centroamérica. Posee una amplia
                        experiencia en el cultivo de camarón, así como en la supervisión de la cadena de frío y el
                        control de calidad en acuicultura.
                    </p>
                </blockquote>
            </figure>
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 md:rounded-se-lg dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <img class="rounded-md w-32 h-32 mx-auto" src="{{ asset('Logo/PaulaMiranda.png') }}"
                        alt="profile picture">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Paula Miranda</h3>
                    <p class="my-4">Médico Veterinario, Mg. en Patología Animal, Diplomado en Negocios y Mg. en Ciencia,
                        Tecnología y Sociedad. 20 años de experiencia en Acuicultura y su cadena de valor, conocimiento
                        científico y su aplicación a problemas relevantes de la industria acuícola, sistemas de
                        relaciones, Diseño de productos y estrategias en salud y producción.
                    </p>
                </blockquote>
            </figure>
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-ss-lg md:border-e dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <img class="rounded-md w-32 h-32 mx-auto" src="{{ asset('Logo/ElmanJavierCalvo.png') }}"
                        alt="profile picture">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Elman Javier Calvo</h3>
                    <p class="my-4">Coordinador del Programa de Acuicultura y Biotecnología Marina, Parque Marino del
                        Pacífico. Universidad Nacional de Costa Rica. Lic. Manejo de los recursos marinos y
                        dulceacuícolas con Maestría en Ciencias Marinas y Costeras UNA. Biólogo, especialista en
                        acuicultura y biotecnología marina.
                    </p>
                </blockquote>
            </figure>
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-ss-lg md:border-e dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <img class="rounded-md w-32 h-32 mx-auto" src="{{ asset('Logo/JonathanChacónGuzmán.jpg') }}"
                        alt="profile picture">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Jonathan Chacón Guzmán</h3>
                    <p class="my-4">Biólogo Marino, Master en Ciencias Marinas y Costeras, Doctorante en Ciencias
                        Naturales para el Desarrollo. Especialista en Investigación y desarrollo de acuicultura marina.
                        Zootecnia de la reproducción y producción de juveniles peces marinos, diseño, planificación y
                        dirección de granjas marinas de pequeña y mediana escala. Acuicultura rural integrada con
                        turismo y otras actividades complementarias.
                    </p>
                </blockquote>
            </figure>
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-ss-lg md:border-e dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <img class="rounded-md w-32 h-32 mx-auto" src="{{ asset('Logo/JesusAlexisRodriguez.jpg') }}"
                        alt="profile picture">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Jesús Alexis Rodríguez</h3>
                    <p class="my-4">Ingeniero Químico Industrial, con Maestría en Calidad del Agua y Maestría en
                        Administración de Empresas. Coordinador del laboratorio de suelos, en el departamento de Suelos
                        en UNAH-Atlántida. Experiencia en dinámica de ecosistemas acuáticos en zonas marino-costeras,
                        profesor titular de la Universidad Nacional Autónoma de Honduras campus Atlántida.
                    </p>
                </blockquote>
            </figure>
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-gray-200 rounded-b-lg md:rounded-se-lg dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <img class="rounded-md w-32 h-32 mx-auto" src="{{ asset('Logo/PatricioPaz.png') }}"
                        alt="profile picture">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Patricio E. Paz</h3>
                    <p class="my-4">Coordinador de Zootecnia del Departamento de Ciencias y Producción, profesor
                        asociado y de jefe de acuicultura de la Escuela Agrícola Panamericana El Zamorano, Doctor en
                        Acuicultura de Aurbun University, especialista en nutrición acuícola, manejo de probióticos,
                        enzimas, promotores de crecimientos, acuaponía con los sistemas NTF y RAF, acuamimetismo y
                        pre-engorde y engorde de tilapia.
                    </p>
                </blockquote>
            </figure>

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