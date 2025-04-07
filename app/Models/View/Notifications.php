<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    const unscripted = 'Yeni tip eklenecek... (lütfen iletişime geçiniz)';

    # talebi oluşturan kullanıcı
    public function getSender()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'erp_approvals_sender_user');
    }

    # artan depo
    public function getIncreasedWarehouse()
    {
        return $this->hasOne(\App\Models\Erp\Warehouse\ErpWarehouse::class, 'id', 'erp_approvals_increased_warehouse_id');
    }

    # azalan depo
    public function getDwindlingWarehouse()
    {
        return $this->hasOne(\App\Models\Erp\Warehouse\ErpWarehouse::class, 'id', 'erp_approvals_dwindling_warehouse_id');
    }

    # ürün
    public function getItem()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'erp_approvals_item_id');
    }

    public function getType()
    {

        $type = self::unscripted;
        
        switch ($this->erp_approvals_type) {
            case 0: $type = 'Depolar arası transfer'; break;            // doğrudan stok transferi
            case 1: $type = 'Üretim'; break;                            // üretime çıkış
            case 2: $type = 'Ürün girişi (Fatura ile giriş)'; break;    // faturadan mal kabul
            case 3: $type = 'Fatura ile çıkış (Satış)'; break;          // fatura ile mal satışı
        }
        
        return $type;
        
    }

}
