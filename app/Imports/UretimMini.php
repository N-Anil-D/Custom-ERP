<?php

namespace App\Imports;

use Illuminate\Support\Collection;
//use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\UretimMiniBrcLine;
use Illuminate\Support\Facades\Session;


class UretimMini implements ToModel,WithHeadingRow
{
    use Importable;
    /**
    * @param Collection $collection
    */

    public $brcListId;

    public function __construct($brcListId)
    {
        $this->brcListId = $brcListId;
    }

    public function model(array $row)
    {
        UretimMiniBrcLine::create([
            'type' => session()->get('ticketType'),
            'listId' => $this->brcListId,
            'title' => $row['title'],
            'subtitle' => $row['subtitle'],
            'quantity' => $row['quantity'],
            'barcode' => $row['barcode'],
            'ref' => $row['ref'],
            'lot' => $row['lot'],
            'date1' => $row['date1'],
            'amount' => $row['amount'],
            'color' => $row['color'],
        ]);
    }
}
