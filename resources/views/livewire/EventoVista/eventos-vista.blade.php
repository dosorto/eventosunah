<div>
  <x-layouts.app>
    <div class="content-wrapper">
      <!-- Lista de botones de modalidades -->
      <div class="lista-categoria  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
        <button wire:click="create()"
          class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Nuevo</button>
        <button class="category-button active">Todos</button>
        <button class="category-button">Virtuales</button>
        <button class="category-button">Presenciales</button>
      </div>
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
      <br>
      {{$targetasEventos->links()}}
      </br>
    </div>
  </x-layouts.app>
</div>