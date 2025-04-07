<?php

namespace App\Http\Livewire\Uretim;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\UretimMiniBrcList;
use App\Imports\UretimMini;
use App\Models\UretimMiniBrcLine;


class Mini extends Component
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
            
            $this->dispatchBrowserEvent('uretimMiniBrcListDeleteModalShow');
            
        }else{
            
            $this->clearItem();
            $this->dispatchBrowserEvent('uretimMiniBrcListInsertModalShow');
            
        }
        
    }

    public function brcListInsert()
    {
        Validator::make($this->listData, [
            'name' => 'required',
            'excelFile' => 'required|mimes:xlsx, xls',
            'type' => session()->get('ticketType'),
        ])->validate();        
        
        $brc = UretimMiniBrcList::create();

        $import = new UretimMini($brc->id);
        $import->import($this->listData['excelFile']);
        
        
        if(isset($this->listData['name'])){
            $brc->update([
                'name' => $this->listData['name'],
                'type' => session()->get('ticketType'),
            ]);
        }else{
           
            $brc->update([
                'name' => $brc->lisToLin[0]->content,
                'type' => session()->get('ticketType'),
            ]);
        }
        
        $this->dispatchBrowserEvent('uretimMiniBrcListInsertModalHide');
        $this->updating();
        Session::flash('success', trans('site.general.complete'));
        
    }

    public function delete()
    {
        UretimMiniBrcLine::where('listId', $this->rowId)->delete();
        UretimMiniBrcList::where('id', $this->rowId)->delete();
        Session::flash('success','Silme işlemi tamamlandı.');
        $this->updating();
        $this->dispatchBrowserEvent('uretimMiniBrcListDeleteModalHide');
        
    }

    public function clearItem()
    {
        $this->listData = [];
    }



    public function render()
    {
        return view('livewire.uretim.mini',[
            'brcList' => UretimMiniBrcList::where('type',Session::get('ticketType'))
                ->where('name','like','%'.$this->search.'%')
                ->orderBy('id','desc')
                ->paginate(20),
        ]);
    }
}
