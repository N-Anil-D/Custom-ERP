<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use App\Models\Erp\Item\ErpItemsLocation;

class ErpFinishedProduct extends Model
{
    use HasFactory, SoftDeletes;

    const unscripted = 'Yeni durum eklenecek... (lütfen iletişime geçiniz)';

    protected $fillable = [
        'item_id',
        'user_id',
        'warehouse_id',
        'lot_no',
        'amount',
        'note',
        'status',
        'send_date',
    ];


    public function getStatus()
    {
        $status = self::unscripted;

        switch ($this->status) {
            case 1: $status = 'PAKETLENDİ & BEKLİYOR'; break;
            case 2: $status = 'Diğer'; break;
        }

        return $status;
    }

    public function item()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }

    public function warehouse()
    {
        return $this->hasOne(ErpWarehouse::class, 'id', 'warehouse_id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }

    public function location($itemID)
    {
        return ErpItemsLocation::where('item_id',$itemID)->where('warehouse_id',99)->get();
    }

}
