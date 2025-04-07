<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpProduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'warehouse_id',
        'lot_no',
        'work_order_no',
        'name',
        'amount',
        'status',
    ];

    public function contents()
    {
        return $this->hasMany(ErpProductionContent::class, 'production_id', 'id');
    }

    public function item()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }

    public function warehouse()
    {
        return $this->hasOne(\App\Models\Erp\Warehouse\ErpWarehouse::class, 'id', 'warehouse_id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
