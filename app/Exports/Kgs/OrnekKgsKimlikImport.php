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
use Maatwebsite\Excel\Excel;

class OrnekKgsKimlikImport implements 
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithProperties,
    ShouldAutoSize,
    WithStyles,
    Responsable
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return collect();
    }

    public function properties(): array
    {
        return [
            'creator'   => 'Nuri Anıl Demirbaş',
            'title'     => 'Web Dev NAD | KGS User List',
            'subject'   => 'Test',
            'company'   => 'NAD Test Company',
        ];
    }

    public function headings(): array
    {
        return [
            'kgs_id',
            'name',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1' => ['font' => ['bold' => TRUE ]],
            'B1' => ['font' => ['bold' => TRUE ]],
            'C1' => ['font' => ['bold' => TRUE ]],
        ];
    }

    public function startCell(): string
    {
        return "A1";
    }

    /* It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'KgsUsersInsertSchema.xlsx';
    
    /**
    * Optional Writer Type
    */
    private $writerType = \Maatwebsite\Excel\Excel::XLSX;
    // private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'application/vnd.ms-excel',
    ];


}
