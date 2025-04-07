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
                font-size: 8px;
                page-break-after: always;
                font-weight: bold;
            }

            .main{
                position:relative;
            }
            .dimensions {
                position:absolute;
                left:80mm;
                top:3.5mm;
            }
            .property1 {
                position:absolute;
                left:19.5mm;
                top:26mm;
                font-size: 5px;
            }
            .property2 {
                position:absolute;
                left:19.5mm;
                top:30mm;
                font-size: 5px;
            }
            .property3 {
                position:absolute;
                left:19.5mm;
                top:34mm;
                font-size: 5px;
            }
            .property4 {
                position:absolute;
                left:19.5mm;
                top:38mm;
                font-size: 5px;
            }
            .property5 {
                position:absolute;
                left:37mm;
                top:26mm;
                font-size: 5px;
            }
            .property6 {
                position:absolute;
                left:37mm;
                top:30mm;
                font-size: 5px;
            }
            .property7 {
                position:absolute;
                left:37mm;
                top:34mm;
                font-size: 5px;
            }
            .property8 {
                position:absolute;
                left:37mm;
                top:38mm;
                font-size: 5px;
            }
            .date1 {
                position: absolute;
                left: 61mm;
                top: 35.5mm;
            }
            .date2 {
                position: absolute;
                left: 61mm;
                top: 42mm;
            }
            .lot {
                position: absolute;
                left: 81mm;
                top: 35.5mm;
            }
            .ref {
                position: absolute;
                left: 81mm;
                top: 42mm;
            }
            .barcode1{
                position: absolute;
                left: 57mm;
                top: 53mm;
            }
            .barcode1text{
                position: absolute;
                left: 69.5mm;
                top: 59mm;
            }
            .barcode2{
                position: absolute;
                left: 57mm;
                top: 64mm;
            }
            .barcode2text{
                position: absolute;
                left: 69.5mm;
                top: 70.5mm;
            }
            .serialno{
                position: absolute;
                left: 61mm;
                top: 46mm;
            }

        </style>
    </head>
        

    <body>

        @foreach($barcodes as $bar)
            @for ($i = 1; $i < $bar->quantity+1; $i++)

                <div class="main">
                    <img src="https://portal.rdglobal.com.tr/uretim/atlas-back.png" width="377.5mm"/>
                    <p class="dimensions">{{ $bar->dimensions }}</p>
                    <p class="serialno">{{ $bar->serialno }}-{{ Str::padLeft($i, 3, '0') }}</p>
                    <p class="property1">{{ $bar->property1 }}</p>
                    <p class="property2">{{ $bar->property2 }}</p>
                    <p class="property3">{{ $bar->property3 }}</p>
                    <p class="property4">{{ $bar->property4 }}</p>
                    <p class="property5">{{ $bar->property5 }}</p>
                    <p class="property6">{{ $bar->property6 }}</p>
                    <p class="property7">{{ $bar->property7 }}</p>
                    <p class="property8">{{ $bar->property8 }}</p>
                    <p class="date1">{{ $bar->date1 }}</p>
                    <p class="date2">{{ $bar->date2 }}</p>
                    <p class="lot">{{ $bar->lot }}</p>
                    <p class="ref">{{ $bar->ref }}</p>
                    <div class="barcode1">{!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 1.2) !!}</div>
                    <p class="barcode1text">{{ $bar->barcode }}</p>
                    <div class="barcode2">{!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 1.2) !!}</div>
                    <p class="barcode2text">{{ $bar->barcode }}</p>
                </div>

            @endfor
        @endforeach

    </body>

</html>
