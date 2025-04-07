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
                font-size: 5px;
                page-break-after: always;
                font-weight: bold;
            }

            .main{
                position:relative;
            }
            .title{
                position: absolute;
                left: 36.5mm;
                top: 5.8mm;
                transform: rotate(90deg);
                font-size: 6px;
                width: 16.1mm;
                height: 3mm;
            }
            .ref {
                position: absolute;
                left: 38mm;
                top: 6mm;
                transform: rotate(90deg);
            }
            .lot {
                position: absolute;
                left: 5.6mm;
                top: 14mm;
            }
            .date1{
                position: absolute;
                left: 16.5mm;
                top: 14mm;
            }
            .barcode{
                position: absolute;
                left: 5.5mm;
                top: 5mm;
            }
            .barcodetext{
                position: absolute;
                left: 13mm;
                top: 11mm;
            }
           
           

        </style>
    </head>
        

    <body>

        @foreach($barcodes as $bar)
            @if($bar->quantity >0)
                @for ($i = 1; $i < $bar->quantity+1; $i++)
                    <div class="main">
                        <img src="{{ url('uretim/mini/besiyeri/tup-v2.png') }}" width="188mm"/>
                        @if($bar->title)<p class="title">{{ Str::limit($bar->title,92) }}</p>@endif
                        @if($bar->lot)<p class="lot">{{ $bar->lot }}</p>@endif
                        @if($bar->ref)<p class="ref">{{ $bar->ref }}</p>@endif
                        @if($bar->barcode)
                            <div class="barcode">{!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 0.8,25) !!}</div>
                            <p class="barcodetext">{{ $bar->barcode }}</p>
                        @endif
                        @if($bar->date1)<p class="date1">{{ $bar->date1 }}</p>@endif
                    </div>
                @endfor
            @endif
        @endforeach

    </body>

</html>
