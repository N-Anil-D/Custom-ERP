<!DOCTYPE html>
<html lang="tr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <link rel="preconnect" href="https://fonts.googleapis.com"/>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
        <link href="https://fonts.googleapis.com/css2?family=Macondo&display=swap" rel="stylesheet"/>

        <title>INVAportal - Barcode Document</title>
        
        <style>

            html {
                margin: 0px;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 17pt;
                page-break-after: always;
                font-weight: bold;
            }

            .main{
                position:relative;
            }
            .title{
                position: absolute;
                top: 24mm;
                width: 145mm;
                text-align: center;
                font-size: 14pt;
            }
            .subtitle{
                position: absolute;
                top: 33mm;
                width: 145mm;
                font-size: 12pt;
                text-align: center;
            }
            
            .ref{
                position: absolute;
                left: 15mm;
                top: 42mm;
                font-size: 10pt;
            }
            .lot{
                position: absolute;
                left: 61mm;
                top: 42mm;
                font-size: 10pt;
            }
            .date1{
                position: absolute;
                left: 115mm;
                top: 42mm;
                font-size: 10pt;
            }
            .barcode{
                position: absolute;
                left: 50mm;
                top: 53mm;
            }
            .barcode-text{
                position: absolute;
                top:60mm;
                font-size: 10pt;
                width: 149mm;
                text-align: center
            }

        </style>
    </head>
        

    <body>

        @foreach($barcodes as $bar)
            @for ($i = 1; $i < $bar->quantity+1; $i++)

                <div class="main">
                    <img src="{{ url("uretim/mini/besiyeri/koli.png") }}" width="566mm"/>
                    <p class="title">{{ $bar->title }}</p>
                    <p class="subtitle">{{ $bar->subtitle }}</p>
                    <p class="ref">{{ $bar->ref }}</p>
                    <p class="lot">{{ $bar->lot }}</p>
                    <p class="date1">{{ $bar->date1 }}</p>
                    @if($bar->barcode)
                    <div class="barcode">
                        {!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 1.5, 35) !!}
                    </div>
                    <p class="barcode-text">{{ $bar->barcode }}</p>
                    @endif
                </div>
                
            @endfor
        @endforeach
        

    </body>

</html>
