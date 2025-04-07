<?php

namespace App\Console\Commands\TestOrOld;

use Illuminate\Console\Command;
use App\Models\LucaStockAlert;
use App\Models\{User};
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Support\Facades\Log;


class LucaStockAlertNotify extends Command
{
    protected $signature    = 'testorold:LucaStockAlertNotify';
    protected $description  = 'Luca stock alert notify';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::channel('console')->info($this->signature.' is start');
        
        $alerts = LucaStockAlert::get();
        foreach($alerts as $alert){
            if($alert->alertCondition === '<'){
                if($alert->altToIte->toplam < $alert->amount){
                    $this->alertProcess($alert);
                }
            }else{
                if($alert->altToIte->toplam > $alert->amount){
                    $this->alertProcess($alert);
                }
            }
            
        }     
        
        Log::channel('console')->info($this->signature.' is end');
    }
    
    public function alertProcess($alert){
        if(!$alert->warned){
            
            $limit = $alert->alertCondition === '<' ? 'ALTINA DÜŞMÜŞTÜR':'ÜSTÜNE ÇIKMIŞTIR';

            $user = User::find($alert->userId);
            if($user->telegram_id){
                TelegramMessage::create()
                    ->to($user->telegram_id)
                    ->line('Merhaba '.$user->name.'. Aşağıda detayları belirtilmiş olan ürün belirlediğiniz limitin **'.$limit.'**.')
                    ->line('ID : '.$alert->altToIte->id)
                    ->line('Stok adı : '.$alert->altToIte->kartAdi)
                    ->line('Stok kodu : '.$alert->altToIte->kartKodu)
                    ->line('Sınırı : '.$alert->amount)
                    ->line('Mevcut miktarı : '.$alert->altToIte->toplam)
                ->send();
            }

            $alert->warned = TRUE;
            $alert->save();
            
        }
    }
}
