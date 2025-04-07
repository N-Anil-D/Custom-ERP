<?php

namespace App\Imports;

use App\Models\Erp\Warehouse\ErpFinishedProduct;
use DateTime;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class ErpFinishedProducts implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row['amount']== null || $row['amount']== ''){
            $row['amount'] = 0;
        }
        return new ErpFinishedProduct([
            'user_id' =>$row['user_id'],
            'warehouse_id' =>$row['warehouse_id'],
            'item_id' =>$row['item_id'],
            'lot_no' =>$row['lot_no'],
            'amount' =>$row['amount'],
            'status' =>$row['status'],
            'send_date' =>date('Y-m-d'),
        ]);
    }
}