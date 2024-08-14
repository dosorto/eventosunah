<div>
    @if ($persona && $conferencia)
        <h1>Validación de Diploma</h1>
        <p><strong>Nombre:</strong> {{ $persona->nombre }} {{$persona->apellido}}</p>
        <p><strong>Conferencia:</strong> {{ $conferencia->nombre }}</p>
        <p><strong>Código Diploma:</strong> {{ $codigoDiploma->Codigo }}</p>
        <p>Este diploma es válido y está asociado a la conferencia mencionada.</p>
    @else
        <p>No se ha encontrado información asociada con este código QR.</p>
    @endif
</div>
