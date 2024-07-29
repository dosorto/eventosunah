<div>
    <form wire:submit.prevent="crearConferencista">
        <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" id="dni" wire:model="dni" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" wire:model="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" wire:model="apellido" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" id="correo" wire:model="correo" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="correoInstitucional">Correo Institucional</label>
            <input type="text" id="correoInstitucional" wire:model="correoInstitucional" class="form-control">
        </div>
        <div class="form-group">
            <label for="fechaNacimiento">Fecha de Nacimiento</label>
            <input type="date" id="fechaNacimiento" wire:model="fechaNacimiento" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="sexo">Sexo</label>
            <select id="sexo" wire:model="sexo" class="form-control" required>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" wire:model="direccion" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" wire:model="telefono" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="numeroCuenta">Número de Cuenta</label>
            <input type="text" id="numeroCuenta" wire:model="numeroCuenta" class="form-control">
        </div>
        <div class="form-group">
            <label for="IdNacionalidad">Nacionalidad</label>
            <select id="IdNacionalidad" wire:model="IdNacionalidad" class="form-control" required>
                @foreach($nacionalidades as $nacionalidad)
                    <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="IdTipoPerfil">Tipo de Perfil</label>
            <select id="IdTipoPerfil" wire:model="IdTipoPerfil" class="form-control" required>
                @foreach($tipoperfiles as $tipoperfile)
                    <option value="{{ $tipoperfile->id }}">{{ $tipoperfile->nombreTipoPerfil }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" wire:model="titulo" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" wire:model="descripcion" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" id="foto" wire:model="foto" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Crear Conferencista</button>
    </form>
</div>
