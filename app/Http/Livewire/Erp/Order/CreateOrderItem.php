<?php

namespace App\Http\Livewire\Erp\Order;

use App\Models\Erp\Item\ErpItem;
use Livewire\{Component, WithPagination};
use App\Models\Erp\Order\{ErpOrder, ErpOrderItem};
use Illuminate\Support\{Carbon, Str};
use Illuminate\Support\Facades\{Validator, Session};


class CreateOrderItem extends Component
{

    const model = 'createorderitem';

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'thisRefresh' => '$refresh'
    ];

    
    public $mainId;
    public $orderData;
    public $orderItemData;
    public $itemData;
    public $remainingDay;
    public $item;
    public $itemId;

    public $searchItem;
    protected $queryString = ['searchItem'];

    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    
    public function render()
    {
        return view('livewire.erp.order.create-order-item');
    }

    public function boot()
    {
        $this->mainId = \Request::segment(3);
        $this->orderData = ErpOrder::find($this->mainId);
        $this->orderItemData = ErpOrderItem::with('item')->where('order_id', $this->mainId)->get();
        $searchItem = Str::of('%'.$this->searchItem.'%')->trim();
        $this->itemData = ErpItem::with('itemToUnit')
            ->where(function($q) use ($searchItem){
            return $q->where('code', 'like', $searchItem)
                    ->orWhere('name', 'like', $searchItem);
            })
        ->whereNotIn('id', $this->orderItemData->pluck('item_id'))
        ->where('type', 2)
        ->get();

    }
    
    public function updating()
    {
        $this->orderItemData = ErpOrderItem::with('item')->where('order_id', $this->mainId)->get();
        $searchItem = Str::of('%'.$this->searchItem.'%')->trim();
        $this->itemData = ErpItem::with('itemToUnit')
            ->where(function($q) use ($searchItem){
            return $q->where('code', 'like', $searchItem)
                    ->orWhere('name', 'like', $searchItem);
            })
        ->whereNotIn('id', $this->orderItemData->pluck('item_id'))
        ->where('type', 2)
        ->get();

    }
    

    public function remainingDayCalc()
    {

        $today = Carbon::now();
        $deliveryDate = Carbon::parse($this->orderData->delivery_date);
        $difference = $today->diffInDays($deliveryDate, true);
        if($difference >= 0) {
            return $difference. ' gün kaldı.';
        }else{
            return 'Teslim tarihini '.abs($difference).' gün geçti';
        }
        
    }

    public function addProductToOrder($itemId)
    {
        $this->itemId = $itemId;
        $this->item = ErpItem::find($itemId);
        $this->selectedArrayData['amount'] = '';
        $this->dispatchBrowserEvent('addProductToOrderModalShow');
    }

    public function addProductToOrderConfirm()
    {
        Validator::make($this->selectedArrayData, [
            'amount'    => 'required'
        ])->validate();

        ErpOrderItem::create([
            'order_id' => $this->mainId,
            'item_id' => $this->itemId,
            'amount' => $this->selectedArrayData['amount'],
        ]);

        Session::flash('success', trans('site.alert.data.insert.success'));

        $this->dispatchBrowserEvent('addProductToOrderModalHide');
        $this->updating();
        // $this->emit('thisRefresh');

    }


    // order item //

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
                $this->selectedModelData = ErpOrderItem::find($this->rowId);
                $this->selectedArrayData = [
                    'amount'  => $this->selectedModelData->amount,
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
        $this->updating();
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
        ErpOrderItem::find($this->rowId)->delete();
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
        $this->updating();        
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
                    'amount' => 'required',
            ])->validate();
        }
        return $validateData;
    }

    
}
