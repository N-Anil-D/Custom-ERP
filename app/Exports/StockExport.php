<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\LucaStockList;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockExport implements 
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithProperties,
    ShouldAutoSize,
    WithStyles
{
    
    public function collection()
    {
        $data = DB::table('luca_stocklist')
                ->select(
                        'luca_stocklist.kartKodu as "Kart kodu"',
                        'luca_stocklist.kartAdi as "Kart adı"',
                        'luca_stocklist.aktif as "Aktif ?"',
                        'luca_stocktype.name as "Stok tipi"',
                        'luca_stocklist.tlBorc as "Borç TL"',
                        'luca_stocklist.tlAlacak as "Alacak TL"',
                        'luca_stocklist.borcMiktar as "Borç Miktar"',
                        'luca_stocklist.alacakMiktar as "Alacak Miktar"',
                        'luca_stocklist.toplam as "Bakiye"'
                    )
                ->leftJoin('luca_stocktype','luca_stocktype.typeId','=','luca_stocklist.stokTipi')
                ->get();
        
        return $data;
    }
    
    public function startCell(): string
    {
        return "A1";
    }
    
    public function properties(): array
    {
        return [
            'creator'   => 'ERPportal | w01ki3',
            'title'     => 'CustomERP Stok Listesi'
        ];
    }
    
    public function headings(): array
    {
        return [
            'Kart kodu',
            'Kart adı',
            'Aktif ?',
            'Stok tipi',
            'Borç TL',
            'Alacak TL',
            'Borç Miktar',
            'Alacak Miktar',
            'Bakiye'
        ];
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
        ];
    }
    
    
    
}
