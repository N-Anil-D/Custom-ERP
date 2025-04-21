<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CustomERP - Malzeme Tanım Etiketi</title>
</head>
<style>
    html {
        margin: 0px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
    }
    .title_in_row{
        border-right: 1px solid #000000;
        width: 27mm;
        height: 6.2mm;
        font-weight: 700;
        font-size: 1.7mm;
        padding-left: 1mm;
        padding-top: 0.2mm;
        padding-bottom: 0.2mm;
        /* line-height: 1mm; */
    }

    .second_title_p{
        opacity: 0.7;
    }

    .info_in_row{
        font-weight: 400;
        width: 55mm;
        height: 6.2mm;
        padding-left: 1mm;
        font-size: 2.7mm;
    }
    table {
        border-collapse: collapse;
        border-color:#000000;
    }

    tr {
        border-bottom: 1pt solid black;
        border-top: 1pt solid black;
        border-right: 1px solid #000000;
        border-left: 1px solid #000000;
        width: 91mm;
    }

    p {
        line-height: 1mm
    }

    .logo_row{
        display: flex;
    }
    .main_div{
        margin-left: 6mm;
        margin-right: 4mm;
        margin-top: 2mm;
        margin-bottom: 2mm;
    }
</style>

<body>
        @php
            $sourceArr  = array('Ş', 'ş', 'Ç', 'ç', 'Ğ', 'ğ', 'İ', 'ı');
            $descArr    = array('S', 's', 'C', 'c', 'G', 'g', 'I', 'i');
        @endphp
        @foreach ($itemDefinitions as $itemDefinition)
            @php
                $name = str_replace($sourceArr, $descArr, $itemDefinition->name);
                $company_name = str_replace($sourceArr, $descArr, $itemDefinition->company_name);
                $controller = str_replace($sourceArr, $descArr, $itemDefinition->controller);
                $suitability = str_replace($sourceArr, $descArr, $itemDefinition->suitability);
            @endphp
            <div class="main_div">
                <table style="margin-top:4mm;">
                    <thead>
                    <tr>
                        <th style="height: 14mm; padding-left:2mm;" align="left">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAL0AAAAbCAYAAAApko7DAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDYuMC1jMDAyIDc5LjE2NDQ2MCwgMjAyMC8wNS8xMi0xNjowNDoxNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjIgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkNGNUI2QjE5MUQ5QzExRUZBRjhCQkNFODE2MDlERkIxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkNGNUI2QjFBMUQ5QzExRUZBRjhCQkNFODE2MDlERkIxIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6Q0Y1QjZCMTcxRDlDMTFFRkFGOEJCQ0U4MTYwOURGQjEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Q0Y1QjZCMTgxRDlDMTFFRkFGOEJCQ0U4MTYwOURGQjEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4C/tugAAAIAElEQVR42uRcaYwU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"
                                    alt="CustomERP Logo"
                                    style="width: 28mm;"
                                >
                        </th>
                        <th style="height: 14mm;" align="right">
                                <p><span style="font-size:3mm;">MALZEME TANIM ETIKETI</span></p>
                                <p class="second_title_p"><span style="font-size:3mm;">MATERIAL IDENTIFICATION LABEL</span></p>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="title_in_row" style="height: 10mm">
                                <p style="font-size: 2mm;">MALZEME ADI</p>
                                <p class="second_title_p" style="font-size: 2mm;">MATERIAL NAME</p>
                            </td>
                            <td class="info_in_row" style="height: 10mm">{{ $name }}</td>
                        </tr>
                        <tr>
                            <td class="title_in_row">
                                <p>DEPO GIRIS TARIHI</p>
                                <p class="second_title_p">WAREHOUSE ENTRY DATE</p>
                            </td>
                            <td class="info_in_row">{{ $itemDefinition->entry_date }}</td>
                        </tr>
                        <tr>
                            <td class="title_in_row">
                                <p>IRSALIYE NO</p>
                                <p class="second_title_p">WAYBILL NUMBER</p>
                            </td>
                            <td class="info_in_row">{{ $itemDefinition->irsaliye }}</td>
                        </tr>
                        <tr>
                            <td class="title_in_row">
                                <p>TEDARIKCI FIRMA ADI</p>
                                <p class="second_title_p">SUPPLIER COMPANY NAME</p>
                            </td>
                            <td class="info_in_row">{{ $company_name }}</td>
                        </tr>
                        <tr>
                            <td class="title_in_row">
                                <p>MIKTAR</p>
                                <p class="second_title_p">AMOUNT</p>
                            </td>
                            <td class="info_in_row">{{ $itemDefinition->amount }}</td>
                        </tr>
                        <tr>
                            <td class="title_in_row">
                                <p>LOT NUMARASI</p>
                                <p class="second_title_p">LOT NUMBER</p>
                            </td>
                            <td class="info_in_row">{{ $itemDefinition->lot }}</td>
                        </tr>
                        <tr>
                            <td class="title_in_row">
                                <p>SON KULLANMA TARIHI</p>
                                <p class="second_title_p">EXPIRATION DATE</p>
                            </td>
                            <td class="info_in_row">{{ $itemDefinition->last_use_date }}</td>
                        </tr>
                        <tr>
                            <td class="title_in_row">
                                <p>KONTROLU YAPAN (PARAF)</p>
                                <p class="second_title_p">CONTROLLED BY (INITIAL)</p>
                            </td>
                            <td class="info_in_row">{{ $controller }}</td>
                        </tr>
                        <tr>
                            <td class="title_in_row">
                                <p>UYGUNLUK DURUMU</p>
                                <p class="second_title_p">AVAILABILITY</p>
                            </td>
                            <td class="info_in_row">{{ $suitability }}</td>
                        </tr>
                    </tbody>
                </table>
                <p style="padding-left: 2px;padding-top:2mm;margin-top:0mm;font-size: 1.8mm;">ET.08.01 rev.01 / Yayin Tarihi Publication Date: 27.12.2021 / Revizyon Tarihi Revision Date: 15.04.2024</p>
            </div>

        @endforeach
</body>

</html>
