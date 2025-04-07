<?php

namespace App\Console\Commands\TestOrOld;

use Illuminate\Console\Command;
use App\Jobs\LucaSyncJob;
use Illuminate\Support\Facades\Log;

class LucaSync extends Command
{
    protected $signature    = 'testorold:LucaSync';
    protected $description  = 'Luca Synchronization';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::channel('console')->info($this->signature.' is start');
        LucaSyncJob::dispatch();
        Log::channel('console')->info($this->signature.' is end');
    }
}
