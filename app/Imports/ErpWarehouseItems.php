<?php

namespace App\Imports;

use App\Models\Erp\Item\ErpItemsWarehouses;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ErpWarehouseItems implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    // public function firstOrCreate(array $row){

    // }

    public function model(array $row)
    {
        $firstOrCreate = ErpItemsWarehouses::where('warehouse_id',$row['warehouse_id'])->where('item_id',$row['item_id'])->first();
        if($firstOrCreate){
            $firstOrCreate->amount = $row['amount'];
            $firstOrCreate->save();
            return;
        }else{
            return new ErpItemsWarehouses([
                'item_id' =>$row['item_id'],
                'warehouse_id' =>$row['warehouse_id'],
                'amount' =>$row['amount'],
            ]);

        }
    }
}
