<?php

namespace App\Imports;

//use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\InvaLogisticBrcLine;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Session;


class InvaLogisticBarcodeImport implements ToModel,WithHeadingRow
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
        
        $p1 = Str::substr($row['lot'],0,2);
        $p2 = Str::substr($row['lot'],2,2);
        
        InvaLogisticBrcLine::create([
            'listId'    => $this->brcListId,
            'lot'       => $row['lot'],
            'lotDate'   => $p2."/".$p1,
            'ref'       => $row['ref'],
            'barcode'   => $row['barcode'],
            'name'      => $row['name'],
            'amount'    => $row['amount'],
            'content'   => $row['content']
        ]);
        
        //Session::put('brcListName',$row['content']);
        
    }
}
