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
                    padding: 0;
                }

                .certificado {
                    width: 100%;
                    height: 100%;
                    background-color: #fff;
                    border-radius: 10px;
                    box-shadow: 0px 0px 10px rgba(0, 0, 0, .1);
                    padding: 0;
                    margin: 0;
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
                    margin-top: 70px;
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
                  
                }

                .certificado-body {
                    text-align: center;
                    font-size: 15px;
                    padding: 0 7%;
                    color: black;
                    margin-bottom: 60px;
                }

                .sello {
                    text-align: center;
                    margin-top: -25px;
                    margin-right: 20px;
                    z-index: 2;
                }

                .sello .fondo {
                    height: 64px;
                    width: 64px;
                    margin-top: -50px;
                    margin-left: 88px;
                    z-index: 2;
                }

                .firmass {
                   
                    margin-top: 1px;
                    font-size: 10px;
                    font-weight: bold;
                    z-index: 2;
                }

                .firmas {
                    text-align: center;
                    margin: 0 5px; 
                    align-items: center;
                }

                .firma {
                    margin-top: -30px;
                    align-items: center;
                }

                .firma .fondo {
                    height: 84px;
                    width: 84px;
                    margin-bottom: -40px;
                }

                .qr-code {
                    height: 80px;
                    width: 80px;
                    margin-top: 20px;
                    margin-left: 30px;
                    z-index: 2;
                    background: none;
                }

                .codigo {
                    font-size: 12px;
                    margin-top: 45px;
                    text-align: center;
                    left: 120px;
                    position: absolute;
                    margin-right: 410px;
                    z-index: 2;
                }
            </style>
        </header>

        <body>
            <div class="p-4 sm:mx-64 mt-20 certificado">
                <div class="gold-swirls">
                    <img class="fondo" src="{{$Plantilla}}" alt="Diploma">
                </div>
                <div class="certificado-header">
                    <div class="certificado-title">CERTIFICADO</div>
                    <div class="certificado-title2">DE RECONOCIMIENTO</div>
                    <div class="certificado-title3">OTORGADO A:</div>
                    <div class="recipient-name">{{ $Nombre }} {{ $Apellido }}</div>
                </div>
                <div class="certificado-body">
                    Por su destacada asistencia y participación en la conferencia "{{ $Conferencia }}", presentada por el distinguido {{ $TituloConferencista }} {{ $Conferencista }} celebrada el {{ \Carbon\Carbon::parse($FechaConferencia)->format('d \d\e F \d\e Y') }} en el marco del evento "{{ $Evento }}".
                    <div>
                        <img class="qr-code" src="data:image/png;base64,{{ $qrcode }}" alt="Código QR">
                    </div>
                </div>
                <div class="firmass">
                    <table style="width: 100%; text-align: center;">
                        <tr>
                        @if($Firma1 || $Sello1 || $NombreFirma1 || $Titulo1)
                            <td class="firmas">
                                <div class="firma">
                                    <img class="fondo" src="{{ $Firma1 }}" />
                                </div>
                                <p>______________________________</p>
                                <div></div>
                                <div class="sello">
                                    <img class="fondo" src="{{ $Sello1 }}" />
                                </div>
                                <div>{{ $NombreFirma1 }}</div>
                                <div>{{ $Titulo1 }}</div>
                            </td>
                            @endif
                            @if($Firma2 || $Sello2 || $NombreFirma2 || $Titulo2)
                            <td class="firmas">
                                <div class="firma">
                                    <img class="fondo" src="{{ $Firma2 }}" />
                                </div>
                                <p>______________________________</p>
                                <div></div>
                                <div class="sello">
                                    <img class="fondo" src="{{ $Sello2 }}" />
                                </div>
                                <div>{{ $NombreFirma2 }}</div>
                                <div>{{ $Titulo2 }}</div>
                            </td>
                            @endif
                            @if($FirmaConferencista || $SelloConferencista || $Conferencista || $TituloConferencista)
                            <td class="firmas">
                                <div class="firma">
                                    <img class="fondo" src="{{ $FirmaConferencista }}" />
                                </div>
                                <p>______________________________</p>
                                <div></div>
                                <div class="sello">
                                    <img class="fondo" src="{{ $SelloConferencista }}" />
                                </div>
                                <div>{{ $Conferencista }}</div>
                                <div>{{ $TituloConferencista }}</div>
                            </td>
                            @endif
                        </tr>
                    </table>
                </div>
                <p class="codigo">Código: {{$uuid}}</p>
            </div>
        </body>
    </x-layouts.reportes>
</div>