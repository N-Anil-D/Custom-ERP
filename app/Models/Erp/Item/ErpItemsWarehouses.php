<?php

namespace App\Models\Erp\Item;

use App\Models\Erp\Warehouse\ErpWarehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpItemsWarehouses extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'amount',
    ];

    public function warehouse()
    {
        return $this->hasOne(ErpWarehouse::class, 'id', 'warehouse_id');
    }

    public function item()
    {
        return $this->hasOne(ErpItem::class, 'id', 'item_id');
    }
}
