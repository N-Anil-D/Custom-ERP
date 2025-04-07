<?php

namespace App\Http\Livewire\Uretim;

use Livewire\Component;
use App\Models\UretimTubeBrcList;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use App\Imports\UretimBarcodeTube;
use App\Models\UretimTubeBrcLine;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

class BarcodeTube extends Component
{
    
    use WithFileUploads;
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $listData = [];
    public $rowId;
    public $action;
    public $search;
    
    public function process($rowId,$action)
    {
        $this->rowId = $rowId;
        $this->action = $action;
        
        
        if($action == 'delete'){
            
            $this->dispatchBrowserEvent('uretimTubeBrcListDeleteModalShow');
            
        }else{
            
            $this->clearItem();
            $this->dispatchBrowserEvent('uretimTubeBrcListInsertModalShow');
            
        }
        
    }
    
    public function brcListInsert()
    {
        Validator::make($this->listData, [
            'name' => 'required',
            'excelFile' => 'required|mimes:xlsx, xls',
        ])->validate();        
        
        $brc = UretimTubeBrcList::create();

        $import = new UretimBarcodeTube($brc->id);
        $import->import($this->listData['excelFile']);
        
        
        if(isset($this->listData['name'])){
            $brc->update([
                'name' => $this->listData['name']
            ]);
        }else{
           
            $brc->update([
                'name' => $brc->lisToLin[0]->content,
            ]);
        }
        
        $this->dispatchBrowserEvent('uretimTubeBrcListInsertModalHide');
        $this->updating();
        Session::flash('success', trans('site.general.complete'));
        
    }
    
    public function delete()
    {
        UretimTubeBrcLine::where('listId', $this->rowId)->delete();
        UretimTubeBrcList::where('id', $this->rowId)->delete();
        Session::flash('success','Silme işlemi tamamlandı.');
        $this->updating();
        $this->dispatchBrowserEvent('uretimTubeBrcListDeleteModalHide');
        
    }
    
        
    public function clearItem()
    {
        $this->listData = [];
    }
    
    
    public function render()
    {
        return view('livewire.uretim.barcode-tube', [
            'brcList' => UretimTubeBrcList::where('name','like','%'.$this->search.'%')
                ->orWhere('created_at','like','%'.$this->search.'%')
                ->orderBy('id','desc')
                ->paginate(20),
        ]);
    }
}