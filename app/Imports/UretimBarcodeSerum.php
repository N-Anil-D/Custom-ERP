<?php

namespace App\Imports;

use Illuminate\Support\Collection;
//use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\UretimSerumBrcLine;


class UretimBarcodeSerum implements ToModel,WithHeadingRow
{
    use Importable;
    /**
    * @param Collection $collection
    */

    public $brcListId;

    public function __construct($brcListId) {
        
        $this->brcListId = $brcListId;
    }
    
    public function model(array $row)
    {
        
        UretimSerumBrcLine::create([
            'listId' => $this->brcListId,
            'title'=> $row['title'],
            'subtitle'=> $row['subtitle'],
            'size'=> $row['size'],
            'color'=> $row['color'],
            'quantity' => $row['quantity'],
            'ref' => $row['ref'],
            'lot' => $row['lot'],
            'date1' => $row['date1'],
            'barcode'=> $row['barcode'],
        ]);
        
    }
}
