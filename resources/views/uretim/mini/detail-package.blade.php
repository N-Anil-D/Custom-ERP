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
                font-size: 10pt;
                page-break-after: always;
                font-weight: bold;
            }

            .main{
                position:relative;
            }
            .title{
                position: absolute;
                left: 0mm;
                top: 8mm;
                font-size: 16pt;
                width: 120mm;
                text-align: center;
            }
            .subtitle{
                position: absolute;
                left: 0mm;
                top: 17mm;
                font-size: 10pt;
                width: 120mm;
                text-align: center;
            }
            .amount{
                position: absolute;
                left: 12mm;
                top: 29.4mm;
            }
            .barcode{
                position: absolute;
                left: 44.5mm;
                top: 27mm;
            }
            .barcodetext{
                position: absolute;
                left: 48.5mm;
                top: 31.5mm;
            }
            .ref {
                position: absolute;
                left: 13.6mm;
                top: 41mm;
            }
            .lot {
                position: absolute;
                left: 65mm;
                top: 41mm;
            }          
            .date1{
                position: absolute;
                left: 95mm;
                top: 41mm;
            }

        </style>
    </head>
        

    <body>

        @foreach($barcodes as $bar)
            @if($bar->quantity >0)
            @for ($i = 1; $i < $bar->quantity+1; $i++)
                <div class="main">
                    <img src="{{ url('uretim/mini/koli_mini_etiket_'.$bar->color.'.png') }}" width="452.88mm"/>
                    @if($bar->title)<p class="title">{{ $bar->title }}</p>@endif
                    @if($bar->subtitle)<p class="subtitle">{{ $bar->subtitle }}</p>@endif
                    @if($bar->amount)<p class="amount">{{ $bar->amount }} pcs</p>@endif
                    @if($bar->barcode)
                        <div class="barcode">{!! DNS1D::getBarcodeHTML($bar->barcode, 'C128',1) !!}</div>
                        <p class="barcodetext">{{ $bar->barcode }}</p>
                    @endif
                    @if($bar->lot)<p class="lot">{{ $bar->lot }}</p>@endif
                    @if($bar->ref)<p class="ref">{{ $bar->ref }}</p>@endif
                    @if($bar->date1)<p class="date1">{{ $bar->date1 }}</p>@endif
                </div>
            @endfor
            @endif
        @endforeach

    </body>

</html>
