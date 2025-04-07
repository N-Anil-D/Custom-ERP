<?php

namespace App\Exports\Erp;

use App\Models\Erp\Warehouse\ErpWarehouse;
use App\Models\Erp\Item\{ErpItem, ErpUnit};
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// use Illuminate\Contracts\Support\Responsable;
// use Illuminate\Support\Facades\Schema;

class SystemDataExport implements 
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

    protected $systemDataType;

    function __construct($type) {

        // dd(Schema::getColumnListing('erp_items'));
            $this->systemDataType = $type;
    }


    public function collection()
    {
        $data = collect();

        if($this->systemDataType === ErpWarehouse::class){
            $pureData = ErpWarehouse::get();
            foreach($pureData as $row){
                $data->push([
                    $row->id,
                    $row->code,
                    $row->name,
                    $row->content,
                    $row->created_at,
                    $row->updated_at,
                ]);
            }
        }elseif($this->systemDataType === ErpUnit::class){
            $pureData = ErpUnit::get();
            foreach($pureData as $row){
                $data->push([
                    $row->id,
                    $row->code,
                    $row->content,
                    $row->created_at,
                    $row->updated_at,
                ]);
            }

        }elseif($this->systemDataType === ErpItem::class){
            $pureData = ErpItem::where('deleted_at',null)->get();
            foreach($pureData as $row){
                $data->push([
                    $row->id,
                    $row->code,
                    $row->name,
                    $row->itemToUnit->content,
                    $row->getType(),
                    $row->stocks->sum('amount'),
                    $row->barcode,
                    $row->itemToVariety?->name,
                    $row->created_at,
                    $row->updated_at,
                ]);
            }

        }else{
        return collect(['Bilinmeyen bir hata oluştu.']);
            
        }
        return $data;
    }

    public function properties(): array
    {
        $headingInfo = [];
        if($this->systemDataType === ErpWarehouse::class){
            $headingInfo = [
                'title'=>trans('site.excel.FileDetails.warehouse'),
                'subject'=>trans('site.excel.FileDetails.warehouse'),
                'description'=>trans('site.excel.FileDetails.warehouse'),
            ];

        }elseif($this->systemDataType === ErpUnit::class){
            $headingInfo = [
                'title'=>trans('site.excel.FileDetails.unit'),
                'subject'=>trans('site.excel.FileDetails.unit'),
                'description'=>trans('site.excel.FileDetails.unit'),
            ];
    
        }elseif($this->systemDataType === ErpItem::class){
            $headingInfo = [
                'title'=>trans('site.excel.FileDetails.item'),
                'subject'=>trans('site.excel.FileDetails.item'),
                'description'=>trans('site.excel.FileDetails.item'),
            ];
        }else{
            $headingInfo = [
                'title'=>'-',
                'subject'=>'-',
                'description'=>'-',
            ];
        }

        return [
            'creator'   => env('APP_NAME'),
            'lastModifiedBy' => env('APP_NAME'),
            'title'     => $headingInfo['title'],
            'subject'   => $headingInfo['subject'],
            'description'    => $headingInfo['description'],
            'keywords'       => 'Mevcut Ürünler',
            'company'   => 'CustomERP',
        ];
    }
    
    public function headings(): array
    {
        if($this->systemDataType === ErpWarehouse::class){
            return [
                'ID',
                'Depo kodu',
                'Depo adı',
                'Açıklama',
                'Kayıt tarihi',
                'Son değişiklik tarihi',
            ];

        }elseif($this->systemDataType === ErpUnit::class){
            return [
                'ID',
                'Birim kodu',
                'Birim açıklaması',
                'Kayıt tarihi',
                'Son değişiklik tarihi',
            ];
    
        }elseif($this->systemDataType === ErpItem::class){
            return [
                'ID',
                'Ürün kodu',
                'Ürün adı',
                'Birim',
                'Ürün tipi',
                'Toplam stok',
                'Barkod',
                'Ürün Çeşidi',
                'Kayıt tarihi',
                'Son değişiklik tarihi',
            ];

        }else{
            return [];
        }
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

    public function columnFormats(): array
    {
        if($this->systemDataType === ErpItem::class){
            return [
                'A' => NumberFormat::FORMAT_NUMBER,
                'B' => NumberFormat::FORMAT_NUMBER,
                'F' => NumberFormat::FORMAT_NUMBER,
                'G' => NumberFormat::FORMAT_NUMBER,
            ];
        }else{
            return [
                'A' => NumberFormat::FORMAT_NUMBER,
            ];
        }
    }

}
