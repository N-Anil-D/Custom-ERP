<?php

namespace App\Console\Commands\TestOrOld;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LucaStockAlertMail extends Command
{
    protected $signature    = 'testorold:LucaStockAlertMail';
    protected $description  = 'Command description ??';

    public function handle()
    {
        Log::channel('console')->info($this->signature.' is start');
        Log::channel('console')->info($this->signature.' is end');
    }
}
