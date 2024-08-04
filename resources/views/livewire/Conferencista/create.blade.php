<div>
    <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400" x-show="isOpen">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <form wire:submit.prevent="store">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-900">
                        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Nuevo Conferencista
                            </h3>
                            <button wire:click="closeModal" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Cerrar modal</span>
                            </button>
                        </div>
                        <div class="mb-4">
                            <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Título:</label>
                            <select wire:model="titulo"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="titulo" placeholder="Título">
                                <option value="" disabled selected>Seleccione un título</option>
                                <optgroup label="Académicos">
                                    <option value="Bachiller">Bachiller</option>
                                    <option value="Diplomado">Diplomado</option>
                                    <option value="Diplomada">Diplomada</option>
                                    <option value="Licenciado">Licenciado</option>
                                    <option value="Licenciada">Licenciada</option>
                                    <option value="Magíster">Magíster</option>
                                    <option value="Máster">Máster</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Doctora">Doctora</option>
                                </optgroup>
                                <optgroup label="Especialistas">
                                    <option value="Agrónomo">Agrónomo</option>
                                    <option value="Agrónoma">Agrónoma</option>
                                    <option value="Biólogo">Biólogo</option>
                                    <option value="Bióloga">Bióloga</option>
                                    <option value="Contador">Contador</option>
                                    <option value="Contadora">Contadora</option>
                                    <option value="Economista">Economista</option>
                                    <option value="Especialista">Especialista</option>
                                    <option value="Farmacéutico">Farmacéutico</option>
                                    <option value="Farmacéutica">Farmacéutica</option>
                                    <option value="Ingeniero Agrícola">Ingeniero Agrícola</option>
                                    <option value="Ingeniera Agrícola">Ingeniera Agrícola</option>
                                    <option value="Ingeniero en Energías Renovables">Ingeniero en Energías Renovables</option>
                                    <option value="Ingeniera en Energías Renovables">Ingeniera en Energías Renovables</option>
                                    <option value="Ing. en Sistemas">Ing. en Sistemas</option>
                                    <option value="Ingeniero en Medio Ambiente">Ingeniero en Medio Ambiente</option>
                                    <option value="Ingeniera en Medio Ambiente">Ingeniera en Medio Ambiente</option>
                                    <option value="Ingeniero en Recursos Hídricos">Ingeniero en Recursos Hídricos</option>
                                    <option value="Ingeniera en Recursos Hídricos">Ingeniera en Recursos Hídricos</option>
                                    <option value="Ingeniero Forestal">Ingeniero Forestal</option>
                                    <option value="Ingeniera Forestal">Ingeniera Forestal</option>
                                    <option value="Ingeniero Hidráulico">Ingeniero Hidráulico</option>
                                    <option value="Ingeniera Hidráulica">Ingeniera Hidráulica</option>
                                    <option value="Matemático">Matemático</option>
                                    <option value="Matemática">Matemática</option>
                                    <option value="Meteorólogo">Meteorólogo</option>
                                    <option value="Meteoróloga">Meteoróloga</option>
                                    <option value="Ocenógrafo">Ocenógrafo</option>
                                    <option value="Ocenógrafa">Ocenógrafa</option>
                                    <option value="Psicólogo">Psicólogo</option>
                                    <option value="Psicóloga">Psicóloga</option>
                                    <option value="Químico">Químico</option>
                                    <option value="Química">Química</option>
                                    <option value="Veterinario">Veterinario</option>
                                    <option value="Veterinaria">Veterinaria</option>
                                </optgroup>
                                <optgroup label="Profesionales">
                                    <option value="Abogado">Abogado</option>
                                    <option value="Abogada">Abogada</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Administradora">Administradora</option>
                                    <option value="Arquitecto">Arquitecto</option>
                                    <option value="Arquitecta">Arquitecta</option>
                                    <option value="Contador">Contador</option>
                                    <option value="Contadora">Contadora</option>
                                    <option value="Diseñador">Diseñador</option>
                                    <option value="Diseñadora">Diseñadora</option>
                                    <option value="Enfermero">Enfermero</option>
                                    <option value="Enfermera">Enfermera</option>
                                    <option value="Ingeniero">Ingeniero</option>
                                    <option value="Ingeniera">Ingeniera</option>
                                    <option value="Jefe">Jefe</option>
                                    <option value="Jefa">Jefa</option>
                                    <option value="Profesor">Profesor</option>
                                    <option value="Profesora">Profesora</option>
                                    <option value="Productor">Productor</option>
                                    <option value="Productora">Productora</option>
                                    <option value="Redactor">Redactor</option>
                                    <option value="Redactora">Redactora</option>
                                    <option value="Supervisor">Supervisor</option>
                                    <option value="Supervisora">Supervisora</option>
                                </optgroup>
                                <optgroup label="Otros">
                                    <option value="Actor">Actor</option>
                                    <option value="Actriz">Actriz</option>
                                    <option value="Asesor">Asesor</option>
                                    <option value="Asesora">Asesora</option>
                                    <option value="Bailarín">Bailarín</option>
                                    <option value="Bailarina">Bailarina</option>
                                    <option value="Chef">Chef</option>
                                    <option value="Conductor">Conductor</option>
                                    <option value="Conductora">Conductora</option>
                                    <option value="Consultor">Consultor</option>
                                    <option value="Consultora">Consultora</option>
                                    <option value="Cocinero">Cocinero</option>
                                    <option value="Cocinera">Cocinera</option>
                                    <option value="Desarrollador">Desarrollador</option>
                                    <option value="Desarrolladora">Desarrolladora</option>
                                    <option value="Editor">Editor</option>
                                    <option value="Editora">Editora</option>
                                    <option value="Fisioterapeuta">Fisioterapeuta</option>
                                    <option value="Fisioterapeuta (femenino)">Fisioterapeuta (femenino)</option>
                                    <option value="Forestal">Forestal</option>
                                    <option value="Forestal (femenino)">Forestal (femenino)</option>
                                    <option value="Geólogo">Geólogo</option>
                                    <option value="Geóloga">Geóloga</option>
                                    <option value="Hidrólogo">Hidrólogo</option>
                                    <option value="Hidróloga">Hidróloga</option>
                                    <option value="Ing. en Tecnologías de la Información">Ing. en Tecnologías de la Información</option>
                                    <option value="Lingüista">Lingüista</option>
                                    <option value="Lingüista (femenino)">Lingüista (femenino)</option>
                                    <option value="Matemático">Matemático</option>
                                    <option value="Matemática">Matemática</option>
                                    <option value="Periodista">Periodista</option>
                                    <option value="Piloto">Piloto</option>
                                    <option value="Productor">Productor</option>
                                    <option value="Productora">Productora</option>
                                    <option value="Psicólogo">Psicólogo</option>
                                    <option value="Psicóloga">Psicóloga</option>
                                    <option value="Químico">Químico</option>
                                    <option value="Química">Química</option>
                                    <option value="Redactor">Redactor</option>
                                    <option value="Redactora">Redactora</option>
                                    <option value="Sociólogo">Sociólogo</option>
                                    <option value="Socióloga">Socióloga</option>
                                    <option value="Tester">Tester</option>
                                    <option value="Topógrafo">Topógrafo</option>
                                    <option value="Topógrafa">Topógrafa</option>
                                    <option value="Veterinario">Veterinario</option>
                                    <option value="Veterinaria">Veterinaria</option>
                                </optgroup>
                            </select>


                            @error('titulo') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Descripción:</label>
                            <textarea wire:model="descripcion"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="descripcion" placeholder="Descripción"></textarea>
                            @error('descripcion') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="foto" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Foto:</label>
                            <input type="file" wire:model="foto"
                                class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                            @if ($foto)
                                <img src="{{ $foto->temporaryUrl() }}" class="mt-2 w-20 h-20 object-cover rounded-full">
                            @endif
                        </div>
                        <div class="mb-4">
                            <label for="persona" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Persona:</label>
                            <input type="text" wire:model.live="inputSearchPersona"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="persona" placeholder="Buscar persona...">
                            @if (count($searchPersonas) > 0)
                                <ul class="mt-2 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                    @foreach($searchPersonas as $persona)
                                        <li wire:click="selectPersona({{ $persona->id }})"
                                            class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            {{ $persona->nombre }} {{ $persona->apellido }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-900">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-yellow-500 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button type="button" wire:click="closeModal"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-white dark:ring-gray-600 dark:hover:bg-gray-700 dark:focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>