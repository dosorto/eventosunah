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
                    width: 1200px;
                    height: 700px;
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
                .sello,
                .codigo {
                    margin-top: 20px;
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
                }

                .certificado-title2 {
                    font-size: 2em;
                    color: black;
                    font-weight: 600;
                    margin: 1px;
                    padding: 0;
                }

                .certificado-title3 {
                    font-size: 1em;
                    margin: 5px 0;
                    padding: 0;
                }

                .recipient-name {
                    text-align: center;
                    font-size: 3.2em;
                    font-weight: 400;
                    letter-spacing: 3px;
                    font-family: 'Great Vibes', cursive;
                    margin: 20px 0;
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
                    margin-top: -68px;
                    margin-left: 88px;
                }

                .firmass {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 1px;
                    font-size: 10px;
                    margin-left: 190px;
                    margin-right: 190px;
                    font-weight: bold;
                }

                .firmas {
                    text-align: center;
                    margin: 0 30px;
                }

                .firma {
                    margin-top: -30px;
                    margin-left: 25px;
                    align-items: center;
                }

                .firma .fondo {
                    height: 84px;
                    width: 84px;
                    margin-bottom: -17px;
                }

                .qr-code {
                    height: 80px;
                    width: 80px;
                    margin-top: 20px;
                    margin-left: 455px;
                    z-index: 2;
                }

                .codigo {
                    font-size: 12px;
                    margin-top: 45px;
                    text-align: center;
                    left: 120px;
                    position: absolute;
                    margin-right: 410px;
                }
            </style>
        </header>

        <body>
            <div class="p-4 sm:mx-64 mt-20 certificado">
                <div class="gold-swirls">
                    <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Plantilla)) }}" />
                </div>
                <div class="certificado-header" style="">
                    <div class="certificado-title">CERTIFICADO</div>
                    <div class="certificado-title2">DE RECONOCIMIENTO</div>
                    <div class="certificado-title3">OTORGADO A:</div>
                    <div class="recipient-name">{{ $persona->nombre }} {{$persona->apellido}}</div>
                </div>
                <div class="certificado-body">
                    Por su destacada asistencia y participación en la conferencia "{{$conferencia->nombre}}", presentada
                    por el distinguido {{$conferencia->conferencista->titulo}}
                    {{$conferencia->conferencista->persona->nombre}} {{$conferencia->conferencista->persona->apellido}},
                    celebrada el {{ \Carbon\Carbon::parse($conferencia->fecha)->format('d \d\e F \d\e Y') }} en el marco
                    del evento "{{$evento->nombreevento}}".
                    <div>
                        <img class="qr-code" src="data:image/png;base64,{{ $qrcode }}" alt="Código QR">
                    </div>
                </div>

                <div class="firmass">
                    @if($diploma->Firma1 || $diploma->Sello1 || $diploma->NombreFirma1 || $diploma->Titulo1)
                    <div class="firmas">
                        @if($diploma->Firma1)
                        <div class="firma">
                            <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Firma1)) }}" />
                        </div>
                        @endif

                        @if($diploma->Sello1)
                        <div class="sello">
                            <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Sello1)) }}" />
                        </div>
                        @endif

                        @if($diploma->NombreFirma1)
                        <div>{{$diploma->NombreFirma1}}</div>
                        @endif

                        @if($diploma->Titulo1)
                        <div>{{$diploma->Titulo1}}</div>
                        @endif
                    </div>
                    @endif

                    @if($diploma->Firma2 || $diploma->Sello2 || $diploma->NombreFirma2 || $diploma->Titulo2)
                    <div class="firmas">
                        @if($diploma->Firma2)
                        <div class="firma">
                            <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Firma2)) }}" />
                        </div>
                        @endif

                        @if($diploma->Sello2)
                        <div class="sello">
                            <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Sello2)) }}" />
                        </div>
                        @endif

                        @if($diploma->NombreFirma2)
                        <div>{{$diploma->NombreFirma2}}</div>
                        @endif

                        @if($diploma->Titulo2)
                        <div>{{$diploma->Titulo2}}</div>
                        @endif
                    </div>
                    @endif

                    @if($conferencia->conferencista->firma || $conferencia->conferencista->sello || $conferencia->conferencista->persona->nombre || $conferencia->conferencista->titulo)
                    <div class="firmas">
                        @if($conferencia->conferencista->firma)
                        <div class="firma">
                            <img class="fondo" src="{{ asset(str_replace('public', 'storage', $conferencia->conferencista->firma)) }}" />
                        </div>
                        @endif

                        @if($conferencia->conferencista->sello)
                        <div class="sello">
                            <img class="fondo" src="{{ asset(str_replace('public', 'storage', $conferencia->conferencista->sello)) }}" />
                        </div>
                        @endif
                        <p>______________________________</p>
                        @if($conferencia->conferencista->persona->nombre)
                        <div>{{$conferencia->conferencista->persona->nombre }} {{$conferencia->conferencista->persona->apellido}}</div>
                        @endif

                        @if($conferencia->conferencista->titulo)
                        <div>{{$conferencia->conferencista->titulo}}</div>
                        @endif
                    </div>
                    @endif
                </div>

                @if (is_null($uuid))
                <p class="codigo">Certificado sin validación, un no extendido</p>
                @else
                <p class="codigo">Código: {{ $uuid }}</p>
                @endif
            </div>
        </body>

        <button onclick="imprimir()" id="imprimir"
            class="absolute top-4 right-4 inline-flex items-center px-4 py-2 text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800 rounded-lg">
            Imprimir
        </button>

        <script>
            function imprimir() {
                // Configurar el tamaño del papel en carta y ponerlo en modo horizontal
                var css = '@page { size: letter landscape; margin: 0; } body { margin: 0; padding: 0; }',
                    head = document.head || document.getElementsByTagName('head')[0],
                    style = document.createElement('style');

                style.type = 'text/css';
                style.media = 'print';

                if (style.styleSheet) {
                    style.styleSheet.cssText = css;
                } else {
                    style.appendChild(document.createTextNode(css));
                }

                head.appendChild(style);

                // Ocultar botones
                document.getElementById('imprimir').style.display = 'none';

                // Esperar un momento para aplicar el estilo antes de imprimir
                setTimeout(function () {
                    window.print();

                    // Volver a ponerlo en modo vertical
                    css = '@page { size: letter portrait; margin: 0; }';
                    style = document.createElement('style');
                    style.type = 'text/css';
                    style.media = 'print';

                    if (style.styleSheet) {
                        style.styleSheet.cssText = css;
                    } else {
                        style.appendChild(document.createTextNode(css));
                    }

                    head.appendChild(style);

                    // Mostrar botones
                    document.getElementById('imprimir').style.display = 'block';
                }, 500);
            }
        </script>
    </x-layouts.reportes>
</div>
