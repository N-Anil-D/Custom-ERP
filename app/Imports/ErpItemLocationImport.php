<?php

namespace App\Imports;

use App\Models\Erp\Item\ErpItemsLocation;
use App\Models\Erp\Item\ErpItemsWarehouses;
use App\Models\Erp\Item\ErpItem;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class ErpItemLocationImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $item = ErpItem::find($row['item_id']);
        // $itemInWarehouse = ErpItemsWarehouses::where('item_id',$row['item_id'])->where('warehouse_id',$row['warehouse_id'])->first();
        // $isExist = ErpItemsLocation::where('item_id',6456456464)->where('warehouse_id',$row['warehouse_id'])->first();
        // if(isset($item) && isset($itemInWarehouse) && !isset($isExist)){
        if(isset($item)){
                return new ErpItemsLocation([
                    'item_id' =>$row['item_id'],
                    'warehouse_id' =>$row['warehouse_id'],
                    'p1' =>$row['p1'],
                    'p2' =>$row['p2'],
                    'p3' =>$row['p3'],
                ]);
        }
    }
}
