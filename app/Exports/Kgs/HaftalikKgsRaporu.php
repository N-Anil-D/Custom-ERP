<?php

namespace App\Exports\Kgs;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Carbon\{Carbon,CarbonInterval};

use Maatwebsite\Excel\Excel;

use App\Models\Kgs\KgsPuantaj;

class HaftalikKgsRaporu implements 
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithProperties,
    ShouldAutoSize,
    WithStyles,
    Responsable,
    WithColumnFormatting

{
    use Exportable;
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function collection()
    {
        $kgsPuantaj = KgsPuantaj::get();
        $output = collect();
        foreach ($kgsPuantaj as $value) {
            $output->push([$value->kgs_id,$value->puantajToName->name,CarbonInterval::minutes($value->calisma_dakika)->cascade()->totalHours,'saat']);
        }
        return $output;
    }

    public function properties(): array
    {
        return [
            'creator'   => 'NAD',
            'title'     => 'Haftalık Puantaj',
            'subject'   => 'KGS kullanıcılarının haftalık mesai hesaplaması',
            'company'   => 'CustomERP',
        ];
    }

    public function headings(): array
    {
        return [
            'KGS_ID',
            'Name',
            'Hesaplanan Süre',
            'Süre',
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
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    private $writerType = \Maatwebsite\Excel\Excel::XLSX;
    private $headers = [
        'Content-Type' => 'application/vnd.ms-excel',
    ];
}
