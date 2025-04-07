<?php

namespace App\Console\Commands\Erp;

use Illuminate\Console\Command;
use App\Models\Erp\Warehouse\{ErpProduction,ErpFinishedProduct};
use App\Models\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\{Mail, Log};
use Illuminate\Support\Str;
use NotificationChannels\Telegram\TelegramMessage;

class ProductionReport extends Command
{
    protected $signature    = 'erp:ProductionReport';
    protected $description  = 'Erp production report';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::channel('console')->info($this->signature.' is start');
        $this->reportMail();
        $this->reportTelegram();
        Log::channel('console')->info($this->signature.' is end');
    }
    
    public function reportMail(){
        // son 24 saatte üretilmiş ürünler
        $productions = ErpProduction::with('contents', 'item', 'warehouse', 'user')
            ->where('status', 1)
            ->where('created_at', '>', now()->subHour(24))
            ->get();
        
        $finishedProducts = ErpFinishedProduct::with('item', 'warehouse')
            ->where('created_at', '>', now()->subHour(24))
            ->get();
            
        // üretim raporu alabilecek kullanıcılar
        $reportUsers = User::where('active', 1)
        ->where('production_report', 1)
        ->get();
        // report production mail
        if($productions->count()){
            foreach($reportUsers as $user){
                $mail       = $user->email;
                $data       = $user;
                $subData    = $productions;
                $subj       = 'Günlük Üretim Raporu';
                if(!is_null($mail)){
                    $view = 'mail.production-report';
                    Mail::to($mail)->send(new SendMail($data, $subData, $subj, $view));
                }
            }
        }
        
        // report finished products mail
        if($finishedProducts->count()){
            foreach($reportUsers as $user){
                $mail       = $user->email;
                $data       = $finishedProducts;
                $subData    = $finishedProducts;
                $subj       = 'Günlük Bitmiş Ürün Raporu';
                if(!is_null($mail)){
                    $view = 'mail.information-finished-products';
                    Mail::to($mail)->send(new SendMail($data, $subData, $subj, $view));
                }
            }
        }
    }
        
        
    public function reportTelegram()
    {
        // son 24 saatte tamamlanmış üretimler
        $productions = ErpProduction::with('contents', 'item', 'warehouse', 'user')
            ->where('status', 1)
            ->where('created_at', '>', now()->subHour(24))
            ->get();

        $finishedProducts = ErpFinishedProduct::with('item', 'warehouse')
            ->where('created_at', '>', now()->subHour(24))
            ->get();

        // üretim raporu alabilecek kullanıcılar
        $reportUsers = User::where('active', 1)
            ->where('production_report', 1)
            ->get();
        foreach($productions as $production){
            foreach($reportUsers as $user){
                TelegramMessage::create()
                ->to($user->telegram_id)
                ->line('Sayın '. $user->name)
                ->line('Üretim ID : #'. Str::padLeft($production->id, 6, '0'))
                ->line('')
                ->line('- Üretim kaydını oluşturan : ' . $production->user->name)
                ->line('- Üretimin gerçekleştiği yer : ' . $production->warehouse->name)
                ->line('- Üretilen malzeme : ' . $production->item->name .' / '. $production->item->code)
                ->line('- Üretilen miktar : ' . $production->amount .' / '. $production->item->itemToUnit->code)
                ->line('- Başlangıç : ' . $production->created_at)
                ->line('- Bitiş : ' . $production->updated_at)
                ->line('- Üretim sorumlusunun üretime verdiği isim : ' . $production->name)
                ->send();
            }
        }
        foreach($finishedProducts as $finishedProduct){
            foreach($reportUsers as $user){
                TelegramMessage::create()
                ->to($user->telegram_id)
                ->line('Sayın '. $user->name)
                ->line('Bitmiş Ürün ID : #'. Str::padLeft($finishedProduct->id, 6, '0'))
                ->line('')
                ->line('- Bitmş LOT : ' . $finishedProduct->lot_no)
                ->line('- Bitmş Ürün : ' . $finishedProduct->item->name)
                ->line('- Üretilen miktar : ' . $finishedProduct->amount .' / '. $finishedProduct->item->itemToUnit->code)
                ->line('- Bitmş Ürünü Oluşturan : ' . $finishedProduct->user->name)
                ->line('- Bitmş Ürünün Konumu : ' . $finishedProduct->warehouse->name)
                ->line('- Hedef Gönderim Tarihi : ' . $finishedProduct->send_date)
                ->line('- Not : ' . $finishedProduct->note)
                ->line('- Durum : ' . $finishedProduct->getStatus())
                ->send();
            }
        }
    }
    
}
