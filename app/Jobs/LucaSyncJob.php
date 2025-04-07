<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Luca\LucaIntegration;
use App\Models\LucaStockList;

class LucaSyncJob implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public function __construct() {
        
    }

    public function handle() {
        $luca = new LucaIntegration();
        if ($luca->getLogin()) {
            foreach ($luca->getStockList() as $list) {

                $model = LucaStockList::where('kartKodu', $list['kart.kartKodu'])->first();

                if ($model) {

                    $model->update([
                        'kartAdi' => $list['kart.kartAdi'],
                        'aktif' => $list['kart.aktif'],
                        'eklemeTarihi'  => $list['kart.eklemeTarihi'],
                        'dovizKod' => $list['kart.dovizKod'],
                        'stokTipi' => $list['stokTipi'],
                        'alisKdvOran' => $list['alisKdvOran'],
                        'satisKdvOran' => $list['satisKdvOran'],
                    ]);
                } else {

                    LucaStockList::create([
                        'kartKodu' => $list['kart.kartKodu'],
                        'kartAdi' => $list['kart.kartAdi'],
                        'aktif' => $list['kart.aktif'],
                        'eklemeTarihi'  => $list['kart.eklemeTarihi'],
                        'dovizKod' => $list['kart.dovizKod'],
                        'stokTipi' => $list['stokTipi'],
                        'alisKdvOran' => $list['alisKdvOran'],
                        'satisKdvOran' => $list['satisKdvOran'],
                    ]);
                }
            }

            $stockList = LucaStockList::get();

            foreach ($stockList as $list) {

                $data = $luca->getStock($list->kartKodu);

                if (isset($data['kart.kartKodu'])) {

                    $list->update([
                        'ekleyen' => $data['kart.ekleyen'],
                        'borc' => $data['kart.bakiye.borc'],
                        'alacak' => $data['kart.bakiye.alacak'],
                        'tlBorc' => $data['kart.bakiye.tlBorc'],
                        'tlAlacak' => $data['kart.bakiye.tlAlacak'],
                        'kartBakiye' => $data['kart.bakiye.kartBakiye'],
                        'borcMiktar' => $data['kart.bakiye.borcMiktar'],
                        'alacakMiktar' => $data['kart.bakiye.alacakMiktar'],
                        'toplam' => ($data['kart.bakiye.borcMiktar'] - $data['kart.bakiye.alacakMiktar']),
                    ]);
                } else {

                    $list->delete();
                }
            }
        }
    }

}
