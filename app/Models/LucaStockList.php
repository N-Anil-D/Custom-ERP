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
    
    
    /*
    *-"kartKodu" => "HM.ELK.00001"
    *-"kartAdi" => "12V 23A ALKALİN PİL"
    *-"aktif" => "E"
    *-"eklemeTarihi" => "25/03/2022 14:07:30"
    *-"dovizKod" => "TL"
    *-"stokTipi" => 1
    *-"alisKdvOran" => 18
    *-"satisKdvOran" => 18
      "stokKartKodu" => "HM.ELK.00001"
    *-"ekleyen" => "#invamed"
      "kart.tip" => 0
    *-"kart.bakiye.borc" => 100.0
    *-"kart.bakiye.alacak" => 847.46
    *-"kart.bakiye.tlBorc" => 100.0
    *-"kart.bakiye.tlAlacak" => 847.46
    *-"kart.bakiye.kartBakiye" => 0.0
    *-"kart.bakiye.borcMiktar" => 100.0
    *-"kart.bakiye.alacakMiktar" => 5.0
      "kart.bakiye.toplam" => false
      "alisKdvOran" => 18
      "satisKdvOran" => 18
    "alisHesapKodu" => "150.HM.GRN.00001"
    "satisHesapKodu" => "600.HM.GRN.00001"
    "alisIadeHesapKodu" => "150.HM.GRN.00001"
    "satisIadeHesapKodu" => "610.HM.GRN.00001"
    "stokKartKodu" => "HM.ELK.00001"
    "bakiyeli" => "E"
     * 
     * 
     */
}
