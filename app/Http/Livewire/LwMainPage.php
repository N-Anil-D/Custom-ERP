<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RoadMapSection;
use NotificationChannels\Telegram\TelegramUpdates;

class LwMainPage extends Component
{
   
    public function render()
    {
        try {
            $telegramData = TelegramUpdates::create()->get();
        } catch (\Throwable $th) {
            $telegramData = ['ok'=>null];
            //throw $th;
        }
        return view('livewire.lw-main-page', [
            'section' => RoadMapSection::with('secToDet')->where('active',1)->orderBy('line')->get(),
            'data' => $telegramData,
        ]);
    }
}
