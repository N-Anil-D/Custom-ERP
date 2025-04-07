<?php

namespace App\Exports\Erp;

use App\Models\Erp\Warehouse\ErpStockMovement;
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
use Carbon\Carbon;

class StockMovementsExport implements 
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithProperties,
    ShouldAutoSize,
    WithStyles,
    WithColumnFormatting
    // Responsable

{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $itemId;
    protected $monthLimit;

    function __construct($id,$month) {
        $this->itemId = $id;
        $this->monthLimit = $month;
    }

    public function collection()
    {
        $pureData = ErpStockMovement::with('getDwindlingWarehouse', 'getIncreasedWarehouse', 'item', 'getSender', 'getApproval')
        ->where('item_id', $this->itemId)
        ->whereBetween('created_at', [Carbon::now()->subMonth($this->monthLimit), Carbon::now()])
        ->orderByDesc('created_at')
        ->get();
        $data = collect();
        foreach($pureData as $row){
            if($row->item){
                $data->push([
                    $row->getType(),
                    $row->getDwindlingWarehouse ? $row->getDwindlingWarehouse->code : '',
                    $row->getIncreasedWarehouse ? $row->getIncreasedWarehouse->code : '',
                    $row->amount,
                    $row->item->itemToUnit->code,
                    $row->getSender->name,
                    $row->getApproval->name,
                    $row->created_at->format('Y-m-d H:i')
                ]);
            }
        }
        return $data;
    }

    public function properties(): array
    {
        $item = ErpStockMovement::where('item_id', $this->itemId)->first()->item;

        return [
            'creator'   => env('APP_NAME'),
            'lastModifiedBy' => env('APP_NAME'),
            'title'     => $item->code.' - Stok Hareketleri',
            'subject'   => $item->name.' Stok Hareketleri',
            'description'    => $item->getType().' Stok Hareketleri',
            'keywords'       => $item->getType().','.$item->itemToUnit->content.',',
            'company'   => 'CustomERP',
        ];
    }
    
    public function headings(): array
    {
        return [
            'Hareket tipi',
            'Azalan depo',
            'Artan depo',
            'Miktar',
            'Birim',
            'Talep sahibi',
            'Onaylayan',
            'İşlem tarihi',
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
            'D' => NumberFormat::FORMAT_NUMBER,
        ];
    }

}
