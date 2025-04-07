<?php

namespace App\Models\Erp\Order;

use App\Models\Erp\Item\ErpItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpOrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'item_id',
        'amount'
    ];

    public function item()
    {
        return $this->hasOne(ErpItem::class, 'id', 'item_id');
    }
}
