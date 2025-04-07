<?php

namespace App\Models\Erp\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpStockMovement extends Model
{
    use HasFactory, SoftDeletes;

    const unscripted = 'Yeni tip eklenecek... (lütfen iletişime geçiniz)';

    protected $fillable = [
        'item_id',
        'type',
        'content',
        'increased_warehouse_id',   // artan depo
        'dwindling_warehouse_id',   // azalan depo
        'sender_user',              // gönderen kullanıcı
        'approval_user',            // onaylayan kullanıcı
        'amount',                   // miktar
        'old_warehouse_amount',     // eski depo miktarı
        'old_total_amount',         // eski toplam miktar
    ];

    public function getType()
    {

        $status = self::unscripted;

        switch ($this->type) {
            case 0: $status = 'Transfer'; break;    // doğrudan stok transferi -- gönderen talep sahibi
            case 1: $status = 'Üretim'; break;      // üretime çıkış
            case 2: $status = 'Ürün girişi'; break; // faturadan mal kabul
            case 3: $status = 'Ürün satışı'; break; // ürün satışı
            case 4: $status = 'Stok sayımı'; break; // stok sayımı
            case 5: $status = 'Transfer'; break;    // doğrudan stok transferi -- alıcı talep sahibi
            case 6: $status = 'Ürün/İşlem'; break;  // üretim dışında ürünlerin işleme sokulması
            case 8: $status = 'Transfer'; break;    // doğrudan stok transferi -- alıcı talep sahibi eline talebin gçtiğini onayladı
            case 9: $status = 'Sterilizasyona Aktarım'; break;  // sterilizasyon
            case 10: $status = 'Diğer Çıkış'; break;  // Satış veya transfer olmayan çıkış
            case 11: $status = 'Üretim İptal'; break;  // Üretim İptal
            case 12: $status = 'Sterilizasyon Sürecinde Kullanıldı'; break;  // Sterilizasyon Sürecinde Kullanıldı
            case 13: $status = 'Paketleme Sürecinde Kullanıldı'; break;  // Paketleme Sürecinde Kullanıldı
        }

        return $status;

    }

    # artan depo
    public function getIncreasedWarehouse()
    {
        return $this->hasOne(\App\Models\Erp\Warehouse\ErpWarehouse::class, 'id', 'increased_warehouse_id');
    }

    # azalan depo
    public function getDwindlingWarehouse()
    {
        return $this->hasOne(\App\Models\Erp\Warehouse\ErpWarehouse::class, 'id', 'dwindling_warehouse_id');
    }

    # talep sahibi
    public function getSender()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'sender_user');
    }

    # onaylayan
    public function getApproval()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'approval_user');
    }

    # ürün
    public function item()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }

}
