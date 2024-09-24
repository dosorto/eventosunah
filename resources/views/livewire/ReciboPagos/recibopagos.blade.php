<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Pago de Evento</title>
    <link rel="stylesheet" href="{{ asset('css/pagos.css') }}">
    @livewireStyles
</head>
<body>

<div class="container dark:bg-gray-900">

    <form wire:submit.prevent="realizarPago" class="dark:bg-gray-800 bg-white" enctype="multipart/form-data">

        <h3 class="title dark:text-white">Detalles de Pago</h3>

        <div class="inputBox">
            <span>Evento:</span>
            <input type="text" class="bg-gray-100 dark:bg-gray-700 dark:text-white" disabled value="{{ $evento->nombre }}" placeholder="Nombre del evento">
        </div>
        <div class="inputBox">
            <span>Nombre completo:</span>
            <input type="text" class="bg-gray-100 dark:bg-gray-700 dark:text-white" disabled value="{{ $persona->nombre }} {{ $persona->apellido }}" placeholder="Nombre completo">
        </div>
        <div class="inputBox">
            <span>Correo:</span>
            <input type="email" class="bg-gray-100 dark:bg-gray-700 dark:text-white" disabled value="{{ $persona->correo }}" placeholder="example@example.com">
        </div>

        <div class="inputBox">
            <span>Foto del Pago:</span>
            <input type="file" wire:model="foto" class="bg-gray-100 dark:bg-gray-700 dark:text-white">
            @error('foto') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <input type="submit" value="Realizar Pago" class="submit-btn">

    </form>

</div>

@livewireScripts
</body>
</html>
