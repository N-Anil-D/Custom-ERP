<?php

namespace App\Http\Livewire\Erp\Item;

use Livewire\{Component, WithPagination};
use App\Models\Erp\Item\ErpItemVariety;
use Illuminate\Support\Facades\{Validator, Session};

class Variety extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    const model = 'variety';

    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    public $search;

    public function render()
    {
        return view('livewire.erp.item.variety', [
            'data' => ErpItemVariety::where('name', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate(20),
        ]);
    }

    public function process($rowId, $action)
    {
        $this->rowId    = $rowId;
        $this->action   = $action;

        if ($action == 'delete') {
            $this->dispatchBrowserEvent(self::model.$this->action.'modalShow');
        } else {
            if ($action == 'insert') {
                $this->clearItem();
            } else {
                $this->selectedModelData = ErpItemVariety::find($this->rowId);
                $this->selectedArrayData = [
                    'name'      => $this->selectedModelData->name,
                ];
            }
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');
        }
    }

    public function upsert()
    {
        
        $validateData = $this->validateData();

        if ($this->action == 'insert') {
            ErpItemVariety::create($validateData);
        } else {
            $this->selectedModelData->update($validateData);
        }

        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.'upsertmodalHide');
    }

    public function clearItem()
    {
        $this->selectedArrayData = [
            'name'      => '',
        ];
    }

    public function delete()
    {
        ErpItemVariety::find($this->rowId)->delete();
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function validateData()
    {
        if($this->action == 'insert'){
            $validateData = Validator::make($this->selectedArrayData, [
                'name'      => 'required|unique:erp_item_varieties,name',
            ])->validate();
        }else{
            $validateData = Validator::make($this->selectedArrayData, [
                'name'   => 'required',
            ])->validate();
        }
        return $validateData;
    }

}
