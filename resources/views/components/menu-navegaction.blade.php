<div>
   <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
      <div class="px-3 py-3 lg:px-5 lg:pl-3">
         <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
               <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                  type="button"
                  class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                  <span class="sr-only">Open sidebar</span>
                  <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                     <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                     </path>
                  </svg>
               </button>
               <a href="/dashboard" class="flex ms-2 md:me-24">
                  <img src="{{ asset('Logo/Eventos_UNAH_Logo.png') }}" alt="Logo" height="70px" width="70px" />
                  <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">EVENTOS
                     UNAH</span>
               </a>

            </div>
            <div class="flex items-center">
               <div class="flex items-center ms-3">
                  <div>
                     <button id="theme-toggle" type="button"
                        class="text-gray-500 mr-3 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                           xmlns="http://www.w3.org/2000/svg">
                           <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                           xmlns="http://www.w3.org/2000/svg">
                           <path
                              d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                              fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                     </button>
                  </div>
                  <div>
                     <button type="button"
                        class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-yellow-300 dark:focus:ring-gray-600"
                        aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-9 h-9 rounded-full"
                           src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&amp;color=000&amp;background=facc15">
                     </button>
                  </div>
                  <div
                     class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                     id="dropdown-user">
                     <div class="px-4 py-3" role="none">
                        <p class="text-sm text-gray-900 dark:text-white" role="none">
                           {{ Auth::user()->name }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                           {{ Auth::user()->email }}
                        </p>
                     </div>
                     <ul class="py-1" role="none">
                        <li>
                           <x-dropdown-link href="{{ route('profile.show') }}">
                              {{ __('Perfil') }}
                           </x-dropdown-link>
                        </li>
                        <li>
                           <form method="POST" action="{{ route('logout') }}" x-data>
                              @csrf

                              <x-dropdown-link href="{{ route('logout') }}"
                                 class="text-red-800 dark:text-gray-100 hover:bg-red-100 "
                                 @click.prevent="$root.submit();">
                                 {{ __('Cerrar Sesión') }}
                              </x-dropdown-link>
                           </form>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </nav>

   <aside id="logo-sidebar"
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
      aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
         <ul class="space-y-2 font-medium">

            <li>
               @can("admin-dashboard")
               <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                 class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
                 <svg
                   class="w-5 h-5 text-gray-900 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                   aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                   <path
                     d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                   <path
                     d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                 </svg>
                 <span class="ms-3">Dashboard</span>
               </x-nav-link>
            @endcan
            </li>
            <li>
               @can("admin-Participante")
               <x-nav-link href="{{ route('eventoVista') }}" :active="request()->routeIs('eventoVista')"
                 class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
                 <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                   xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                   <path fill-rule="evenodd"
                     d="M17 10v1.126c.367.095.714.24 1.032.428l.796-.797 1.415 1.415-.797.796c.188.318.333.665.428 1.032H21v2h-1.126c-.095.367-.24.714-.428 1.032l.797.796-1.415 1.415-.796-.797a3.979 3.979 0 0 1-1.032.428V20h-2v-1.126a3.977 3.977 0 0 1-1.032-.428l-.796.797-1.415-1.415.797-.796A3.975 3.975 0 0 1 12.126 16H11v-2h1.126c.095-.367.24-.714.428-1.032l-.797-.796 1.415-1.415.796.797A3.977 3.977 0 0 1 15 11.126V10h2Zm.406 3.578.016.016c.354.358.574.85.578 1.392v.028a2 2 0 0 1-3.409 1.406l-.01-.012a2 2 0 0 1 2.826-2.83ZM5 8a4 4 0 1 1 7.938.703 7.029 7.029 0 0 0-3.235 3.235A4 4 0 0 1 5 8Zm4.29 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h6.101A6.979 6.979 0 0 1 9 15c0-.695.101-1.366.29-2Z"
                     clip-rule="evenodd" />
                 </svg>

                 <span class="flex-1 ms-3 whitespace-nowrap">Principal</span>

               </x-nav-link>
            @endcan
            </li>
            <li>
               @can("admin-evento")
               <button type="button"
                 class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-yellow-400 dark:text-white dark:hover:bg-gray-700"
                 aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                 <svg
                   class="w-6 h-6  text-gray-900 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                   aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                   viewBox="0 0 24 24">
                   <path fill-rule="evenodd"
                     d="M12.512 8.72a2.46 2.46 0 0 1 3.479 0 2.461 2.461 0 0 1 0 3.479l-.004.005-1.094 1.08a.998.998 0 0 0-.194-.272l-3-3a1 1 0 0 0-.272-.193l1.085-1.1Zm-2.415 2.445L7.28 14.017a1 1 0 0 0-.289.702v2a1 1 0 0 0 1 1h2a1 1 0 0 0 .703-.288l2.851-2.816a.995.995 0 0 1-.26-.189l-3-3a.998.998 0 0 1-.19-.26Z"
                     clip-rule="evenodd" />
                   <path fill-rule="evenodd"
                     d="M7 3a1 1 0 0 1 1 1v1h3V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h1V4a1 1 0 0 1 1-1Zm10.67 8H19v8H5v-8h3.855l.53-.537a1 1 0 0 1 .87-.285c.097.015.233.13.277.087.045-.043-.073-.18-.09-.276a1 1 0 0 1 .274-.873l1.09-1.104a3.46 3.46 0 0 1 4.892 0l.001.002A3.461 3.461 0 0 1 17.67 11Z"
                     clip-rule="evenodd" />
                 </svg>

                 <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Eventos</span>
                 <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                   viewBox="0 0 10 6">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="m1 1 4 4 4-4" />
                 </svg>
               </button>
            @endcan
               <ul id="dropdown-example" class="hidden py-2 space-y-2">

                  <li>
                     <x-nav-link href="{{ route('evento') }}" :active="request()->routeIs('evento')"
                        class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group  hover:bg-yellow-400 dark:hover:bg-gray-700  dark:text-white">Eventos</x-nav-link>

                  </li>

                  @can("admin-conferencista")
                 <li>
                   <x-nav-link href="{{ route('conferencista') }}" :active="request()->routeIs('conferencista')"
                     class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 ">Conferencista</x-nav-link>
                 </li>
              @endcan
            </li>

         </ul>
         </li>
         <li>
            @can("admin-usuario")
            <x-nav-link href="{{ route('usuario') }}" :active="request()->routeIs('usuario')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                 <path fill-rule="evenodd"
                   d="M17 10v1.126c.367.095.714.24 1.032.428l.796-.797 1.415 1.415-.797.796c.188.318.333.665.428 1.032H21v2h-1.126c-.095.367-.24.714-.428 1.032l.797.796-1.415 1.415-.796-.797a3.979 3.979 0 0 1-1.032.428V20h-2v-1.126a3.977 3.977 0 0 1-1.032-.428l-.796.797-1.415-1.415.797-.796A3.975 3.975 0 0 1 12.126 16H11v-2h1.126c.095-.367.24-.714.428-1.032l-.797-.796 1.415-1.415.796.797A3.977 3.977 0 0 1 15 11.126V10h2Zm.406 3.578.016.016c.354.358.574.85.578 1.392v.028a2 2 0 0 1-3.409 1.406l-.01-.012a2 2 0 0 1 2.826-2.83ZM5 8a4 4 0 1 1 7.938.703 7.029 7.029 0 0 0-3.235 3.235A4 4 0 0 1 5 8Zm4.29 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h6.101A6.979 6.979 0 0 1 9 15c0-.695.101-1.366.29-2Z"
                   clip-rule="evenodd" />
               </svg>

               <span class="flex-1 ms-3 whitespace-nowrap">Usuarios</span>

            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-rol")
            <x-nav-link href="{{ route('rol') }}" :active="request()->routeIs('rol')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                 <path fill-rule="evenodd"
                   d="M17 10v1.126c.367.095.714.24 1.032.428l.796-.797 1.415 1.415-.797.796c.188.318.333.665.428 1.032H21v2h-1.126c-.095.367-.24.714-.428 1.032l.797.796-1.415 1.415-.796-.797a3.979 3.979 0 0 1-1.032.428V20h-2v-1.126a3.977 3.977 0 0 1-1.032-.428l-.796.797-1.415-1.415.797-.796A3.975 3.975 0 0 1 12.126 16H11v-2h1.126c.095-.367.24-.714.428-1.032l-.797-.796 1.415-1.415.796.797A3.977 3.977 0 0 1 15 11.126V10h2Zm.406 3.578.016.016c.354.358.574.85.578 1.392v.028a2 2 0 0 1-3.409 1.406l-.01-.012a2 2 0 0 1 2.826-2.83ZM5 8a4 4 0 1 1 7.938.703 7.029 7.029 0 0 0-3.235 3.235A4 4 0 0 1 5 8Zm4.29 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h6.101A6.979 6.979 0 0 1 9 15c0-.695.101-1.366.29-2Z"
                   clip-rule="evenodd" />
               </svg>

               <span class="flex-1 ms-3 whitespace-nowrap">Roles</span>

            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-miAsistencia")
               <x-nav-link href="{{ route('conferencias-inscritas') }}" :active="request()->routeIs('conferencias-inscritas')"
                  class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd"
                     d="M18 14a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2Z"
                     clip-rule="evenodd" />
                  <path fill-rule="evenodd"
                     d="M15.026 21.534A9.994 9.994 0 0 1 12 22C6.477 22 2 17.523 2 12S6.477 2 12 2c2.51 0 4.802.924 6.558 2.45l-7.635 7.636L7.707 8.87a1 1 0 0 0-1.414 1.414l3.923 3.923a1 1 0 0 0 1.414 0l8.3-8.3A9.956 9.956 0 0 1 22 12a9.994 9.994 0 0 1-.466 3.026A2.49 2.49 0 0 0 20 14.5h-.5V14a2.5 2.5 0 0 0-5 0v.5H14a2.5 2.5 0 0 0 0 5h.5v.5c0 .578.196 1.11.526 1.534Z"
                     clip-rule="evenodd" />
                  </svg>

                  <span class="flex-1 ms-3 whitespace-nowrap">Mis conferencias</span>

               </x-nav-link>
            @endcan
         </li>
         <li>
               @can("admin-Historial")
               <x-nav-link href="{{ route('historial-conferencias') }}" :active="request()->routeIs('historial-conferencias')"
                 class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
                 <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                   xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                   <path fill-rule="evenodd"
                     d="M17 10v1.126c.367.095.714.24 1.032.428l.796-.797 1.415 1.415-.797.796c.188.318.333.665.428 1.032H21v2h-1.126c-.095.367-.24.714-.428 1.032l.797.796-1.415 1.415-.796-.797a3.979 3.979 0 0 1-1.032.428V20h-2v-1.126a3.977 3.977 0 0 1-1.032-.428l-.796.797-1.415-1.415.797-.796A3.975 3.975 0 0 1 12.126 16H11v-2h1.126c.095-.367.24-.714.428-1.032l-.797-.796 1.415-1.415.796.797A3.977 3.977 0 0 1 15 11.126V10h2Zm.406 3.578.016.016c.354.358.574.85.578 1.392v.028a2 2 0 0 1-3.409 1.406l-.01-.012a2 2 0 0 1 2.826-2.83ZM5 8a4 4 0 1 1 7.938.703 7.029 7.029 0 0 0-3.235 3.235A4 4 0 0 1 5 8Zm4.29 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h6.101A6.979 6.979 0 0 1 9 15c0-.695.101-1.366.29-2Z"
                     clip-rule="evenodd" />
                 </svg>

                 <span class="flex-1 ms-3 whitespace-nowrap">Historial Conferencias</span>

               </x-nav-link>
            @endcan
            </li>
         <li>
            @can("admin-asistencia")
               <x-nav-link href="{{ route('asistencia') }}" :active="request()->routeIs('asistencia')"
                  class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd"
                     d="M18 14a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2Z"
                     clip-rule="evenodd" />
                  <path fill-rule="evenodd"
                     d="M15.026 21.534A9.994 9.994 0 0 1 12 22C6.477 22 2 17.523 2 12S6.477 2 12 2c2.51 0 4.802.924 6.558 2.45l-7.635 7.636L7.707 8.87a1 1 0 0 0-1.414 1.414l3.923 3.923a1 1 0 0 0 1.414 0l8.3-8.3A9.956 9.956 0 0 1 22 12a9.994 9.994 0 0 1-.466 3.026A2.49 2.49 0 0 0 20 14.5h-.5V14a2.5 2.5 0 0 0-5 0v.5H14a2.5 2.5 0 0 0 0 5h.5v.5c0 .578.196 1.11.526 1.534Z"
                     clip-rule="evenodd" />
                  </svg>

                  <span class="flex-1 ms-3 whitespace-nowrap">Asistencia</span>

               </x-nav-link>
            @endcan
         </li>
         <li>
            @can("admin-nacionalidad")
            <x-nav-link href="{{ route('nacionalidad') }}" :active="request()->routeIs('nacionalidad')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg
                 class="flex-shrink-0 w-5 h-5 text-gray-900 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                 aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                 <path
                   d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Nacionalidad</span>
            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-carrera")
            <x-nav-link href="{{ route('carrera') }}" :active="request()->routeIs('carrera')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white  hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                 <path fill-rule="evenodd"
                   d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                   clip-rule="evenodd" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Carrera</span>
            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-modalidad")
            <x-nav-link href="{{ route('modalidad') }}" :active="request()->routeIs('modalidad')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                 <path fill-rule="evenodd"
                   d="M5 3a2 2 0 0 0-2 2v5h18V5a2 2 0 0 0-2-2H5ZM3 14v-2h18v2a2 2 0 0 1-2 2h-6v3h2a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2h2v-3H5a2 2 0 0 1-2-2Z"
                   clip-rule="evenodd" />
               </svg>

               <span class="flex-1 ms-3 whitespace-nowrap">Modalidad</span>
            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-departamento")
            <x-nav-link href="{{ route('departamento') }}" :active="request()->routeIs('departamento')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg
                 class="flex-shrink-0 w-5 h-5 text-gray-900 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                 aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                 <path
                   d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Departamento</span>
            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-diploma")
            <x-nav-link href="{{ route('diploma') }}" :active="request()->routeIs('diploma')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                 width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                 <path d="M11 9a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" />
                 <path fill-rule="evenodd"
                   d="M9.896 3.051a2.681 2.681 0 0 1 4.208 0c.147.186.38.282.615.255a2.681 2.681 0 0 1 2.976 2.975.681.681 0 0 0 .254.615 2.681 2.681 0 0 1 0 4.208.682.682 0 0 0-.254.615 2.681 2.681 0 0 1-2.976 2.976.681.681 0 0 0-.615.254 2.682 2.682 0 0 1-4.208 0 .681.681 0 0 0-.614-.255 2.681 2.681 0 0 1-2.976-2.975.681.681 0 0 0-.255-.615 2.681 2.681 0 0 1 0-4.208.681.681 0 0 0 .255-.615 2.681 2.681 0 0 1 2.976-2.975.681.681 0 0 0 .614-.255ZM12 6a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"
                   clip-rule="evenodd" />
                 <path
                   d="M5.395 15.055 4.07 19a1 1 0 0 0 1.264 1.267l1.95-.65 1.144 1.707A1 1 0 0 0 10.2 21.1l1.12-3.18a4.641 4.641 0 0 1-2.515-1.208 4.667 4.667 0 0 1-3.411-1.656Zm7.269 2.867 1.12 3.177a1 1 0 0 0 1.773.224l1.144-1.707 1.95.65A1 1 0 0 0 19.915 19l-1.32-3.93a4.667 4.667 0 0 1-3.4 1.642 4.643 4.643 0 0 1-2.53 1.21Z" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Diploma</span>
            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-localidad")
            <x-nav-link href="{{ route('localidad') }}" :active="request()->routeIs('localidad')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                 <path fill-rule="evenodd"
                   d="M8.64 4.737A7.97 7.97 0 0 1 12 4a7.997 7.997 0 0 1 6.933 4.006h-.738c-.65 0-1.177.25-1.177.9 0 .33 0 2.04-2.026 2.008-1.972 0-1.972-1.732-1.972-2.008 0-1.429-.787-1.65-1.752-1.923-.374-.105-.774-.218-1.166-.411-1.004-.497-1.347-1.183-1.461-1.835ZM6 4a10.06 10.06 0 0 0-2.812 3.27A9.956 9.956 0 0 0 2 12c0 5.289 4.106 9.619 9.304 9.976l.054.004a10.12 10.12 0 0 0 1.155.007h.002a10.024 10.024 0 0 0 1.5-.19 9.925 9.925 0 0 0 2.259-.754 10.041 10.041 0 0 0 4.987-5.263A9.917 9.917 0 0 0 22 12a10.025 10.025 0 0 0-.315-2.5A10.001 10.001 0 0 0 12 2a9.964 9.964 0 0 0-6 2Zm13.372 11.113a2.575 2.575 0 0 0-.75-.112h-.217A3.405 3.405 0 0 0 15 18.405v1.014a8.027 8.027 0 0 0 4.372-4.307ZM12.114 20H12A8 8 0 0 1 5.1 7.95c.95.541 1.421 1.537 1.835 2.415.209.441.403.853.637 1.162.54.712 1.063 1.019 1.591 1.328.52.305 1.047.613 1.6 1.316 1.44 1.825 1.419 4.366 1.35 5.828Z"
                   clip-rule="evenodd" />
               </svg>

               <span class="flex-1 ms-3 whitespace-nowrap">Localidad</span>
            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-tipoPerfil")
            <x-nav-link href="{{ route('tipoperfil') }}" :active="request()->routeIs('tipoperfil')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg
                 class="flex-shrink-0 w-5 h-5 text-gray-900 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                 aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                 <path
                   d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Tipo Perfil</span>
            </x-nav-link>
         @endcan
         </li>
         <li>
            @can("admin-persona")
            <x-nav-link href="{{ route('persona') }}" :active="request()->routeIs('persona')"
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                 <path fill-rule="evenodd"
                   d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z"
                   clip-rule="evenodd" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Personas</span>
            </x-nav-link>
         @endcan
         </li>
         <li>
            <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
         </li>
         <li>
            <x-nav-link
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group">
               <svg
                  class="flex-shrink-0 w-5 h-5 text-gray-900 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                  aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z" />
                  <path
                     d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z" />
                  <path
                     d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Sign Up</span>
            </x-nav-link>
         </li>
         {{-- <div id="dropdown-cta" class="p-4 mt-6 rounded-lg bg-yellow-50 dark:bg-blue-900" role="alert">
            <div class="flex items-center mb-3">
               <span
                  class="bg-orange-100 text-orange-800 text-sm font-semibold me-2 px-2.5 py-0.5 rounded dark:bg-orange-200 dark:text-orange-900">¡Bienvenido!</span>
               <button type="button"
                  class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 inline-flex justify-center items-center w-6 h-6 text-blue-900 rounded-lg focus:ring-2 focus:ring-blue-400 p-1 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-400 dark:hover:bg-blue-800"
                  data-dismiss-target="#dropdown-cta" aria-label="Close">
                  <span class="sr-only">Close</span>
                  <svg class="w-2.5 h-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 14 14">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                  </svg>
               </button>
            </div>
            <p class="mb-3 text-sm text-gray-800 dark:text-white">
               Este es tu panel de control donde puedes ver y administrar tus eventos.
            </p>
            <x-nav-link
               class="text-sm text-blue-800 underline font-medium hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
               href="{{ route('profile.show') }}"> Ver y administrar Perfil</x-nav-link>
         </div>--}}

         </ul>
      </div>
   </aside>

   <div class="p-4 sm:ml-64 dark:bg-gray-900">

   </div>

</div>