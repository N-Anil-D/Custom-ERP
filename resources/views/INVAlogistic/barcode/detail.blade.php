<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CustomERP - Barcode Document</title>
</head>
<style>
    html {
        margin: 0px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
    }
    
    #lot {
        top:0;
        left: 0;
        position: absolute;        
    }

    /* AŞ için */
    .page-break {
        page-break-after: always;
    }

    .as-content {
        font-size: 5pt;
        position: absolute;
        top: 8mm;
        left: 29mm;
        right: 5mm;
        font-weight: bold;
    }
    
    .as-amount {
        font-size: 6pt;
        position: absolute;
        top: 10.5mm;
        left: 14mm;
        font-weight: bold;
    }
    
    .as-lot {
        font-size: 5pt;
        position: absolute;
        top: 17.8mm;
        left: 13mm;
        font-weight: bold;
    }
    
    .as-ref {
        font-size: 5pt;
        position: absolute;
        top: 20.2mm;
        left: 13mm;
        font-weight: bold;
    }
    
    .as-lotdate {
        font-size: 5pt;
        position: absolute;
        top: 22.5mm;
        left: 13mm;
        font-weight: bold;
    }
    
    .as-name {
        font-size: 5pt;
        position: absolute;
        top: 22mm;
        left: 27mm;
        right: 5mm;
        font-weight: bold;
    }

    .as-barcode {
        position: absolute;
        top: 26mm;
        left: 38mm;
    }

    .as-barcode-text {
        position: absolute;
        top: 33mm;
        left: 45mm;
    }



</style>

<body>

    @php
        $sourceArr  = array('Ş', 'ş', 'Ç', 'ç', 'Ğ', 'ğ', 'İ', 'ı');
        $descArr    = array('S', 's', 'C', 'c', 'G', 'g', 'I', 'i');
    @endphp
    
    @foreach($barcodes as $bar)
    
        @php

            $name = str_replace($sourceArr, $descArr, $bar->name);
            $content = str_replace($sourceArr, $descArr, $bar->content);
        
        @endphp

        @for ($i = 0; $i < $bar->amount*3; $i++)
            @if($type == 'LTD')
                <div style="width: 69.9mm; height: 49.9mm;">
                    <div style="height: 15mm;"></div>
                    <div style="height: 6mm;">
                        <table>
                            <tr>
                                <td style="width: 10mm">
                                </td>
                                <td>
                                    <strong>
                                    {{ $bar->lot }}
                                    </strong>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <strong>
                                    {{ $bar->lotDate }}
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="height: 12mm;">
                        <table>
                            <tr>
                                <td style="width: 10mm">
                                </td>
                                <td>
                                    <strong>
                                    {{ $bar->ref }}
                                    </strong>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: center">
                                    @if($bar->barcode)
                                    {!! DNS1D::getBarcodeHTML($bar->barcode, 'EAN13', 1.2, 20) !!}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><strong>1</strong></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: center">
                                    <strong>
                                    {{ $bar->barcode }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="3" style="font-size: 7px">{{ $name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div style="height: 4mm">

                    </div>
                    <div>
                        <table>
                            <tr>
                                <td style="width: 3mm"></td>
                                <td>
                                    <strong><p style='font-size: 8px'>{{ Str::limit($content,75) }}</p></strong>
                                </td>
                                <td style="width: 10mm"></td>
                            </tr>
                            
                        </table>
                    </div>

                </div>

            @else
                <div>
                    <div class="as-content">{{ $content }}</div>
                    <div class="as-amount">1</div>
                    <div class="as-lot">{{ $bar->lot }}</div>
                    <div class="as-ref">{{ $bar->ref }}</div>
                    <div class="as-lotdate">{{ $bar->lotDate }}</div>
                    <div class="as-name">{{ $name }}</div>
                    <div class="as-barcode">@if($bar->barcode){!! DNS1D::getBarcodeHTML($bar->barcode, 'EAN13', 1.2, 25) !!}@endif</div>
                    <div class="as-barcode-text">{{ $bar->barcode }}</div>
                </div>

                @if(!(($i+1 == $bar->amount*3) && ($loop->last)))
                    <div class="page-break"></div>
                @endif
            @endif
        @endfor

    @endforeach




</body>

</html>
