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
                height: 199mm;
            }

            .title1{
                position: absolute;
                left: 0mm;
                top: 2mm;
                width: 99mm;
                text-align: center;
                font-size: 10px;
            }
            .title2{
                position: absolute;
                left: 0mm;
                top: 37mm;
                width: 99mm;
                text-align: center;
                font-size: 10px;
            }
            .dimension1 {
                position:absolute;
                left:0mm;
                top:8mm;
                width: 99mm;
                text-align: center;
                font-size: 10px;
            }
            .dimension2 {
                position:absolute;
                left:0mm;
                top:43mm;
                width: 99mm;
                text-align: center;
                font-size: 10px;
            }
            .dimension3 {
                position:absolute;
                left:63mm;
                top:75mm;
            }
            .dimension4 {
                position:absolute;
                left:5mm;
                top:93mm;
            }
            .dimension5 {
                position:absolute;
                left:41mm;
                top:190mm;
                /* width: 99mm; */
                /* text-align: center; */
            }
            .barcode1 {
                position:absolute;
                left:29mm;
                top:14mm;
            }
            .barcode1-text {
                position:absolute;
                left:0mm;
                top:20mm;
                width: 99mm;
                text-align: center;
                font-size:10px;
            }
            .barcode2 {
                position:absolute;
                left:29mm;
                top:49mm;
            }
            .barcode2-text {
                position:absolute;
                left:0mm;
                top:55mm;
                width: 99mm;
                text-align: center;
                font-size:10px;
            }
            .lot1{
                position:absolute;
                left:66mm;
                top:25mm;
            }
            .lot2{
                position:absolute;
                left:66mm;
                top:60mm;
            }
            .lot3{
                position:absolute;
                left:85mm;
                top:193mm;
            }
            .ref1{
                position:absolute;
                left:66mm;
                top:28mm;
            }
            .ref2{
                position:absolute;
                left:66mm;
                top:63mm;
            }
            .date1-1{
                position:absolute;
                left:12mm;
                top:25mm;
            }
            .date1-2{
                position:absolute;
                left:12mm;
                top:60mm;
            }
            .date1-3{
                position:absolute;
                left:85mm;
                top:187mm;
            }
            .date2-1{
                position:absolute;
                left:12mm;
                top:28mm;
            }
            .date2-2{
                position:absolute;
                left:12mm;
                top:63mm;
            }
            .date2-3{
                position:absolute;
                left:85mm;
                top:190mm;
            }
            .content1{
                position: absolute;
                left: 5mm;
                top:87mm;
            }
            .content2{
                position: absolute;
                left: 5mm;
                top:90mm;
            }
            .property1 {
                position:absolute;
                left:40mm;
                top:100mm;
            }
            .property2 {
                position:absolute;
                left:40mm;
                top:106mm;
            }
            .property3 {
                position:absolute;
                left:40mm;
                top:112mm;
            }
            .property4 {
                position:absolute;
                left:40mm;
                top:118mm;
            }
            .property5 {
                position:absolute;
                left:80mm;
                top:100mm;
            }
            .property6 {
                position:absolute;
                left:80mm;
                top:106mm;
            }
            .property7 {
                position:absolute;
                left:80mm;
                top:112mm;
            }
            .property8 {
                position:absolute;
                left:80mm;
                top:118mm;
            }
            .rev1 {
                position: absolute;
                top:180mm;
                width: 99mm;
                text-align: center;
            }
            .rev2 {
                position: absolute;
                top:182mm;
                width: 99mm;
                text-align: center;
            }

        </style>
    </head>
        

    <body>

        @foreach($barcodes as $bar)
            @for ($i = 1; $i < $bar->quantity+1; $i++)

                <div class="main">
                    <p class="title1">{{ $bar->title }}</p>
                    <p class="title2">{{ $bar->title }}</p>
                    <p class="dimension1">{{ $bar->dimensions }}</p>
                    <p class="dimension2">{{ $bar->dimensions }}</p>
                    <p class="dimension3">{{ $bar->dimensions }}</p>
                    <p class="dimension4">{{ $bar->dimensions }}</p>
                    <p class="dimension5">{{ $bar->dimensions }}</p>
                    @if($bar->barcode)
                        <div class="barcode1">{!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 1.2) !!}</div>
                        <p class="barcode1-text">{{ $bar->barcode }}</p>
                        <div class="barcode2">{!! DNS1D::getBarcodeHTML($bar->barcode, 'C128', 1.2) !!}</div>
                        <p class="barcode2-text">{{ $bar->barcode }}</p>
                    @endif
                    <p class="lot1">{{ $bar->lot }}</p>
                    <p class="lot2">{{ $bar->lot }}</p>
                    <p class="lot3">{{ $bar->lot }}</p>
                    <p class="ref1">{{ $bar->ref }}</p>
                    <p class="ref2">{{ $bar->ref }}</p>
                    <p class="date1-1">{{ $bar->date1 }}</p>
                    <p class="date1-2">{{ $bar->date1 }}</p>
                    <p class="date1-3">{{ $bar->date1 }}</p>
                    <p class="date2-1">{{ $bar->date2 }}</p>
                    <p class="date2-2">{{ $bar->date2 }}</p>
                    <p class="date2-3">{{ $bar->date2 }}</p>
                    <p class="content1">{{ $bar->content1 }}</p>
                    <p class="content2">{{ $bar->content2 }}</p>
                    <p class="property1">{{ $bar->property1 }}</p>
                    <p class="property2">{{ $bar->property2 }}</p>
                    <p class="property3">{{ $bar->property3 }}</p>
                    <p class="property4">{{ $bar->property4 }}</p>
                    <p class="property5">{{ $bar->property5 }}</p>
                    <p class="property6">{{ $bar->property6 }}</p>
                    <p class="property7">{{ $bar->property7 }}</p>
                    <p class="property8">{{ $bar->property8 }}</p>
                    <p class="rev1">{{ $bar->rev1 }}</p>
                    <p class="rev2">{{ $bar->rev2 }}</p>
                </div>

            @endfor
        @endforeach

    </body>

</html>
