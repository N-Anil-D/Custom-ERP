<?php

namespace App\Http\Livewire\Erp\Order;

use Livewire\{Component, WithPagination};
use App\Models\Erp\Order\{ErpOrder, ErpOrderItem};
use Illuminate\Support\Facades\{Validator, Session};
use Illuminate\Support\Str;

class CreateOrder extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    const model = 'createorder';

    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    public $search;

    public function render()
    {
        return view('livewire.erp.order.create-order', [
            'data' => ErpOrder::where('customer_name', 'like', '%' . $this->search . '%')
                ->orWhere('order_date', 'like', '%' . $this->search . '%')
                ->orWhere('delivery_date', 'like', '%' . $this->search . '%')
                ->orWhere('order_note', 'like', '%' . $this->search . '%')
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
                $this->selectedModelData = ErpOrder::find($this->rowId);
                $this->selectedArrayData = [
                    'order_number'  => $this->selectedModelData->order_number,
                    'customer_name' => $this->selectedModelData->customer_name,
                    'order_date'    => $this->selectedModelData->order_date,
                    'delivery_date' => $this->selectedModelData->delivery_date,
                    'order_note'    => $this->selectedModelData->order_note,
                ];
            }
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');
        }
    }

    public function upsert()
    {

        $validateData = $this->validateData();

        if ($this->action == 'insert') {
            ErpOrder::create($validateData);
        } else {
            $this->selectedModelData->update($validateData);
        }

        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.'upsertmodalHide');
    }

    public function clearItem()
    {
        $this->selectedArrayData = [
            'order_number'  => date('YmdHi').Str::upper(Str::random(3)),
            'customer_name' => '',
            'order_date'    => '',
            'delivery_date' => '',
            'order_note'    => '',
        ];
    }

    public function delete()
    {
        ErpOrder::find($this->rowId)->delete();
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function validateData()
    {
        if($this->action == 'insert'){
            $validateData = Validator::make($this->selectedArrayData, [
                    'order_number'  => 'required|unique:erp_orders,order_number',
                    'customer_name' => 'required',
                    'order_date'    => 'required|date',
                    'delivery_date' => 'required|date',
                    'order_note'    => 'min:0',
                ])->validate();
            }else{
                $validateData = Validator::make($this->selectedArrayData, [
                    'customer_name' => 'required',
                    'order_date'    => 'required|date',
                    'delivery_date' => 'required|date',
                    'order_note'    => 'min:0',
            ])->validate();
        }
        return $validateData;
    }
}
