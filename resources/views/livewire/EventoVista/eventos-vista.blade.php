<div>
  <x-layouts.app>
    <div class="content-wrapper">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-6 ml-2">
        Eventos Disponibles
    </h2>
      <!-- Lista de eventos -->
      <div
        class="evento-list dark:bg-gray-900  dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">
        @foreach($targetasEventos as $targetasEvento)
      <a href="{{ route('vistaconferencia') }}" class="evento-card">
        <div class="thumbnail-container">
        <img src="http://www.puertopixel.com/wp-content/uploads/2011/03/Fondos-web-Texturas-web-abtacto-17.jpg"
          alt="Sin Foto De Evento"
          class=" bg-white dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white thumbnail">
        <p class="marca">{{ $targetasEvento->modalidad->modalidad }}</p>
        </div>
        <div class="evento-info">
        <img src="https://th.bing.com/th/id/OIP.iyJwFnHYSH0mMBUsmc_JnQHaIs?rs=1&pid=ImgDetMain" alt="foto-creador"
          class="icon">
        <div class="evento-details">
          <h2 class="name-evento">{{ $targetasEvento->nombreevento }}</h2>
          <p class="evento-creador">{{ $targetasEvento->organizador }}</p>
          <p class="fecha-creacion">18 septiembre 2024</p>
        </div>
        </div>
      </a>
    @endforeach
      </div>
    </div>
    <br>
    {{$targetasEventos->links()}}
    </br>
  </x-layouts.app>
</div>