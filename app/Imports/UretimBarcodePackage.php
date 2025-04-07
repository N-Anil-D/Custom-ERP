<?php

namespace App\Imports;

use Illuminate\Support\Collection;
//use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\UretimPackageBrcLine;
use Illuminate\Support\Str;

class UretimBarcodePackage implements ToModel,WithHeadingRow
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
       
       
        UretimPackageBrcLine::create([

            'listId' => $this->brcListId,
            'title'=> $this->engLang($row['title']),
            'dimensions'=> $row['dimensions'],
            'barcode'=> $row['barcode'],
            'lot'=> $row['lot'],
            'ref'=> $row['ref'],
            'date1'=> $row['date1'],
            'date2'=> $row['date2'],
            'content1'=> $this->engLang($row['content1']),
            'content2'=> $this->engLang($row['content2']),
            'property1'=> $row['property1'],
            'property2'=> $row['property2'],
            'property3'=> $row['property3'],
            'property4'=> $row['property4'],
            'property5'=> $row['property5'],
            'property6'=> $row['property6'],
            'property7'=> $row['property7'],
            'property8'=> $row['property8'],
            'rev1'=> $row['rev1'],
            'rev2'=> $row['rev2'],
            'quantity' => $row['quantity']

        ]);

        
    }

    public function engLang($text){

        $text = Str::upper($text);
        $text = Str::replace('Ç', 'C', $text);
        $text = Str::replace('Ğ', 'G', $text);
        $text = Str::replace('İ', 'I', $text);

        return $text;

    }
    
    
}
