<?php

namespace App\Exports\Erp;

use App\Models\Erp\ErpApproval;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class WarehouseTransferExport implements 
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

    protected $erpApprovalIdArray;

    function __construct($erpApprovalIdArray_c) {
        $this->erpApprovalIdArray = $erpApprovalIdArray_c;
    }

    public function collection()
    {
        $pureData = ErpApproval::with('getSender','getIncreasedWarehouse','getDwindlingWarehouse','getItem')->whereIn('id',$this->erpApprovalIdArray)->orderByDesc('created_at')->get();
        
        $excelData = collect();
        foreach ($pureData as $row) {
            $excelData->push([
                'itemName' => isset($row->getItem) ? $row->getItem->name:'Ürün Sistemden Kaldırılmıştır',
                'amount' => $row->amount,
                'itemToUnit' => isset($row->getItem) ? $row->getItem->itemToUnit->content:'Ürün Sistemden Kaldırılmıştır',
                'content' => $row->content,
                'increased_warehouse_id' => $row->getIncreasedWarehouse?->name,
                'dwindling_warehouse_id' => $row->getDwindlingWarehouse?->name,
                'lot' => $row->lot_no,
                'sender_user' => isset($row->getSender) ? $row->getSender->name : 'Kullanıcı İnaktif',
                'answer_user' => isset($row->getSender) ? $row->getSender->name : 'Kullanıcı İnaktif',
                'date' => $row->updated_at->format('Y-m-d H:i:s'),
                'id' => $row->id,
                'itemID' => isset($row->getItem) ? $row->getItem->id:'-',
            ]);
        }
        return $excelData;
    }

    public function headings(): array
    {
        return [
            'Ürün Adı',
            'Miktar',
            'Birim',
            'Açıklama',
            'Artan Depo',
            'Azalan Depo',
            'LOT NO',
            'Tablebi Oluşturan',
            'Tablebi Yanıtlayan',
            'Tarih',
            'ID',
            'Ürün ID',
        ];
    }

    
    public function properties(): array
    {
        // $item = ErpItem::find($this->itemId);
        
        return [
            'creator'   => env('APP_NAME'),
            'lastModifiedBy' => env('APP_NAME'),
            'title'     => 'Stok Transferleri',
            'subject'     => 'Stok Transferleri',
            'company'   => 'CustomERP',
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
            'I1' => ['font' => ['bold' => TRUE ]],
            'J1' => ['font' => ['bold' => TRUE ]],
            'K1' => ['font' => ['bold' => TRUE ]],
            'L1' => ['font' => ['bold' => TRUE ]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
        ];
    }

}
