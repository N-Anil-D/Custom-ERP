<?php

namespace App\Http\Livewire\Luca;

use Livewire\Component;
use App\Models\LucaStockList;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockExport;
use App\Models\LucaStockAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class StockList extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $search;
    public $rowId;
    public $item = [];
    public $alert = [];
    public $process;
    public $action;
    
    public function downloadExcel()
    {
        
        return Excel::download(new StockExport(), 'INVAportal_StockList_'.date('Ymd-Hi').'.xlsx');
        
    }
    
    public function process($rowId,$process)
    {
        
        $this->rowId = $rowId;
        $this->process = $process;
        
        if($process == 'delete'){
            
            $this->dispatchBrowserEvent('alertDeleteModalShow');
            
        }else{
            
            $this->item = LucaStockList::find($this->rowId);  
            $this->clearItem();
            $this->dispatchBrowserEvent('stockModalShow');
            
        }     
        
    }
    
    
    public function insertOrUpdate()
    {
        
        Validator::make($this->alert, [
            'amount' => 'required|numeric',
        ])->validate();
        
        if($this->alert['alertCondition'] === ""){
            $this->alert['alertCondition'] = "<";
        }
        
        $newAlert = LucaStockAlert::create([
            'userId' => Auth::user()->id,
            'itemId' => $this->rowId,
            'amount' => $this->alert['amount'],              
            'alertCondition' => $this->alert['alertCondition'],
            'warned' => FALSE,
        ]);
        
        $this->dispatchBrowserEvent('stockModalHide');        
        Session::flash('success','Alarm kuruldu');
        
    }
    
    public function delete()
    {
        LucaStockAlert::where('itemId', $this->rowId)
                ->where('userId',Auth::user()->id)
                ->delete();
        
        $this->dispatchBrowserEvent('alertDeleteModalHide');        
        Session::flash('error','Alarm kaldırıldı');
              
    }
    
    public function clearItem()
    {
        
        $this->alert = [
            'amount' => '',
            'alertCondition' => ''
        ];
         
    }

    
    public function render()
    {
        return view('livewire.luca.stock-list', [
            'data' => LucaStockList::where('kartKodu','like','%'.$this->search.'%')
                ->orWhere('kartAdi','like','%'.$this->search.'%')
                ->orderBy('stokTipi')
                ->paginate(10),
        ]);
    }
}
