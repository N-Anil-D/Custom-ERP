<?php

namespace App\Http\Livewire\Erp\Warehouse;

use Livewire\{Component, WithPagination};
use App\Models\View\Products;
use App\Models\Erp\Item\{ErpItem, ErpItemsWarehouses, ErpItemsLocation};
use Illuminate\Support\Facades\{Validator, Session, Storage, Auth};


class LogisticData extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $operation;
    public $search;
    public $asignLocationModalData = [];
    public $selectedItemID;
    public $warehouseID;
    public $selectedItem;
    public $selectedLocationData;
    public $p1;
    public $p2;
    public $p3;
    const model = 'itemLocation';

    public function render()
    {
        $this->warehouseBySegment();
        $logisticItems = ErpItemsWarehouses::where('warehouse_id',$this->warehouseID)->pluck('item_id');
        $itemsInWarehouse = ErpItem::whereIn('id',$logisticItems);
        return view('livewire.erp.warehouse.logistic-data',
            [
                'logisticDatas'=>$itemsInWarehouse->where(function($q){
                    $q->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
                })
                ->paginate(20),
            ]
        );

    }

    public function warehouseBySegment(){
        $segment = request()->segment(count(request()->segments()));
        if($segment == "ERPLogistic"){
            $this->warehouseID = 1;
        }elseif($segment == "semi-product"){
            $this->warehouseID = 27;
        }
    }

    public function asignNewLocationData($itemId)
    {
        $validateData = Validator::make(['itemId'=>$itemId], [
            'itemId'    => 'required|integer',
        ]);
        if ($validateData->fails()) {
            return Session::flash('error','Girilen bilgiler hatalı.');
        }
        $this->clearLocationData();
        $this->operation = 'asignNewLocation';
        $this->selectedItemID = $itemId;
        $this->selectedItem = ErpItemsWarehouses::where('warehouse_id',$this->warehouseID)->where('item_id',$this->selectedItemID)->get();
        if(count($this->selectedItem) == 1){
            $this->selectedItem = $this->selectedItem->first();
        }else{
            return Session::flash('error','Odada çoklanan ürün hatası, Sistem yöneticisi ile iletişime geçiniz.');
        }
    }

    public function editExistingLocation($locationID,$itemId){
        $this->selectedItemID = $itemId;
        $this->selectedLocationData = ErpItemsLocation::find($locationID);
        if (isset($this->selectedLocationData)) {
            $this->p1 = $this->selectedLocationData->p1;
            $this->p2 = $this->selectedLocationData->p2;
            $this->p3 = $this->selectedLocationData->p3;
        }else{
            return Session::flash('error','Hatalı veri.');
        }
    }

    public function asignLocation(){
        $validateData = Validator::make(['p1'=>$this->p1,'p2'=>$this->p2,'p3'=>$this->p3], [
            'p1'    => 'nullable|string',
            'p2'    => 'nullable|string',
            'p3'    => 'nullable|string',
        ]);
        if ($validateData->fails()) {
            return Session::flash('error','Girilen bilgiler hatalı.');
        }
        if($this->operation == 'asignNewLocation'){
            $this->saveNewLocation();
        }else{
            $this->editLocation();
        }
        
        $this->clearLocationData();
    }

    private function saveNewLocation(){
        $newLocation = new ErpItemsLocation;
        $newLocation->item_id = $this->selectedItemID;
        $newLocation->warehouse_id = $this->warehouseID;
        $newLocation->p1 = $this->p1;
        $newLocation->p2 = $this->p2;
        $newLocation->p3 = $this->p3;
        $newLocation->save();
        $this->clearLocationData();
        $this->operation = null;
    }
    
    private function editLocation(){
        $this->selectedLocationData->p1 = $this->p1;
        $this->selectedLocationData->p2 = $this->p2;
        $this->selectedLocationData->p3 = $this->p3;
        $this->selectedLocationData->save();
        $this->clearLocationData();
        return Session::flash('success','Raf Değiştirildi.');
    }

    public function deleteLocation($locationID){
        ErpItemsLocation::find($locationID)->delete();

        return Session::flash('success','Raf Silindi.');
    }
    
    private function clearLocationData(){
        $this->p1 = null;
        $this->p2 = null;
        $this->p3 = null;
        $this->selectedItemID = null;
        return;
    }

}
