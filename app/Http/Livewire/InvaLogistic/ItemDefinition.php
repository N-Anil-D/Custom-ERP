<?php

namespace App\Http\Livewire\InvaLogistic;

use Livewire\{Component,WithPagination,WithFileUploads};
use Illuminate\Support\Facades\{Validator,Session,DB};
use App\Models\ItemDefinitionList;
use App\Imports\ItemDefinitionImport;
use Maatwebsite\Excel\Facades\Excel;

class ItemDefinition extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $listData = [];
    public $rowId;
    public $action;
    public $search;

    public function render()
    {
        return view('livewire.inva-logistic.item-definition', [
            'ItemDefinitionList' => ItemDefinitionList::where('name','like','%'.$this->search.'%')
                ->where('listId',0)
                ->paginate(20),
        ]);
    }

    public function process($rowId,$action)
    {
        $this->rowId = $rowId;
        $this->action = $action;
        
        if($action == 'delete'){
            
            $this->dispatchBrowserEvent('deleteModalShow');
            
        }elseif($action == 'insert'){
            $this->clearItem();
            $this->dispatchBrowserEvent('insertModalShow');
        }
    }

    public function insert()
    {
        Validator::make($this->listData, [
            'name' => 'required',
            'excelFile' => 'required|mimes:xlsx, xls',
        ])->validate();
        Excel::import(new ItemDefinitionImport($this->listData['name']), $this->listData['excelFile']);

        $this->dispatchBrowserEvent('insertModalHide');
        $this->updating();
        Session::flash('success', trans('site.general.complete'));
    }

    public function delete()
    {
        ItemDefinitionImport::where('listId', $this->rowId)->delete();
        ItemDefinitionImport::where('id', $this->rowId)->delete();
        Session::flash('success','Silme işlemi tamamlandı.');
        $this->updating();
        $this->dispatchBrowserEvent('brcListDeleteModalHide');
        
    }

    public function clearItem()
    {
        $this->listData = [];
    }

}
