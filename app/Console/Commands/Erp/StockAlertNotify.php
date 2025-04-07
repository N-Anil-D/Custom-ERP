<?php

namespace App\Console\Commands\Erp;

use Illuminate\Console\Command;
use App\Models\Erp\Item\ErpItemsPersonalActions;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Support\Facades\Log;

class StockAlertNotify extends Command
{
    protected $signature    = 'erp:StockAlertNotify';
    protected $description  = 'Erp stock alert notify';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        
        $alerts = ErpItemsPersonalActions::with('item','user')->where('warned', 0)->get();
        foreach($alerts as $alert){

            if($alert->alert_condition === '<'){
                if($alert->item->stocks->sum('amount') < $alert->amount){
                    $this->alertProcess($alert);
                }
            }else{
                if($alert->item->stocks->sum('amount') > $alert->amount){
                    $this->alertProcess($alert);
                }
            }
            
        }   
    }

    public function alertProcess($alert)
    {

        $user = $alert->user;

        Log::channel('console')->info($this->signature.' | usr : '.$user->name.' | tlgId : '.$user->telegram_id);
        Log::channel('console')->info($alert->item->code.' | '.$alert->item->stocks->sum('amount').' '.$alert->alert_condition.' '.$alert->amount);

        $limit = $alert->alert_condition === '<' ? 'altına düştüğünde' : 'üstüne çıktığında';
        
        if($user->telegram_id){
            TelegramMessage::create()
                ->to($user->telegram_id)
                ->line('*'.$alert->item->code.' ÜRÜN KODU İÇİN KURDUĞUNUZ ALARM ÇALIŞTI*')
                ->line('')
                ->line('Alarm koşulunuz : '.$alert->amount.' '.$alert->item->itemToUnit->code.' '.$limit.' uyarı ver.')
                ->line('')
                ->line('Ürün adı : '.$alert->item->name)
                ->line('Şuanki ürün miktarı : '.$alert->item->stocks->sum('amount').' '.$alert->item->itemToUnit->code)
            ->send();
        }

        if ($alert->perma == false) {
            $alert->warned = TRUE;
        }
        $alert->save();
            
    }
}
