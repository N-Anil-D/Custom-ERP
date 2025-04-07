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
                left: 41.5mm;
                top: 24.5mm;
            }
            .subtitle{
                position: absolute;
                left: 41.5mm;
                top: 33.2mm;
                font-size: 10pt;
            }
            .size{
                position: absolute;
                left: 41.5mm;
                top: 37.7mm;
                font-size: 12pt;
            }
            .ref{
                position: absolute;
                left: 15mm;
                top: 46mm;
                font-size: 10pt;
            }
            .lot{
                position: absolute;
                left: 15mm;
                top: 56mm;
                font-size: 10pt;
            }
            .date1{
                position: absolute;
                left: 9mm;
                top: 66mm;
                font-size: 12pt;
            }
            .barcode{
                position: absolute;
                left: 3mm;
                top: 76mm;
            }
            .barcode-text{
                position: absolute;
                left: 5mm;
                top:81mm;
                font-size: 10pt;
            }

        </style>
    </head>
        

    <body>

        @foreach($barcodes as $bar)
            @for ($i = 1; $i < $bar->quantity+1; $i++)

                <div class="main">
                    <img src="{{ url("uretim/serum-etiket/koli_etiketi_".$bar->color."_300.png") }}" width="566.1mm"/>
                    <p class="title">{{ $bar->title }}</p>
                    <p class="subtitle">{{ $bar->subtitle }}</p>
                    <p class="size">{{ $bar->size }}</p>
                    <p class="ref">{{ $bar->ref }}</p>
                    <p class="lot">{{ $bar->lot }}</p>
                    <p class="date1">{{ $bar->date1 }}</p>
                    @if($bar->barcode)
                    <div class="barcode">
                        {!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 0.9, 30) !!}
                    </div>
                    <p class="barcode-text">{{ $bar->barcode }}</p>
                    @endif
                </div>
                
            @endfor
        @endforeach
        

    </body>

</html>
