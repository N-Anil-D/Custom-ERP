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
            .lot {
                position: absolute;
                left: 5mm;
                top: 14.3mm;
            }
            .ref {
                position: absolute;
                left: 36.5mm;
                top: 6mm;
                transform: rotate(90deg);
            }
            .barcode{
                position: absolute;
                left: 4mm;
                top: 4mm;
            }
            .barcodetext{
                position: absolute;
                left: 13mm;
                top: 11mm;
            }
            .name{
                position: absolute;
                left: 35mm;
                top: 6mm;
                transform: rotate(90deg);
                font-size: 4px;
                width: 16mm;
                height: 5mm;
            }
            .date1{
                position: absolute;
                left: 16.2mm;
                top: 14.3mm;
            }

        </style>
    </head>
        

    <body>

        @foreach($barcodes as $bar)
            @if($bar->quantity >0)
            @for ($i = 1; $i < $bar->quantity+1; $i++)
                <div class="main">
                    <img src="https://portal.rdglobal.com.tr/uretim/tube-back-v3.png" width="188.7mm"/>
                    @if($bar->lot)<p class="lot">{{ $bar->lot }}</p>@endif
                    @if($bar->ref)<p class="ref">{{ $bar->ref }}</p>@endif
                    @if($bar->barcode)
                        <div class="barcode">{!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 0.8) !!}</div>
                        <p class="barcodetext">{{ $bar->barcode }}</p>
                    @endif
                    @if($bar->name)<p class="name">{{ Str::limit($bar->name,92) }}</p>@endif
                    @if($bar->date1)<p class="date1">{{ $bar->date1 }}</p>@endif
                </div>
            @endfor
            @else
                <div class="main">
                    <img src="https://portal.rdglobal.com.tr/uretim/tube-back-v2.png" width="188.7mm"/>
                    @if($bar->lot)<p class="lot">{{ $bar->lot }}</p>@endif
                    @if($bar->ref)<p class="ref">{{ $bar->ref }}</p>@endif
                    @if($bar->barcode)
                        <div class="barcode">{!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 0.8) !!}</div>
                        <p class="barcodetext">{{ $bar->barcode }}</p>
                    @endif
                    @if($bar->name)<p class="name">{{ Str::limit($bar->name,92) }}</p>@endif
                    @if($bar->date1)<p class="date1">{{ $bar->date1 }}</p>@endif
                </div>
            @endif
        @endforeach

    </body>

</html>
