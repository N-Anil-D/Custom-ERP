<?php

namespace App\Models\Erp\StockTaking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpStockTaking extends Model
{
    use HasFactory, SoftDeletes;

    const unscripted = 'Yeni tip eklenecek... (lütfen iletişime geçiniz)';

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'status',
        'amount',
        'counting_user',
        'approver_user',
    ];

    public function item()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }

    public function warehouse()
    {
        return $this->hasOne(\App\Models\Erp\Warehouse\ErpWarehouse::class, 'id', 'warehouse_id');
    }

    public function getStatus()
    {
        $status = self::unscripted;

        switch ($this->status) {
            case 0: $status = 'Sayım aşamasında'; break;
            case 1: $status = 'Onay bekliyor (sayıldı)'; break;
            case 2: $status = 'Onaylandı'; break;
            case 3: $status = 'İptal edildi'; break;
        }
        
        return $status;

    }

    public function countingUser()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'counting_user');
    }

    public function approverUser()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'approver_user');
    }




}
