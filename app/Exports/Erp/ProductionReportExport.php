<?php

namespace App\Exports\Erp;

use App\Models\Erp\Warehouse\ErpProduction;
use App\Models\User;
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
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Excel;
use Carbon\Carbon;

class ProductionReportExport implements 
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithProperties,
    ShouldAutoSize,
    WithStyles
    // WithColumnFormatting
    // Responsable
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $productionIdArray;

    function __construct($productionIdArray_c) {
        $this->productionIdArray = $productionIdArray_c;
    }

    public function collection()
    {

        $pureData = ErpProduction::with('item','warehouse','user','contents')->whereIn('id',$this->productionIdArray)->orderBy('warehouse_id')->get();
        
        $excelData = collect();
        foreach ($pureData as $rowLevel_1) {
            $excelData->push([
                'type' => 'ÜRETİLEN',
                'itemCode' => isset($rowLevel_1->item) ? $rowLevel_1->item->code:'Ürün Sistemden Kaldırılmıştır',
                'itemName' => isset($rowLevel_1->item) ? $rowLevel_1->item->name:'Ürün Sistemden Kaldırılmıştır',
                'productionName' => $rowLevel_1->name,
                'productionRoom' => $rowLevel_1->warehouse->name,
                'amount' => $rowLevel_1->amount,
                'wastage' => null,
                'itemToUnit' => isset($rowLevel_1->item) ? $rowLevel_1->item->itemToUnit->content:'Ürün Sistemden Kaldırılmıştır',
                'creator' => User::find($rowLevel_1->user_id)->name,
                'date' => $rowLevel_1->updated_at->format('Y-m-d H:i:s'),
                'itemId' => isset($rowLevel_1->item) ? $rowLevel_1->item->id:"-",
                'productionId' => $rowLevel_1->id,
            ]);

            foreach ($rowLevel_1->contents as $rowLevel_2) {
                $excelData->push([
                    'type' => 'KULLANILAN',
                    'itemCode' => isset($rowLevel_2->item->code) ? $rowLevel_2->item->code:'Ürün Sistemden Kaldırılmıştır',
                    'itemName' => isset($rowLevel_2->item->name) ? $rowLevel_2->item->name:'Ürün Sistemden Kaldırılmıştır',
                    'productionName' => null,
                    'productionRoom' => null,
                    'amount' => $rowLevel_2->amount,
                    'wastage' => $rowLevel_2->wastage,
                    'itemToUnit' => isset($rowLevel_2->item->code) ? $rowLevel_2->item->itemToUnit->content:'Ürün Sistemden Kaldırılmıştır',
                    'creator' => null,
                    'date' => $rowLevel_1->updated_at->format('Y-m-d H:i:s'),
                    'itemId' => isset($rowLevel_2->item) ? $rowLevel_1->item->id:"-",
                    'productionId' => null,
                ]);
            }
        }
        return $excelData;
    }



    public function properties(): array
    {
        return [
            'creator'   => env('APP_NAME'),
            'lastModifiedBy' => env('APP_NAME'),
            'title'     => Auth::user()->name.' - Üretimlerim',
            'subject'   => Auth::user()->name.' - Kullanıcısının kendisinin başlatıp bitirdiği üretimler',
            'description'    => Auth::user()->name.' - Kullanıcısının kendisinin başlatıp bitirdiği üretimler',
            'keywords'       => 'Üretim Raporu - '.Auth::user()->name,
            'company'   => 'CustomERP',
        ];
    }
    
    public function headings(): array
    {
        return [
            'Tip',
            'Ürün Kodu',
            'Ürün Adı',
            'Üretime Verilen Ad',
            'Üretimin Yapıldığı Oda',
            'Miktar',
            'Fire',
            'Birim',
            'Üretimi Yapan',
            'Tarih',
            'Ürün ID',
            'Üretim ID',
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
        ];
    }

}
