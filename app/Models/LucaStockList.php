<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LucaStockList extends Model
{
    use HasFactory;
    
    protected $table = "luca_stocklist";
    protected $fillable = [
        'kartKodu',
        'kartAdi',
        'aktif',
        'eklemeTarihi',
        'dovizKod',
        'stokTipi',
        'alisKdvOran',
        'satisKdvOran',
        'ekleyen',
        'borc',
        'alacak',
        'tlBorc',
        'tlAlacak',
        'kartBakiye',
        'borcMiktar',
        'alacakMiktar',
        'toplam'
    ];
    
    //model-dışAnahtar-içAnahtar
    public function stkToTyp()
    {
        return $this->hasOne(LucaStockType::class,'typeId','stokTipi');
    }
    
}
