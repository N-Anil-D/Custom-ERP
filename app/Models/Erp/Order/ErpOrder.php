<?php

namespace App\Models\Erp\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpOrder extends Model
{
    use HasFactory, SoftDeletes;

    const unscripted = 'Yeni tip eklenecek... (lütfen iletişime geçiniz)';

    protected $fillable = [
        'order_number',
        'customer_name',
        'order_date',
        'delivery_date',
        'order_status',
        'order_note'
    ];

    public function getStatus()
    {
        $status = self::unscripted;

        switch ($this->order_status) {
            case 0: $status = 'Alındı'; break;
            case 1: $status = 'Üretimde'; break;
            case 2: $status = 'Üretildi'; break;
        }

        return $status;
    }

    public function items()
    {
        return $this->hasMany(ErpOrderItem::class, 'order_id', 'id');
    }

}
