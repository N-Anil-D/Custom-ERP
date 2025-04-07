<?php

namespace App\Exports\Erp;

use App\Models\Erp\Item\ErpItem;
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
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Excel;

class ItemWarehouseDispersionExport implements 
FromCollection,
WithHeadings,
WithCustomStartCell,
WithProperties,
ShouldAutoSize,
WithStyles,
WithColumnFormatting
// Responsable

{
    protected $itemId;

    function __construct($id) {
            $this->itemId = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $pureData = ErpItem::with('stocks')->find($this->itemId);
        $data = collect();
        foreach($pureData->stocks as $row){
            $data->push([
                $row->warehouse->code,
                $row->warehouse->name,
                $row->amount,
                $pureData->itemToUnit->code
            ]);
        }
        return $data;
    }

    public function properties(): array
    {
        $item = ErpItem::find($this->itemId);
        
        return [
            'creator'   => env('APP_NAME'),
            'lastModifiedBy' => env('APP_NAME'),
            'title'     => $item->code.' Stok Dağılımı',
            'subject'   => $item->name.' Stok Dağılımı',
            'description'    => $item->getType().' Stok Dağılımı',
            'keywords'       => $item->getType().','.$item->itemToUnit->content.',',
            'company'   => 'CustomERP',
        ];
    }
    
    public function headings(): array
    {
        return [
            'Depo kodu',
            'Depo adı',
            'Miktar',
            'Birim',
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
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
        ];
    }

}
