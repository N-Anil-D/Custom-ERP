<?php

namespace App\Console\Commands\TestOrOld;

use Illuminate\Console\Command;
use App\Models\TestModel;
use Illuminate\Support\Facades\Log;

class TestCommand extends Command
{
    protected $signature    = 'testorold:TestCommand';
    protected $description  = 'Test command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::channel('console')->info($this->signature.' is start');

         for ($i=0; $i < 10; $i++) {
            TestModel::create([
                'name' => $i.". kayıt",
            ]);            
            
        }

        Log::channel('console')->info($this->signature.' is end');
    }
}
