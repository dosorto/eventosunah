<div>

  <div class="content-wrapper">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-2 ml-2">
      Eventos Disponibles
    </h2>
    @if($Eventos->isEmpty())
    <div id="alert-additional-content-4"
      class="p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-500 dark:border-yellow-800"
      role="alert">
      <div class="flex items-center">
      <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
        fill="currentColor" viewBox="0 0 20 20">
        <path
        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
      </svg>
      <span class="sr-only">Info</span>
      <h3 class="text-lg font-bold">No hay eventos disponibles.</h3>
      </div>
      <div class="mt-2 mb-4 text-sm">
      <p>Por el momento no hay eventos disponibles, por favor intente más tarde.</p>
      </div>
      <div class="flex">
      </div>
    </div>
  @else
    <!-- Lista de eventos -->
    <div class="evento-list dark:bg-gray-900  dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">
      @foreach($Eventos as $tarjetasEvento)
      <a href="{{ route('vistaconferencia', ['evento' => $tarjetasEvento->id]) }}" class="evento-card">
      <div class="thumbnail-container">
      @if($tarjetasEvento->logo == "")
      <img src="http://www.puertopixel.com/wp-content/uploads/2011/03/Fondos-web-Texturas-web-abtacto-17.jpg"
      alt="Sin Foto De Evento">
    @else
      <img src="{{ asset(str_replace('public', 'storage', $tarjetasEvento->logo)) }}" alt="Sin Foto De Evento"
      class=" bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white thumbnail">
    @endif
      <p class="marca">{{ $tarjetasEvento->modalidad->modalidad }}</p>
      </div>
      <div class="evento-info">
      <img src="{{ asset('Logo/EVENTIS LOGO.png') }}" alt="foto-creador" class="icon">
      <div class="evento-details">
      <h2 class="name-evento">{{ $tarjetasEvento->nombreevento }}</h2>
      <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <div class="flex flex-col justify-center max-w-sm">
        <p class="evento-creador">{{ $tarjetasEvento->organizador }}</p>
        <p class="fecha-creacion">{{ $tarjetasEvento->created_at->diffForHumans() }}</p>
        </div>

        <div class="flex flex-col justify-center max-w-sm">
        @if ($tarjetasEvento->estado === 'Pagado')
      <p
      class="inline-flex md:ml-0 md:mr-8 sm:ml-0 sm:mr-8 lg:ml-8 lg:mr-0 items-center px-3 py-2 text-sm font-semibold text-center text-black bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
      Inscribirse
      <svg class="w-6 h-6 text-gray-800 dark:text-gray-800" aria-hidden="true"
      xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
      <path fill-rule="evenodd"
        d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z"
        clip-rule="evenodd" />
      </svg>
      </p>
    @endif
        </div>
      </div>


      </div>
      </div>
      </a>
    @endforeach
    </div>

    <br>
    {{ $Eventos->links() }}
    <br>
    </div>
  @endif
</div>