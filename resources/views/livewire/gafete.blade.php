@extends('layouts.congreso')
@section('app-content')
<section class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div
        class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-col mt-4 items-center">
            <div class="bg-gray-100 z-10 w-16 rounded-full"><br></div>
            <img src="{{ asset('Logo/baner1.jpg') }}" class="h-20 me-4 rounded-lg" alt="Logo" />
            <p class="text-xl mx-8 mb-2 font-bold text-gray-900 dark:text-white text-center break-words">{{$evento->nombreevento}}</p>
            <img class="w-48 h-48 mb-3 border-yellow-400 border-4 rounded-full shadow-lg"
                src="https://th.bing.com/th/id/OIP.QfSY_47Sh6O1l3awl-lHjwHaHa?rs=1&pid=ImgDetMain" alt="Foto Perfil" />

            <h5 class="mb-1 text-xl mx-8 font-bold text-yellow-400 dark:text-white text-center break-words">{{Auth::user()->persona->nombre }} {{Auth::user()->persona->apellido }}</h5>
            <span class="text-sm mb-8 text-gray-500 dark:text-gray-400">Participante</span>
            <div class="flex w-full mt-4 md:mt-6 bg-yellow-400 justify-center h-24">
                <div class="flex flex-col items-center relative w-full">
                    <div class="bg-yellow-500 w-full h-2 z-10"><br></div>
                    <img class="transform -translate-y-1/2 w-20 h-20 z-20 bg-transparent border-4 border-black"
                        src="data:image/png;base64,{{ $qrcode }}" alt="Código QR">
                        <h2 class="absolute bottom-7 text-sm text-gray-900 dark:text-white">Campus-Choluteca</h2>
                        <h2 class="absolute bottom-2 text-sm text-gray-900 dark:text-white">edgar.carranza@unah.edu.hn</h2>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection