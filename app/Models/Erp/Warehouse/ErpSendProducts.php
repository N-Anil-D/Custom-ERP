<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class ErpSendProducts extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "erp_send_products";

    const unscripted = 'Yeni durum eklenecek... (lütfen iletişime geçiniz)';

    protected $fillable = [
        'item_id',
        'lot_no',
        'fatura_irsaliye_no',
        'send_to',
        'amount',
        'last_warehouse_id',
        'status',
        'user_id',
        'send_date',
    ];

    public function getStatus()
    {
        $status = self::unscripted;

        switch ($this->status) {
            case 0: $status = 'ONAY BEKLİYOR'; break;
            case 1: $status = 'GÖNDERİLDİ'; break;
            case 2: $status = 'GERİ ÇEKİLDİ'; break;
            case 3: $status = 'RED EDİLDİ'; break;
        }

        return $status;
    }

    public function item()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }

    public function warehouse()
    {
        return $this->hasOne(ErpWarehouse::class, 'id', 'last_warehouse_id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }

}
