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
        <a type="button" href="{{ route('dashboard') }}"
        class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-300 dark:text-gray-800 dark:hover:bg-yellow-400 dark:focus:ring-yellow-800">
        <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
          viewBox="0 0 20 14">
          <path
          d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
        </svg>
        Ir al dashboard
        </a>
      </div>
      </div>
    @else
      <!-- Lista de eventos -->
      <div
      class="evento-list dark:bg-gray-900  dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">
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
        <p class="evento-creador">{{ $tarjetasEvento->organizador }}</p>
        <p class="fecha-creacion">{{ $tarjetasEvento->created_at->diffForHumans() }}</p>
        {{-- <p class="fecha-creacion">{{ $tarjetasEvento->created_at->translatedFormat('F j, Y') }}</p> --}}

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