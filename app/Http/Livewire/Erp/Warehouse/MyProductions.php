<?php

namespace App\Http\Livewire\Erp\Warehouse;

use App\Models\Erp\Item\ErpItem;
use App\Models\Erp\Warehouse\{ErpProduction, ErpProductionContent, ErpStockMovement};
use App\Models\Erp\Item\ErpItemsWarehouses;
use Livewire\{Component, WithPagination};
use Illuminate\Support\Facades\{Auth, Session, Validator};
use App\Models\View\Products;
use Carbon\Carbon;

class MyProductions extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    const model = 'myProductions';

    public $search;
    protected $queryString = ['search'];

    public $itemId;
    public $productionId;
    public $cancelProduction;
    public $warehouseId;
    public $item;
    public $lot_no;
    public $reverseProduction;
    public $reverseProductionContent;
    public $selectedArrayData = [];

    public function render()
    {
        return view('livewire.erp.warehouse.my-productions', [
            'data' => Products::where('user_id', Auth::user()->id)
                ->whereIn('erp_items_type', [1, 2])
                ->where(function ($q){
                    $q->orWhere('erp_items_code', 'like', '%'.$this->search.'%')
                    ->orWhere('erp_items_name', 'like', '%'.$this->search.'%')
                    ->orWhere('erp_warehouses_name', 'like', '%'.$this->search.'%')
                    ->orWhere('erp_items_warehouses_amount', 'like', '%'.$this->search.'%')
                    ->orWhere('erp_units_content', 'like', '%'.$this->search.'%');
            })->paginate(20),
            'data2' => ErpProduction::whereIn('warehouse_id', Auth::user()->warehouses->pluck('warehouse_id')->toArray())
                ->where('created_at',">", Carbon::yesterday())
                ->where('created_at',"<", Carbon::tomorrow())
                ->with('item')
            ->paginate(20),
            'productions' => ErpProduction::where('user_id', Auth::user()->id)->where('status', 0)->get(),
        ]);
    }

    public function openCreateProductionModal($itemId, $warehouseId)
    {
        $this->clearItem();
        $this->itemId = $itemId;
        $this->warehouseId = $warehouseId;
        $this->item = ErpItem::find($itemId);
        $this->dispatchBrowserEvent(self::model.'createProductionModalShow');
    }

    public function addProduction()
    {
        $validateData = Validator::make($this->selectedArrayData, [
            'name'          => 'required',
            'amount'        => 'required',
            // 'lot_no'        => 'string|nullable',
            'work_order_no' => 'string|nullable',
        ])->validate();
        
        $production = ErpProduction::create([
            'user_id'       => Auth::user()->id,
            'item_id'       => $this->itemId,
            'warehouse_id'  => $this->warehouseId,
            // 'lot_no'        => $validateData['lot_no'],
            'work_order_no' => $validateData['work_order_no'],
            'name'          => $validateData['name'],
            'amount'        => $validateData['amount'],
        ]);

        return redirect()->route('create.production',[Auth::user()->id, $this->itemId, $this->warehouseId, $production->id]);
    }

    public function cancelProductionCheck($id)
    {
        $this->productionId = $id;
        $this->cancelProduction = ErpProduction::find($this->productionId);
        $this->dispatchBrowserEvent(self::model.'cancelProductionModalShow');
    }

    public function cancelProduction()
    {
        $this->cancelProduction->delete();
        $this->dispatchBrowserEvent(self::model.'cancelProductionModalHide');
    }

    public function reverseProductionModal($productionID)
    {
        $this->productionId = $productionID;
        $this->reverseProduction = ErpProduction::find($this->productionId);
        $this->reverseProductionContent = ErpProductionContent::where('production_id',$this->productionId)->with('item')->get();
        $this->dispatchBrowserEvent(self::model.'reverseProductionModalShow');
    }

    public function reverseProduction()
    {
        #üretülen ürünü iptal et
        $decreaseProductedItem = ErpItemsWarehouses::where('warehouse_id',$this->reverseProduction->warehouse_id)->where('item_id',$this->reverseProduction->item_id)->first();
        #stok hareketlerine işlecek
        ErpStockMovement::create([
            'item_id'                   => $this->reverseProduction->item_id,
            'type'                      => 11, // üretim iptal
            'content'                   => "Üretim iptal",
            'dwindling_warehouse_id'    => $this->reverseProduction->warehouse_id,
            'increased_warehouse_id'    => 0,
            'sender_user'               => Auth::user()->id,
            'approval_user'             => Auth::user()->id,
            'amount'                    => $this->reverseProduction->amount,
            'old_warehouse_amount'      => $decreaseProductedItem->amount,
            'old_total_amount'          => $decreaseProductedItem->item->stocks->sum('amount'),
        ]);
        $decreaseProductedItem->amount = $decreaseProductedItem->amount - $this->reverseProduction->amount;
        $decreaseProductedItem->save();
        #üretimde harcanan ürünleri iptal et
        foreach ($this->reverseProductionContent as $value) {
            $increaseUsedItem = ErpItemsWarehouses::where('warehouse_id',$value->warehouse_id)->where('item_id',$value->item_id)->first();
            ErpStockMovement::create([
                'item_id'                   => $value->item_id,
                'type'                      => 11, // üretim iptal
                'content'                   => "Üretim iptal",
                'dwindling_warehouse_id'    => 0,
                'increased_warehouse_id'    => $value->warehouse_id,
                'sender_user'               => Auth::user()->id,
                'approval_user'             => Auth::user()->id,
                'amount'                    => ($value->amount - $value->wastage),
                'old_warehouse_amount'      => $increaseUsedItem->amount,
                'old_total_amount'          => $increaseUsedItem->item->stocks->sum('amount'),
            ]);
            $increaseUsedItem->amount = $increaseUsedItem->amount + ($value->amount - $value->wastage);
            $increaseUsedItem->save();
        }
        $this->reverseProduction->delete();
        $this->dispatchBrowserEvent(self::model.'reverseProductionModalHide');
    }

    public function clearItem()
    {
        $this->selectedArrayData = [
            'name'          => null,
            'amount'        => null,
            // 'lot_no'        => null,
            'work_order_no' => null,
        ];
        $this->productionId = null;
    }
}
