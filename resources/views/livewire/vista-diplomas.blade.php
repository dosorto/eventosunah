<div>
    <x-layouts.reportes>
        <header>
            <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: 'Open Sans', sans-serif;
                    background-color: #f4f4f4;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;

                }

                .certificado {
                    background-color: white;
                    width: 800px;
                    height: 500px;
                    /* Aumenta el alto */
                    border-radius: 10px;
                    box-shadow: 0px 0px 10px rgba(0, 0, 0, .1);
                    padding: 20px;
                    position: relative;
                    justify-content: center;
                    align-items: center;
                }

                .gold-swirls {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 1;
                }

                .gold-swirls .fondo {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    border-radius: 10px;
                }

                .certificado-header,
                .certificado-body,
                .firmas,
                .sello {
                    position: relative;
                    text-align: center;
                    z-index: 2;
                    color: black;
                }

                .certificado-title {
                    font-size: 3.6em;
                    color: black;
                    font-weight: 800;
                    padding: 0;
                    margin-left: 50px;
                }

                .certificado-title2 {
                    font-size: 2em;
                    color: black;
                    font-weight: 600;
                    margin: 1px;
                    padding: 0;
                    margin-left: 50px;
                }

                .certificado-title3 {
                    font-size: 1em;
                    margin: 5px 0;
                    padding: 0;
                    margin-left: 50px;
                }

                .recipient-name {
                    text-align: center;
                    font-size: 3.2em;
                    font-weight: 400;
                    letter-spacing: 3px;
                    font-family: 'Great Vibes', cursive;
                    margin: 15px 0;
                }

                .certificado-body {
                    text-align: center;
                    font-size: 12px;
                    padding: 0 7%;
                    color: black;
                    margin-bottom: 60px;
                }

                .sello {
                    text-align: center;
                    margin-top: -25px;
                    margin-right: 20px;
                }

                .sello .fondo {
                    height: 64px;
                    width: 64px;
                }

                .firmass {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 1px;
                    font-size: 10px;
                    margin-left: 40px;
                    margin-right: 40px;
                    font-weight: bold;

                }

                .firmas {
                    text-align: center;
                    margin: 0 10px;

                }

                .firma {
                    margin-top: -30px;
                    margin-left: 25px;
                    align-items: center;
                }

                .firma .fondo {
                    height: 64px;
                    width: 64px;
                    margin-bottom: -17px;

                }

                .qr-code {
                    margin-top: 40px;
                    height: 80px;
                    width: 80px;
                }
            </style>

        </header>

        <body>
            <div class="p-4 sm:mx-64 mt-20 certificado">
                <div class="gold-swirls">
                    <img class="fondo" src="{{ asset('Logo/certificado3.png')}}" />
                </div>
                <div class="certificado-header" style="display: flex; justify-content: flex-end;">
                    <div class="certificado-header">
                        <div class="certificado-title">CERTIFICADO</div>
                        <div class="certificado-title2">DE RECONOCIMIENTO</div>
                        <div class="certificado-title3">OTORGADO A:</div>
                        <div class="recipient-name">{{ $persona->nombre }} {{$persona->apellido}}</div>
                    </div>
                    <div>
                        <img class="qr-code" src="data:image/png;base64,{{ $qrcode }}" alt="Código QR">
                    </div>
                </div>
                <div class="certificado-body">
                    Por haber asistido cumplidamente a la conferencia de {{$conferencia->nombre}} Realizada el
                    {{ \Carbon\Carbon::parse($conferencia->fecha)->format('d \d\e F \d\e Y') }}.
                </div>

                <div class="firmass">
                    <div class="firmas">
                        <div class="firma">
                            <img class="fondo" src="{{ asset('Logo/firma1.png') }}" />
                        </div>
                        <ul>
                            <li>
                                <ul
                                    class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-900 dark:border-gray-900">
                            </li>
                        </ul>
                        <div>Acxel Fernando Aplicano</div>
                        <div>Coordinador General</div>
                    </div>
                    <div class="sello">
                        <img class="fondo" src="{{ asset('Logo/sello.png') }}" />
                    </div>
                    <div class="firmas">
                    <div class="firma">
                        <img class="fondo" src="{{ asset('Logo/firma2.png') }}" />
                    </div>
                        <ul>
                            <li>
                                <ul
                                    class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-900 dark:border-gray-900">
                            </li>
                        </ul>
                        <div>Acxel Fernando Aplicano</div>
                        <div>Coordinador General</div>
                    </div>
                    <div class="sello">
                        <img class="fondo" src="{{ asset('Logo/sello.png') }}" />
                    </div>
                    <div class="firmas">
                    <div class="firma">
                        <img class="fondo" src="{{ asset('Logo/firma3.png') }}" />
                    </div>
                        <ul>
                            <li>
                                <ul
                                    class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-900 dark:border-gray-900">
                            </li>
                        </ul>
                        <div>Acxel Fernando Aplicano</div>
                        <div>Coordinador General</div>
                    </div>
                    <div class="sello">
                        <img class="fondo" src="{{ asset('Logo/sello.png') }}" />
                    </div>
                </div>
            </div>
        </body>
    </x-layouts.reportes>

</div>