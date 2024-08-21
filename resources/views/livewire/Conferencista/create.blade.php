<html>
<div>
    <!-- Modal -->
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
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 111.414 1.414L11.414 10l4.293 4.293a1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 01-1.414-1.414L8.586 10 4.293 5.707a1 1 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Cerrar modal</span>
                            </button>
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
                            <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Nombre:</label>
                            <input type="text" wire:model="nombre"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="nombre" placeholder="Nombre">
                            @error('persona.nombre') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="apellido" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Apellido:</label>
                            <input type="text" wire:model="apellido"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="apellido" placeholder="Apellido">
                            @error('persona.apellido') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="dni" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">DNI:</label>
                            <input type="text" wire:model="dni"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="dni" placeholder="DNI">
                            @error('Conferencistas.dni') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="correo" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Correo:</label>
                            <input type="email" wire:model="correo"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="correo" placeholder="Correo">
                            @error('persona.correo') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="fechaNacimiento" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Fecha de Nacimiento:</label>
                            <input type="date" wire:model="fechaNacimiento"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="fechaNacimiento">
                            @error('persona.fechaNacimiento') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="sexo" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Sexo:</label>
                            <select wire:model="sexo"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="sexo">
                                <option value="" disabled selected>Seleccione un sexo</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                                
                            </select>
                            @error('persona.sexo') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="nacionalidad" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Nacionalidad:</label>
                            <select wire:model="IdNacionalidad"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="nacionalidad">
                                <option value="" disabled selected>Seleccione una nacionalidad</option>
                                @foreach($nacionalidades as $nacionalidad)
                                    <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                                @endforeach
                            </select>
                            @error('persona.IdNacionalidad') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Número de Teléfono:</label>
                            <input type="text" wire:model="telefono"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="telefono" placeholder="Número de Teléfono">
                            @error('persona.telefono') <span class="text-red-500">{{ $message }}</span> @enderror
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
                            <label for="direccion" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Dirección:</label>
                            <input type="text" wire:model="direccion"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="direccion" placeholder="Dirección">
                            @error('persona.direccion') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="tipoPerfil" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Tipo de Perfil:</label>
                            <select wire:model.live="IdTipoPerfil"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="tipoPerfil">
                                <option value="" disabled selected>Seleccione un tipo de perfil</option>
                                @foreach($tipoperfiles as $tipoPerfil)
                                    <option value="{{ $tipoPerfil->id }}">{{ $tipoPerfil->tipoperfil }}</option>
                                @endforeach
                            </select>
                            @error('persona.IdTipoPerfil') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>


                        @if($IdTipoPerfil == 1 ) 
                            <div class="mb-4">
                                <label for="correoInstitucional" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Correo Institucional:</label>
                                <input type="email" wire:model="correoInstitucional"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="correoInstitucional" placeholder="Correo Institucional">
                                @error('persona.correoInstitucional') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="numeroCuenta" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Número de Cuenta:</label>
                                <input type="text" wire:model="numeroCuenta"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="numeroCuenta" placeholder="Número de Cuenta">
                                @error('persona.numeroCuenta') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        @if($IdTipoPerfil == 3) 
                            <div class="mb-4">
                                <label for="correoInstitucional" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Correo Institucional:</label>
                                <input type="email" wire:model="correoInstitucional"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="correoInstitucional" placeholder="Correo Institucional">
                                @error('persona.correoInstitucional') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="numeroCuenta" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Código de docente:</label>
                                <input type="text" wire:model="numeroCuenta"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="numeroCuenta" placeholder="Número de Cuenta">
                                @error('persona.numeroCuenta') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif


                        <div class="mb-4">
                            <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Descripción:</label>
                            <textarea wire:model="descripcion"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="descripcion" placeholder="Descripción"></textarea>
                            @error('descripcion') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                    </div>

                   

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-800">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button wire:click.prevent="store()" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-yellow-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Guardar
                            </button>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <button wire:click="closeModal()" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-900 dark:hover:border-gray-600 dark:focus:ring-gray-700 ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancelar
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
  
</div>

</html>