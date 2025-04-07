<?php

namespace App\Imports;

use App\Models\Erp\Item\ErpItem;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class ErpItemEkle implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $increment = 1;

    public function model(array $row)
    {
        if($row['code']== null || $row['code']== ''){
            $row['code'] = 'Geçici UBB'.$this->increment;
            $this->increment++;
        }
        return new ErpItem([
            'unit_id' =>$row['unit_id'],
            'code' =>$row['code'],
            'name' =>$row['name'],
            'content' =>$row['content'],
            'type' =>$row['type'],
            'barcode' =>$row['barcode'],
        ]);
    }
}
