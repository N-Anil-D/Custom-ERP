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
                font-size: 3pt;
                page-break-after: always;
                font-weight: bold;
            }

            .main{
                position:relative;
            }
            .title{
                position: absolute;
                left: 0mm;
                top: 1.5mm;
                width: 15mm;
                text-align: center;
            }
            .ref {
                position: absolute;
                left: 4.5mm;
                top: 8mm;
            }
            .lot {
                position: absolute;
                left: 4.5mm;
                top: 11.2mm;
            }          
            .date1{
                position: absolute;
                left: 3mm;
                top: 14.5mm;
            }

        </style>
    </head>
        

    <body>

        @foreach($barcodes as $bar)
            @if($bar->quantity >0)
            @for ($i = 1; $i < $bar->quantity+1; $i++)
                <div class="main">
                    <img src="{{ url('uretim/mini/tup_mini_etiket_'.$bar->color.'.png') }}" width="56.61mm"/>
                    @if($bar->title)<p class="title">{{ $bar->title }}</p>@endif
                   
                    @if($bar->lot)<p class="lot">{{ $bar->lot }}</p>@endif
                    @if($bar->ref)<p class="ref">{{ $bar->ref }}</p>@endif
                    @if($bar->date1)<p class="date1">{{ $bar->date1 }}</p>@endif
                </div>
            @endfor
            @endif
        @endforeach

    </body>

</html>
