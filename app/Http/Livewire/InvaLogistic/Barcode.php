<?php

namespace App\Http\Livewire\InvaLogistic;

use Livewire\Component;
use App\Models\InvaLogisticBrcList;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use App\Imports\InvaLogisticBarcodeImport;
use App\Models\InvaLogisticBrcLine;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;


class Barcode extends Component
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
            
            $this->dispatchBrowserEvent('brcListDeleteModalShow');
            
        }else{
            $this->clearItem();
            $this->dispatchBrowserEvent('brcListInsertModalShow');
        }
    }
    
    public function brcListInsert()
    {
        Validator::make($this->listData, [
            'excelFile' => 'required|mimes:xlsx, xls',
        ])->validate();        
        
        $brc = InvaLogisticBrcList::create();

        $import = new InvaLogisticBarcodeImport($brc->id);
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
        
        $this->dispatchBrowserEvent('brcListInsertModalHide');
        $this->updating();
        Session::flash('success', trans('site.general.complete'));
        
    }
    
    public function delete()
    {
        InvaLogisticBrcLine::where('listId', $this->rowId)->delete();
        InvaLogisticBrcList::where('id', $this->rowId)->delete();
        Session::flash('success','Silme işlemi tamamlandı.');
        $this->updating();
        $this->dispatchBrowserEvent('brcListDeleteModalHide');
        
    }
    
    public function clearItem()
    {
        $this->listData = [];
    }


    public function render()
    {
        return view('livewire.inva-logistic.barcode', [
            'brcList' => InvaLogisticBrcList::with('lisToLin')->where('name','like','%'.$this->search.'%')
                ->orWhere('created_at','like','%'.$this->search.'%')
                ->orderBy('id','desc')
                ->paginate(20),
        ]);
    }
}