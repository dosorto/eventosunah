<x-app-layout>
    <div class="p-4 sm:ml-64">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
               Bienvenido a tu panel de control {{ Auth::user()->name }}
            </div>
        </div>
    </div>
</x-app-layout>
