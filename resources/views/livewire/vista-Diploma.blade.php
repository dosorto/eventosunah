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
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
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
                    margin: 0;
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
                }

                .sello .fondo {
                    height: 64px;
                    width: 64px;
                    margin: 0 auto;
                }

                .firmas {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 1px;
                    font-size: 10px;
                    margin: 0 190px;
                    font-weight: bold;
                }

                .firma {
                    display: flex;
                    flex-direction: column;
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
                    z-index: 2;
                }

                .codigo {
                    font-size: 12px;
                    margin-top: 45px;
                    text-align: center;
                }
            </style>
        </header>

        <body>
            <div class="certificado">
                <div class="gold-swirls">
                    <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Plantilla)) }}" />
                </div>
                <div class="certificado-header">
                    <div class="certificado-title">CERTIFICADO</div>
                    <div class="certificado-title2">DE RECONOCIMIENTO</div>
                    <div class="certificado-title3">OTORGADO A:</div>
                    <div class="recipient-name">{{ $persona->nombre }} {{ $persona->apellido }}</div>
                </div>

                <div class="certificado-body">
                    Por su destacada asistencia y participación en la conferencia "{{ $conferencia->nombre }}", presentada por el distinguido {{ $conferencia->conferencista->titulo }} {{ $conferencia->conferencista->persona->nombre }} {{ $conferencia->conferencista->persona->apellido }}, 
                    celebrada el {{ \Carbon\Carbon::parse($conferencia->fecha)->format('d \d\e F \d\e Y') }} en el marco del evento "{{ $conferencia->evento->nombreevento }}".
                    <div>
                        <img class="qr-code" src="data:image/png;base64,{{ $qrcode }}" alt="Código QR">
                    </div>
                </div>

                <div class="firmas">
                    <div class="firma">
                        <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Firma1)) }}" />
                        <div>{{ $diploma->NombreFirma1 }}</div>
                        <div>{{ $diploma->Titulo1 }}</div>
                    </div>
                    <div class="firma">
                        <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Firma2)) }}" />
                        <div>{{ $diploma->NombreFirma2 }}</div>
                        <div>{{ $diploma->Titulo2 }}</div>
                    </div>
                    <div class="firma">
                        <img class="fondo" src="{{ asset(str_replace('public', 'storage', $diploma->Firma3)) }}" />
                        <div>{{ $diploma->NombreFirma3 }}</div>
                        <div>{{ $diploma->Titulo3 }}</div>
                    </div>
                </div>
                
                <p class="codigo">Código: {{ $diploma->Codigo }}</p>
            </div>
        </body>
    </x-layouts.reportes>
</div>
