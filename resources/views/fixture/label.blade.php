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

        <title>CustomERP - Demirbaş barkodları</title>
        
        <style>

            html {
                margin: 0px;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 7pt;
                font-weight: bold;
            }

            .page-break {
                page-break-after: always;
            }

            .barcode{
                position: absolute;
                left: 1.2mm;
                bottom: 6mm;
            }
            
            .barcodetext{
                position: absolute;
                font-size: 9pt;
                bottom: 1mm;
                width: 100%;
            }
            
            .content{
                text-align: center;
            }
            
            .logo{
                text-align: center;
            }

            .table{
                position: absolute;
                left: 1mm;
                font-size: 8pt;
            }

        </style>
    </head>
        

    <body>

        @php
            $sourceArr  = array('Ş', 'ş', 'Ç', 'ç', 'Ğ', 'ğ', 'İ', 'ı');
            $descArr    = array('S', 's', 'C', 'c', 'G', 'g', 'I', 'i');
        @endphp

        @foreach($labels as $label)

            @php 
                $location = str_replace($sourceArr, $descArr, $label->location);
                $section  = str_replace($sourceArr, $descArr, $label->section);
                $roomCode = str_replace($sourceArr, $descArr, $label->roomCode);
                $itemName = str_replace($sourceArr, $descArr, $label->item_name);
                $brand    = str_replace($sourceArr, $descArr, $label->brand);
            @endphp

            @for ($i = 1; $i < $label->amount+1; $i++)
                @php $barcode = str_replace('.', '-', $label->code).'-'.Str::padleft($i, 4, 0);@endphp
                <div class="logo">
                    <img src="img/logo_1cm-01.png" style="height: 12mm;"/>
                </div>
                <div class="table">
                    <table>
                        <tr>
                            <td>Lokasyon</td>
                            <td>:</td>
                            <td>{{ $location }}</td>
                        </tr>
                        <tr>
                            <td>Bölüm/Kat</td>
                            <td>:</td>
                            <td>{{ $section.'/'. $label->floor }}</td>
                        </tr>
                        <tr>
                            <td>Ürün/Marka</td>
                            <td>:</td>
                            <td>{{ $itemName }}{{ ($label->brand) ? '/'.$brand : '' }}</td>
                        </tr>
                    </table>
                </div>
                @if($label->code)
                    <div class="barcode">{!! DNS1D::getBarcodeHTML($barcode, 'C128', 0.88, 50) !!}</div>
                    <div class="barcodetext">
                        <div class="content">
                            {{ $barcode }}
                        </div>
                    </div>
                @endif
                
                @if($labels->count() == 1)
                    @if($i < $label->amount)
                        <div class="page-break"></div>
                    @endif
                @else
                    @if(!$loop->last)
                        <div class="page-break"></div>
                    @endif
                @endif
            @endfor
        @endforeach

    </body>

</html>
