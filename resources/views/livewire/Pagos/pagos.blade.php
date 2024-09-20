<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Pago de conferencia</title>
    <link rel="stylesheet" href="{{ asset('css/pagos.css') }}">
</head>
<body>

<div class="container dark:bg-gray-900">

    <form class="dark:bg-gray-800 bg-white" action="">

        <div class="row dark:bg-gray-800 dark:text-white text bg-white">

            <div class="col dark:bg-gray-800 dark:text-white">

                <h3 class="title dark:text-white">Dirección de facturación</h3>
                <div class="inputBox">
                    <span>Conferencia:</span>
                    <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" disabled value="{{$conferencia->nombre}}" disabled placeholder="Nombre de conferencia">
                </div>
                <div class="inputBox">
                    <span>Nombre completo:</span>
                    <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" disabled value="{{$persona->nombre}} {{$persona->apellido}}" placeholder="Acxel Fernando Aplicano Aguilar">
                </div>
                <div class="inputBox">
                    <span>Correo:</span>
                    <input type="email" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" disabled value="{{$persona->correo}}" placeholder="example@example.com">
                </div>
                <div class="inputBox">
                    <span>Dirección :</span>
                    <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" disabled value="{{$persona->direccion}}"  placeholder="casa - calle - comunidad">
                </div>
                <div class="inputBox">
                    <span>Ciudad:</span>
                    <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="Choluteca">
                </div>

                <div class="flex">
                    <div class="inputBox">
                        <span>Pais:</span>
                        <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="Honduras">
                    </div>
                    <div class="inputBox">
                        <span>Postal:</span>
                        <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="123 456">
                    </div>
                </div>

            </div>

            <div class="col">

                <h3 class="title">Datos de pago:</h3>
                <div class="inputBox">
                    <span>Monto:</span>
                    <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" value="{{$conferencia->precio}}" disabled placeholder="Precio de la conferencia">
                </div>
                <div class="inputBox">
                    <span>Targetas aceptadas:</span>
                    <img src="{{ asset('targetas/card_img.png') }}" alt="tarjetas aceptadas">
                </div>
                <div class="inputBox">
                    <span>Nombre en la tarjeta:</span>
                    <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="Acxel Fernando Aplicano Aguilar">
                </div>
                <div class="inputBox">
                    <span>Número de tarjeta de crédito:</span>
                    <input type="number" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="1111-2222-3333-4444">
                </div>
                <div class="inputBox">
                    <span>Mes de expiración:</span>
                    <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="enero">
                </div>

                <div class="flex">
                    <div class="inputBox">
                        <span>Año de expiración:</span>
                        <input type="number" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="2026">
                    </div>
                    <div class="inputBox">
                        <span>CVV :</span>
                        <input type="text" class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="1234">
                    </div>
                </div>

            </div>
    
        </div>

        <input type="submit" value="Realizar pago" class="submit-btn">

    </form>

</div>    
    
</body>
</html>