<?php

namespace App\Console\Commands\Fixture;

use Illuminate\Console\Command;
use App\Models\FixturesItem;
use Illuminate\Support\Facades\{Storage, Log};
use Illuminate\Support\Str;
use PDF;

class GenerateAssetTags extends Command
{
    // protected $signature    = 'command:generateAssetTags {param?}'; // for parameter example
    // dd($this->argument('param')); // for parameter example
    protected $signature    = 'fixture:GenerateAssetTags';
    protected $description  = 'Tüm demirbaş etiketlerini üretir.';

    # etiket boyutu en x boy
    const   point   = 2.8346456693;
    const   width   = 70;
    const   height  = 50;
    public  $index  = 0;

    public function handle()
    {       
        Log::channel('console')->info($this->signature.' is start');

        $data = FixturesItem::where('amount', '<', 100)->orderBy('id')->get();
        $customPaper = array(0, 0, self::width * self::point, self::height * self::point);
        
        $rowTotal = 0;
        $rowArray = [];
        
        foreach($data as $row){
            
            $rowTotal += $row->amount;
            array_push($rowArray, $row->id);

            if($rowTotal > 100){
                
                $this->index++;
                $labels = FixturesItem::whereIn('id', $rowArray)->orderBy('id')->get();
                $pdf = PDF::loadView('fixture.label',compact('labels'))->setPaper($customPaper);
                Storage::put('fixtures/'.Str::padleft($this->index, 3, 0).'_'.Str::padLeft($rowTotal, 4, 0).'_'.$labels[0]->code.'.pdf', $pdf->output());
                $rowTotal = 0;
                $rowArray = [];
        
            }
        }
        
        FixturesItem::whereBetween('amount', [99, 300])
            ->orderBy('id')->chunk(1, function($labels){

            $customPaper = array(0, 0, self::width * self::point, self::height * self::point);
            $this->index++;
            $pdf = PDF::loadView('fixture.label',compact('labels'))->setPaper($customPaper);
            Storage::put('fixtures/'.Str::padleft($this->index, 3, 0).'_'.Str::padLeft($labels[0]->amount, 4, 0).'_'.$labels[0]->code.'.pdf', $pdf->output());

        });

        Log::channel('console')->info($this->signature.' is end');
    }
}
