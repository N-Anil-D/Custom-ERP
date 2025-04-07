<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Erp\Item\{ErpItem, ErpItemsWarehouses};
use App\Models\Erp\Warehouse\ErpProduction;
use App\Models\Erp\Warehouse\ErpStockMovement;
use App\Models\Erp\Warehouse\ErpWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ErpWarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:erp-stok-islemleri');
    }

    public function myProducts()
    {
        $title = 'Ürünlerim';
        return view('erp.warehouse.my-products', compact('title'));
    }

    public function myProductions()
    {
        $title = 'Üretim işlemlerim';
        return view('erp.warehouse.my-productions', compact('title'));
    }

    public function createProduction($userId, $itemId, $warehouseId, $productionId)
    {
        if(Auth::user()->id == $userId){
            if(in_array($warehouseId, json_decode(Auth::user()->warehouses->pluck('warehouse_id')))){
                if(ErpItemsWarehouses::where('item_id', $itemId)->where('warehouse_id', $warehouseId)->count() > 0){
                    $item = ErpItem::find($itemId);
                    $warehouse = ErpWarehouse::find($warehouseId);
                    $production = ErpProduction::find($productionId);
                    $title = 'Üretim : '. $production->name .' | Ürün : '. $item->code.' | Depo : '. $warehouse->name;
                    return view('erp.warehouse.create-production', compact('title', 'item', 'warehouse', 'productionId'));
                }
            }
        }

        session()->flash('error', trans('site.accessDenied'));            
        return redirect()->route('index');
    }

    public function completeProduction(Request $request)
    {
        $production = ErpProduction::with('contents')->find($request->productionId);

        if($production->status == 0){

            if($production->contents->count() > 0){
                # status 1 e çekilecek
                $production->update(['status' => 1]);

                # erp_stock_movements değeri işlenecek
                ErpStockMovement::create([
                    'item_id'                   => $production->item_id,
                    'type'                      => 1, // üretim
                    'content'                   => $production->name,
                    'dwindling_warehouse_id'    => 0,
                    'increased_warehouse_id'    => $production->warehouse_id,
                    'sender_user'               => $production->user_id,
                    'approval_user'             => $production->user_id,
                    'amount'                    => $request->amount,
                    'old_warehouse_amount'      => $production->item->stock($production->warehouse_id)->amount,
                    'old_total_amount'          => $production->item->stocks->sum('amount'),
                ]);
            
                # erp_production amount değeri (+) olarak o depaya eklenecek
                $itemWarehouse = ErpItemsWarehouses::where('item_id', $production->item_id)
                    ->where('warehouse_id', $production->warehouse_id)
                    ->first();
                if($itemWarehouse){
                    $itemWarehouse->update([
                        'amount' => $itemWarehouse->amount + $request->amount
                    ]);
                }else{
                    ErpItemsWarehouses::create([
                        'item_id' => $production->item_id,
                        'warehouse_id' => $production->warehouse_id,
                        'amount' => $request->amount,
                    ]);
                }               
                
                foreach($production->contents as $productionItem){

                    # erp_stock_movements değerleri işlenecek
                    ErpStockMovement::create([
                        'item_id'                   => $productionItem->item_id,
                        'type'                      => 1, // üretim
                        'content'                   => $production->name,
                        'dwindling_warehouse_id'    => $productionItem->warehouse_id,
                        'increased_warehouse_id'    => 0,
                        'sender_user'               => $production->user_id,
                        'approval_user'             => $production->user_id,
                        'amount'                    => $productionItem->amount,
                        'old_warehouse_amount'      => $productionItem->item->stock($productionItem->warehouse_id)->amount,
                        'old_total_amount'          => $productionItem->item->stocks->sum('amount'),
                    ]);

                    # erp_production_content değerleri (-) olarak o depoya işlenecek
                    $itemWarehouse = ErpItemsWarehouses::where('item_id', $productionItem->item_id)
                        ->where('warehouse_id', $productionItem->warehouse_id)
                        ->first();
                    if($itemWarehouse){
                        $itemWarehouse->update([
                            'amount' => $itemWarehouse->amount - $productionItem->amount
                        ]);
                    }else{
                        ErpItemsWarehouses::create([
                            'item_id' => $productionItem->item_id,
                            'warehouse_id' => $productionItem->warehouse_id,
                            'amount' => $productionItem->amount,
                        ]);
                    }
                }

                session()->flash('success','üretim kaydı alındı');
                return redirect()->route('my.productions');

            }

            session()->flash('error', 'üretim kaydı oluşturulamadı. üretimde kullanılacak malzeme/malzemeler seçilmedi');
            return redirect()->back();

        }

        session()->flash('error', 'bu üretim daha öncesinde tamamlanmış bir üretimdir.');
        return redirect()->back();


    }

    public function workOrder(){
        // dd('Geliştirme Aşamasında. - NAD');
        $title = 'İş Emri';
        return view('erp.warehouse.work-order', compact('title'));
    }

}
