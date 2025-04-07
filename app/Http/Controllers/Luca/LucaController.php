<?php

namespace App\Http\Controllers\Luca;

use App\Http\Controllers\Controller;
use App\Luca\LucaIntegration;
use App\Models\LucaStockList;
use App\Models\LucaStockAlert;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;


class LucaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:luca');
    }

    public function stokListesi()
    {        
        $title = 'Stok Listesi';
        return view('luca.stok.index',compact('title'));
    }
        
    public function integrationManuel()
    {
        
        $luca = new LucaIntegration();
        if($luca->getLogin()){
           foreach($luca->getStockList() as $list){

               $model = LucaStockList::where('kartKodu',$list['kart.kartKodu'])->first();
               
               if($model) {
                   
                   $model->update([
                        'kartAdi'       => $list['kart.kartAdi'],
                        'aktif'         => $list['kart.aktif'],
                        'eklemeTarihi'  => $list['kart.eklemeTarihi'],
                        'dovizKod'      => $list['kart.dovizKod'],
                        'stokTipi'      => $list['stokTipi'],
                        'alisKdvOran'   => $list['alisKdvOran'],
                        'satisKdvOran'  => $list['satisKdvOran'],
                   ]);
                   
               }else{
                   
                   LucaStockList::create([
                        'kartKodu'      => $list['kart.kartKodu'],
                        'kartAdi'       => $list['kart.kartAdi'],
                        'aktif'         => $list['kart.aktif'],
                        'eklemeTarihi'  => $list['kart.eklemeTarihi'],
                        'dovizKod'      => $list['kart.dovizKod'],
                        'stokTipi'      => $list['stokTipi'],
                        'alisKdvOran'   => $list['alisKdvOran'],
                        'satisKdvOran'  => $list['satisKdvOran'],
                   ]);
                   
               }
               
           }
           
            $stockList = LucaStockList::get();
            
            foreach ($stockList as $list){
               
                $data = $luca->getStock($list->kartKodu);
                
                if(isset($data['kart.kartKodu'])){
                    
                    $list->update([
                        'ekleyen'       =>$data['kart.ekleyen'],
                        'borc'          =>$data['kart.bakiye.borc'],
                        'alacak'        =>$data['kart.bakiye.alacak'],
                        'tlBorc'        =>$data['kart.bakiye.tlBorc'],
                        'tlAlacak'      =>$data['kart.bakiye.tlAlacak'],
                        'kartBakiye'    =>$data['kart.bakiye.kartBakiye'],
                        'borcMiktar'    =>$data['kart.bakiye.borcMiktar'],
                        'alacakMiktar'  =>$data['kart.bakiye.alacakMiktar'],
                        'toplam'        =>($data['kart.bakiye.borcMiktar']-$data['kart.bakiye.alacakMiktar']),
                    ]);
                    
                }else{
                    
                   $list->delete();
                   
                }
               
            }
               
        }
    }
    
    public function stockAlertManuel()
    {
        
        $alerts = LucaStockAlert::get();
        foreach($alerts as $alert){
            if($alert->alertCondition == '<'){
                if($alert->altToIte->toplam < $alert->amount){
                    $this->alertProcess($alert);
                }
            }else{
                if($alert->altToIte->toplam > $alert->amount){
                    $this->alertProcess($alert);
                }
            }
            
        }
        
    }
    
    public function alertProcess($alert)
    {
        if(!$alert->warned){
            
            $data = $alert;
            $subData = [];
            $subj = "ERPportal | ".$data->altToIte->kartKodu." - Stok Alarm Bildirimi";
            $view = "mail.lucaStockAlert";
            
            Mail::to($data->altToUsr->email)->send(new SendMail($data, $subData, $subj, $view));
            
            $alert->warned = TRUE;
            $alert->save();
            
        }
    }

    
    
    
}
