<?php

namespace App\Http\Livewire\Uretim;

use Livewire\Component;
use App\Models\UretimPackageBrcList;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use App\Imports\UretimBarcodePackage;
use App\Models\UretimPackageBrcLine;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

class BarcodePackage extends Component
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
            
            $this->dispatchBrowserEvent('uretimPackageBrcListDeleteModalShow');
            
        }else{
            
            if($action == 'see'){
                
                $this->see();
                
            }else{
                
                $this->clearItem();
                $this->dispatchBrowserEvent('uretimPackageBrcListInsertModalShow');
                
            }
            
            
            
        }
        
    }
    
    public function brcListInsert()
    {
        Validator::make($this->listData, [
            'name' => 'required',
            'excelFile' => 'required|mimes:xlsx, xls',
        ])->validate();        
        
        $brc = UretimPackageBrcList::create();

        $import = new UretimBarcodePackage($brc->id);
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
        
        $this->dispatchBrowserEvent('uretimPackageBrcListInsertModalHide');
        $this->updating();
        Session::flash('success', trans('site.general.complete'));
        
    }
    
    public function delete()
    {
        UretimPackageBrcLine::where('listId', $this->rowId)->delete();
        UretimPackageBrcList::where('id', $this->rowId)->delete();
        Session::flash('success','Silme işlemi tamamlandı.');
        $this->updating();
        $this->dispatchBrowserEvent('uretimPackageBrcListDeleteModalHide');
        
    }
    
    public function clearItem()
    {
        $this->listData = [];
    }
    
    public function see()
    {
        $this->dispatchBrowserEvent('uretimPackageBrcListViewModalShow');
    }
    public function render()
    {
        return view('livewire.uretim.barcode-package', [
            'brcList' => UretimPackageBrcList::where('name','like','%'.$this->search.'%')
                ->orWhere('created_at','like','%'.$this->search.'%')
                ->orderBy('id','desc')
                ->paginate(20),
        ]);
    }
}
