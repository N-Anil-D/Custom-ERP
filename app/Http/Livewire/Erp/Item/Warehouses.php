<?php

namespace App\Http\Livewire\Erp\Item;

use Livewire\{Component, WithPagination};
use Illuminate\Support\Facades\{Validator, Session};
use App\Models\Erp\Warehouse\ErpWarehouse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Erp\SystemDataExport;

class Warehouses extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    const model = 'warehouses';

    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    public $search;

    public function render()
    {
        return view('livewire.erp.item.warehouses', [
            'data' => ErpWarehouse::where('code', 'like', '%' . $this->search . '%')
                ->orWhere('name', 'like', '%' . $this->search . '%')
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
                $this->selectedModelData = ErpWarehouse::find($this->rowId);
                $this->selectedArrayData = [
                    'code'                      => $this->selectedModelData->code,
                    'name'                      => $this->selectedModelData->name,
                    'content'                   => $this->selectedModelData->content,
                    'can_take_from_outside'     => $this->selectedModelData->can_take_from_outside,
                    'can_send_to_outside'       => $this->selectedModelData->can_send_to_outside,
                ];
            }
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');
        }
    }

    public function upsert()
    {
        $validateData = $this->validateData();

        if ($this->action == 'insert') {
            ErpWarehouse::create($validateData);
        } else {
            $this->selectedModelData->update($validateData);
        }

        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.'upsertmodalHide');
    }

    public function clearItem()
    {
        $this->selectedArrayData = [
            'code'                      => null,
            'name'                      => null,
            'content'                   => null,
            'can_take_from_outside'     => 0,
            'can_send_to_outside'       => 0,
        ];
    }

    public function delete()
    {
        ErpWarehouse::find($this->rowId)->delete();
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function validateData()
    {
        if($this->action == 'insert'){
            $validateData = Validator::make($this->selectedArrayData, [
                'code'                      => 'required|min:3|unique:erp_warehouses,code',
                'name'                      => 'required',
                'content'                   => 'required',
                'can_take_from_outside'     => 'required',
                'can_send_to_outside'       => 'required',
            ])->validate();
        }else{
            $validateData = Validator::make($this->selectedArrayData, [
                'name'                      => 'required',
                'content'                   => 'required',
                'can_take_from_outside'     => 'required',
                'can_send_to_outside'       => 'required',
            ])->validate();
        }
        return $validateData;
    }

    public function systemExports(){
        $systemDataType = ErpWarehouse::class;
        Session::flash('success', trans('site.general.download'));
        return Excel::download(new SystemDataExport($systemDataType),'Genel tanımlamalar-Depo.xlsx');
    }

}
