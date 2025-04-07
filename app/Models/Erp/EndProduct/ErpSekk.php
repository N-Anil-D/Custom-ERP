<?php

namespace App\Models\Erp\EndProduct;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class ErpSekk extends Model
{
    use HasFactory, SoftDeletes;
    const unscripted = 'Yeni durum eklenecek... (lütfen iletişime geçiniz)';
    protected $table = "erp_sekk";

    protected $fillable = [
        'lot_no',
        'item_id',
        'user_id',
        'warehouse_id',
        'clean_status',
        'work_order_no',
        'amount',
        'general_status',
        'text',
    ];

    public function getGeneralStatus()
    {
        $general_status = self::unscripted;

        switch ($this->general_status) {
            case 1: $general_status = 'Sterilizasyon & Karantina'; break;
            case 2: $general_status = 'Kalite kontrol devam ediyor'; break;
            case 3: $general_status = 'Kolileme onayı bekliyor'; break;
            case 4: $general_status = 'Kolileme onaylandı & Kolileme devam ediyor'; break;
            case 5: $general_status = 'Kolilendi'; break;
            case 6: $general_status = 'İmha edildi'; break;
        }

        return $general_status;
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
