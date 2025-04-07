<?php

namespace App\Http\Livewire\Erp\Item;

use Livewire\{Component, WithPagination};
use App\Models\Erp\ErpApproval;
use App\Models\Erp\Item\{ErpItem, ErpItemsWarehouses, ErpUnit, ErpItemsLocation,ErpItemsPersonalActions,ErpItemsPrices,ErpItemVariety};
use App\Models\Erp\EndProduct\ErpSekk;
use App\Models\Erp\StockTaking\ErpStockTaking;
use App\Models\Erp\Warehouse\{ErpWarehouse,ErpFinishedProduct,ErpProduction,ErpProductionContent,ErpProductionRecipe,ErpSendProducts,ErpStockMovement,ErpUsersWarehouseNotes};
use App\Exports\Erp\SystemDataExport;
use Illuminate\Support\Facades\{Validator, Session};
use Maatwebsite\Excel\Facades\Excel;


class Items extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    const model = 'items';

    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    public $search;

    public function render()
    {
        return view('livewire.erp.item.items', [
            'units' => ErpUnit::all(),
            'data' => ErpItem::with('itemToUnit')->where('code', 'like', '%' . $this->search . '%')
                ->orWhere('id', 'like', '%' . $this->search . '%')
                ->orWhere('name', 'like', '%' . $this->search . '%')
                ->orWhere('content', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%')
                ->orWhere('created_at', 'like', '%' . $this->search . '%')
                ->orWhere('updated_at', 'like', '%' . $this->search . '%')
                // ->orWhereRelation('itemToUnit', 'content', 'like', '%'. $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate(20),
            'warehouses' => ErpWarehouse::all(),
            'varieties' => ErpItemVariety::all(),
        ]);
    }

    public function process($rowId, $action)
    {
        $this->rowId    = $rowId;
        $this->action   = $action;

        if ($action == 'delete') {
            $this->dispatchBrowserEvent(self::model.$this->action.'modalShow');
        } else if ($action == 'insert'){
            $this->clearItem();
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');
        } else if ($action == 'update') {
            $this->selectedModelData = ErpItem::find($this->rowId);
            $this->selectedArrayData = [
                'code'      => $this->selectedModelData->code,
                'name'      => $this->selectedModelData->name,
                'content'   => $this->selectedModelData->content,
                'unit_id'   => $this->selectedModelData->unit_id,
                'type'      => $this->selectedModelData->type,
                'barcode'   => $this->selectedModelData->barcode,
                'variety_id'=> $this->selectedModelData->variety_id,
            ];
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');

        }
    }

    public function upsert()
    {
        $validateData = $this->validateData();
        if ($this->action == 'insert') {
            $item = ErpItem::create($validateData);
            ErpItemsWarehouses::create([
                'item_id'       => $item->id,
                'warehouse_id'  => $validateData['warehouse'],
                'amount'        => 0,
            ]);
        } else if($this->action == 'update'){
            $this->selectedModelData->update($validateData);
        }

        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.'upsertmodalHide');
    }

    public function clearItem()
    {
        $this->selectedArrayData = [
            'code'      => null,
            'name'      => null,
            'content'   => null,
            'unit_id'   => null,
            'type'      => null,
            'warehouse' => null,
            'barcode'   => null,
            'variety_id'=> null,
        ];
    }

    public function delete()
    {
        #DELETE items in other tables start
            $deleteErpItemsLocation = ErpItemsLocation::where('item_id',$this->rowId)->get();
            foreach ($deleteErpItemsLocation as $value) {
                $value->delete();
            }
            $deleteErpItemsPersonalActions = ErpItemsPersonalActions::where('item_id',$this->rowId)->get();
            foreach ($deleteErpItemsPersonalActions as $value) {
                $value->delete();
            }

            $deleteErpItemsPrices = ErpItemsPrices::where('item_id',$this->rowId)->get();
            foreach ($deleteErpItemsPrices as $value) {
                $value->delete();
            }

            $deleteErpItemsWarehouses = ErpItemsWarehouses::where('item_id',$this->rowId)->get();
            foreach ($deleteErpItemsWarehouses as $value) {
                $value->delete();
            }

            $deleteErpSekk = ErpSekk::where('item_id',$this->rowId)->get();
            foreach ($deleteErpSekk as $value) {
                $value->delete();
            }

            $deleteErpStockTaking = ErpStockTaking::where('item_id',$this->rowId)->get();
            foreach ($deleteErpStockTaking as $value) {
                $value->delete();
            }

            $deleteErpFinishedProduct = ErpFinishedProduct::where('item_id',$this->rowId)->get();
            foreach ($deleteErpFinishedProduct as $value) {
                $value->delete();
            }

            $deleteErpProduction = ErpProduction::where('item_id',$this->rowId)->get();
            foreach ($deleteErpProduction as $value) {
                $value->delete();
            }

            $deleteErpProductionContent = ErpProductionContent::orWhere('item_id',$this->rowId)->orWhere('main_item_id',$this->rowId)->get();
            foreach ($deleteErpProductionContent as $value) {
                $value->delete();
            }

            $deleteErpProductionRecipe = ErpProductionRecipe::where('item_id',$this->rowId)->get();
            foreach ($deleteErpProductionRecipe as $value) {
                $value->delete();
            }

            $deleteErpSendProducts = ErpSendProducts::where('item_id',$this->rowId)->get();
            foreach ($deleteErpSendProducts as $value) {
                $value->delete();
            }

            $deleteErpStockMovement = ErpStockMovement::where('item_id',$this->rowId)->get();
            foreach ($deleteErpStockMovement as $value) {
                $value->delete();
            }
            
            $deleteErpUsersWarehouseNotes = ErpUsersWarehouseNotes::where('item_id',$this->rowId)->get();
            foreach ($deleteErpUsersWarehouseNotes as $value) {
                $value->delete();
            }
            
            $deleteErpApproval = ErpApproval::where('item_id',$this->rowId)->get();
            foreach ($deleteErpApproval as $value) {
                $value->delete();
            }
        #DELETE items in other tables end
        #DELETE items
            ErpItem::find($this->rowId)->delete();
        #DELETE items
        
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function validateData()
    {
        if($this->action == 'insert'){
            $validateData = Validator::make($this->selectedArrayData, [
                'code'      => 'required|min:3|unique:erp_items,code',
                'name'      => 'required',
                'content'   => 'required',
                'unit_id'   => 'required|integer',
                'type'      => 'required|integer',
                'warehouse' => 'required|integer',
                'barcode'   => 'nullable',
                'variety_id'=> 'nullable',
            ])->validate();
        }else if($this->action == 'update'){
            $validateData = Validator::make($this->selectedArrayData, [
                // 'code'      => 'required|min:3|unique:erp_items,code', // ürün kendini kontrol ettiği için update işlemi yapılamıyor
                'code'      => 'required|min:3',
                'name'      => 'required',
                'content'   => 'required',
                'unit_id'   => 'required|integer',
                'type'      => 'required|integer',
                'barcode'   => 'nullable',
                'variety_id'=> 'nullable',
            ])->validate();
        }
        $validateData['code'] = \Illuminate\Support\Str::upper($validateData['code']);
        $isItemCodeActive = ErpItem::whereNot('id',$this->rowId)->where('code',$validateData['code'])->first();
        if ($isItemCodeActive) {
            # eğer aktif itemlerde sorun var ise validator hatası döndürmek adına tekrar validate işlemine girecek
            $validateData = Validator::make($this->selectedArrayData, [
                'code'      => 'required|min:3|unique:erp_items,code',
            ])->validate();
        }else {
            # eğer aktif itemlerde sorun yok ise validator işlemine girmeyecek
            return $validateData;
        }
    }

    public function systemExports(){
        $systemDataType = ErpItem::class;
        Session::flash('success', trans('site.general.download'));
        return Excel::download(new SystemDataExport($systemDataType),'Genel tanımlamalar-Urun.xlsx');
    }

    // public function addBarcode($rowId)
    // {
    //     $this->rowId = $rowId;
    //     // return view('livewire.erp.barcodes', ['rowId'=>$rowId]);
    //     $this->dispatchBrowserEvent(self::model.'barcodemodalShow');
    // }

}
