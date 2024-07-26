<div>
    <!-- Formulario de asistencia -->
    <form wire:submit.prevent="store">
        <!-- Campos del formulario -->
        <div class="mb-4">
            <label for="evento" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Evento:</label>
            <input wire:model.live="inputSearchEvento"
                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                type="text" placeholder="Buscar evento...">
            @if(!empty($inputSearchEvento) && !empty($searchEventos))
                <ul class="bg-white border border-gray-300 mt-2 rounded-md max-h-48 overflow-auto shadow-lg z-10">
                    @foreach($searchEventos as $evento)
                        <li wire:click="selectEvento({{ $evento->id }})" class="p-2 cursor-pointer hover:bg-gray-200">
                            {{ $evento->nombreevento }}
                        </li>
                    @endforeach
                </ul>
            @endif
            @error('IdEvento') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="Asistencia">Asistencia</label>
            <input type="checkbox" wire:model="Asistencia" id="Asistencia">
        </div>

        <button type="submit">Guardar Asistencia</button>
    </form>

    <!-- Lista de asistencias -->
    <table>
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Persona</th>
                <th>Evento</th>
                <th>Asistencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asistencias as $asistencia)
                <tr>
                    <td>{{ $asistencia->Fecha }}</td>
                    <td>{{ $asistencia->persona->nombre }}</td>
                    <td>{{ $asistencia->evento->nombre }}</td>
                    <td>{{ $asistencia->Asistencia ? 'Presente' : 'Ausente' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>