<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpProductionContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'main_item_id',
        'item_id',
        'warehouse_id',
        'amount',
        'wastage',
    ];

    public function item()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }
    
}
