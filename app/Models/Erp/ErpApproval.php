<?php

namespace App\Models\Erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpApproval extends Model
{
    use HasFactory, SoftDeletes;

    const unscripted = 'Yeni tip eklenecek... (lütfen iletişime geçiniz)';

    protected $fillable = [
        'item_id',
        'content',
        'content_answer',
        'file',
        'type',
        'status',
        'notify',
        'sender_user',              // gönderen kullanıcı
        'answer_user',              // onaylayan/iptal eden kullanıcı
        'increased_warehouse_id',   // artan depo
        'dwindling_warehouse_id',   // azalan depo
        'amount',                   // miktar
        'lot_no',                   // lot_no
    ];

    public function getType()
    {

        $type = self::unscripted;
        
        switch ($this->type) {
            case 0: $type = 'Depolar arası transfer'; break;                // doğrudan stok transferi // gönderici talep sahibi
            case 1: $type = 'Üretim'; break;                                // üretime çıkış
            case 2: $type = 'Ürün girişi (Fatura ile giriş)'; break;        // faturadan mal kabul
            case 3: $type = 'Çıkış (Satış)'; break;                         // fatura ile mal satışı
            case 4: $type = 'Sayım ile giriş'; break;                       // sayım verisi
            case 5: $type = 'Depolar arası transfer'; break;                // doğrudan stok transferi // alıcı talep sahibi
            // case 6: $type = '????????????'; break;                       // ????????????
            case 7: $type = 'Satın alma talebi'; break;                     // hammadde satın alma talebi
            case 8: $type = 'Depolar arası transfer(Elime ulaştı)'; break;  // doğrudan stok transferi // talep sahibi elim geçti onayı
            case 9: $type = 'Onaylanmış satın alma talebi'; break;          // Onaylanmış satın alma talebi 7->9->2
            case 10: $status = 'Diğer Çıkış'; break;  // Satış veya transfer olmayan çıkış
        }
        
        return $type;
        
    }
    
    public function getStatus()
    {
        $status = self::unscripted;

        switch ($this->status) {
            case 0: $status = 'Onay bekliyor'; break;
            case 1: $status = 'Onaylandı'; break;
            case 2: $status = 'İptal edildi'; break;
        }
        
        return $status;

    }

    public function getNotify()
    {
        return $this->notify.' adet bildirim gönderildi';
    }

    # talebi oluşturan kullanıcı
    public function getSender()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'sender_user')->where('active',1);
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

    # ürün
    public function getItem()
    {
        return $this->hasOne(\App\Models\Erp\Item\ErpItem::class, 'id', 'item_id');
    }
}
