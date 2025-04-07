<?php

namespace App\Imports;

use App\Models\ItemDefinitionList;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;


class ItemDefinitionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public $listId;
    public $newFile;

    public function __construct($recordName){
        $this->newFile = new ItemDefinitionList;
        $this->newFile->listId = 0;
        $this->newFile->name = $recordName;
        $this->newFile->entry_date = Carbon::now();
        $this->newFile->company_name = 0;
        $this->newFile->amount = 0;
        $this->newFile->lot = 0;
        $this->newFile->save();
        $this->listId = $this->newFile->id;
    }


    public function model(array $row)
    {
    $entry_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['giris_tarihi']))->format('Y-m-d H:i:s');
    $last_use_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['son_kullanim_tarihi']))->format('Y-m-d H:i:s');
    $this->newFile->amount++;
    $this->newFile->save();
        return new ItemDefinitionList([
            'listId' => $this->listId,
            'name' => $row['isim'],
            'entry_date' => $entry_date,
            'irsaliye' => $row['irsaliye'],
            'company_name' => $row['tedarikci'],
            'amount' => $row['miktar'],
            'lot' => $row['lot'],
            'last_use_date' => $last_use_date,
            'controller' => $row['kontrolu_yapan'],
            'suitability' => $row['uygunluk_durumu'],
        ]);
    }
}
