<?php

namespace App\Http\Livewire\Erp\Item;

use Livewire\{Component, WithPagination};
use App\Models\Erp\Item\ErpUnit;
use Illuminate\Support\Facades\{Validator, Session};
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Erp\SystemDataExport;

class Units extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    const model = 'units';

    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    public $search;

    public function render()
    {
        return view('livewire.erp.item.units', [
            'data' => ErpUnit::where('code', 'like', '%' . $this->search . '%')
                ->orWhere('content', 'like', '%' . $this->search . '%')
                ->orWhere('created_at', 'like', '%' . $this->search . '%')
                ->orWhere('updated_at', 'like', '%' . $this->search . '%')
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
                $this->selectedModelData = ErpUnit::find($this->rowId);
                $this->selectedArrayData = [
                    'code'      => $this->selectedModelData->code,
                    'content'   => $this->selectedModelData->content,
                ];
            }
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');
        }
    }

    public function upsert()
    {
        
        $validateData = $this->validateData();

        if ($this->action == 'insert') {
            ErpUnit::create($validateData);
        } else {
            $this->selectedModelData->update($validateData);
        }

        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.'upsertmodalHide');
    }

    public function clearItem()
    {
        $this->selectedArrayData = [
            'code'      => '',
            'content'   => '',
        ];
    }

    public function delete()
    {
        ErpUnit::find($this->rowId)->delete();
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function validateData()
    {
        if($this->action == 'insert'){
            $validateData = Validator::make($this->selectedArrayData, [
                'code'      => 'required|max:3|unique:erp_units,code',
                'content'   => 'required',
            ])->validate();
        }else{
            $validateData = Validator::make($this->selectedArrayData, [
                'content'   => 'required',
            ])->validate();
        }
        return $validateData;
    }

    public function systemExports(){
        $systemDataType = ErpUnit::class;
        Session::flash('success', trans('site.general.download'));
        return Excel::download(new SystemDataExport($systemDataType),'Genel tanımlamalar-Birim.xlsx');
    }

}
