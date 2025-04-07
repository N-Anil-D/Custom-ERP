<?php

namespace App\Exports\Erp;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
// use Illuminate\Contracts\Support\Responsable;
// use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Excel;

class MyProductListExport implements 
FromCollection,
WithHeadings,
WithCustomStartCell,
WithProperties,
ShouldAutoSize,
WithStyles,
WithColumnFormatting

{
    /**
    * @return \Illuminate\Support\Collection
    */



    public function collection()
    {
        $userWarehouseList = Auth::user()->warehouses;
        $data = collect();
        foreach ($userWarehouseList as $rowLevel_1) {
            // foreach ($rowLevel_1->wherehouseItems->where('amount','>',0) as $rowLevel_2) {
            foreach ($rowLevel_1->wherehouseItems as $rowLevel_2) {
                if($rowLevel_2->item){
                    $data->push([
                        'ID' => ($rowLevel_2->item) ? $rowLevel_2->item->id : '',
                        'code' => ($rowLevel_2->item) ? $rowLevel_2->item->code : '',
                        'name' => ($rowLevel_2->item) ? $rowLevel_2->item->name : '',
                        'warehouseName' => $rowLevel_2->warehouse->name,
                        'amount' => $rowLevel_2->amount,
                        'itemToUnit' => ($rowLevel_2->item) ? $rowLevel_2->item->itemToUnit->content : '',
                        'type' => ($rowLevel_2->item) ? $rowLevel_2->item->getType() : '',
                        'itemVariety' => ($rowLevel_2->item) ? $rowLevel_2->item->itemToVariety?->name : '',
                    ]);
                }
            }
        }
        return $data;
    }

    public function properties(): array
    {
        
        return [
            'creator'           => env('APP_NAME'),
            'lastModifiedBy'    => env('APP_NAME'),
            'title'             => Auth::user()->name.' | Mevcut Ürünlerim',
            'subject'           => Auth::user()->name.' | Mevcut Ürünlerim',
            'description'       => Auth::user()->name.' | Mevcut Ürünlerim',
            'keywords'          => 'Mevcut Ürünlerim',
            'company'           => 'CustomERP',
        ];
    }
    
    public function headings(): array
    {
        return [
            'Ürün ID',
            'Ürün kodu',
            'Ürün adı',
            'Stok yeri',
            'Stok miktarı',
            'Birimi',
            'Ürün tipi',
            'Ürün Çeşidi',
        ];
    }

    public function startCell(): string
    {
        return "A1";
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            'A1' => ['font' => ['bold' => TRUE ]],
            'B1' => ['font' => ['bold' => TRUE ]],
            'C1' => ['font' => ['bold' => TRUE ]],
            'D1' => ['font' => ['bold' => TRUE ]],
            'E1' => ['font' => ['bold' => TRUE ]],
            'F1' => ['font' => ['bold' => TRUE ]],
            'G1' => ['font' => ['bold' => TRUE ]],
            'H1' => ['font' => ['bold' => TRUE ]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
        ];
    }

}
