<?php

namespace App\Imports;

use Illuminate\Support\Collection;
//use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\UretimTubeBrcLine;


class UretimBarcodeTube implements ToModel,WithHeadingRow
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
        
        UretimTubeBrcLine::create([
            'listId' => $this->brcListId,
            'name'=> $row['name'],
            'quantity' => $row['quantity'],
            'lot'=> $row['lot'],
            'ref'=> $row['ref'],
            'barcode'=> $row['barcode'],
            'date1' => $row['date1'],
        ]);
        
    }
}
