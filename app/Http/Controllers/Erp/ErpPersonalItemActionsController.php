<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Session, Auth, Validator};
use Illuminate\Http\Request;
use App\Models\Erp\ErpApproval;
use App\Models\Erp\Warehouse\{ErpProduction, ErpWarehouse};
use App\Models\Erp\Item\ErpItem;
use App\Exports\Erp\{ProductionReportExport,WarehouseTransferExport};
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\{Carbon,Arr};

class ErpPersonalItemActionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:erp-kisisel-islemlerim');
    }

    public function personalItemAction()
    {
        $title = 'Tüm ürünler & işlemlerim';
        return view('erp.personal-item-action', compact('title'));
    }

    public function allWarehouseItems()
    {
        $title = 'Odalardaki Ürünler';
        return view('erp.warehouse.warehouse-items', compact('title'));
    }

    public function productionReportDownload(Request $request){
        $validateData = Validator::make($request->all(), [
            'start' => 'required',
            'end'   => 'required',
        ])->validate();
        $startDate = Carbon::parse($validateData['start'])->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($validateData['end'])->endOfDay()->format('Y-m-d H:i:s');
        if($request->type === 'personal'){
            $data = ErpProduction::where('user_id',Auth::user()->id)->whereBetween('updated_at',[$startDate,$endDate])->pluck('id')->toArray();
            $fileName = Auth::user()->name.'-Üretim Raporu-'.Carbon::now()->locale('tr')->format('Y-m-d_H-m').'.xlsx';
            return Excel::download(new ProductionReportExport($data), $fileName);
        }elseif($request->type === 'item'){
            $data = ErpProduction::where('item_id',$request->itemId)->whereBetween('updated_at',[$startDate,$endDate])->pluck('id')->toArray();
            $itemName = preg_replace('/[^A-Za-z0-9\-ÜİÇÖĞIığüşöç]/', '-', ErpItem::find($request->itemId)?->name);
            $fileName = $itemName.'-Üretim Raporu-'.Carbon::now()->locale('tr')->format('Y-m-d_H-m').'.xlsx';
            return Excel::download(new ProductionReportExport($data), $fileName);
        }elseif($request->type === 'warehouse'){
            $data = ErpProduction::where('warehouse_id',$request->warehouseId)->whereBetween('updated_at',[$startDate,$endDate])->pluck('id')->toArray();
            $warehouseName = preg_replace('/[^A-Za-z0-9\-ÜİÇÖĞIığüşöç]/', '-', ErpWarehouse::find($request->warehouseId)?->name);
            $fileName = $warehouseName.'-Üretim Raporu-'.Carbon::now()->locale('tr')->format('Y-m-d_H-m').'.xlsx';
            return Excel::download(new ProductionReportExport($data), $fileName);
        }elseif($request->type === 'all'){
            $data = ErpProduction::whereBetween('updated_at',[$startDate,$endDate])->pluck('id')->toArray();
            $fileName = 'Fabrika Üretim Raporu-['.$startDate.'---'.$endDate.'].xlsx';
            return Excel::download(new ProductionReportExport($data), $fileName);
        }elseif($request->type === 'transfer'){
            if($request->warehouseId == 0){
                $warehouseIdArray = ErpWarehouse::get()->pluck('id');
            }else{
                $warehouseIdArray = [$request->warehouseId];
            }
            //incdecb => increase decrease or both [decrease = 1, increase = 2, both = 3]
            if($request->incdecb == 1){
                $data = ErpApproval::whereIn('dwindling_warehouse_id',$warehouseIdArray)->whereIn('type',[0,5])->whereBetween('created_at',[$startDate,$endDate])->pluck('id')->toArray();
            }elseif($request->incdecb == 2){
                $data = ErpApproval::whereIn('increased_warehouse_id',$warehouseIdArray)->whereIn('type',[0,5])->whereBetween('created_at',[$startDate,$endDate])->pluck('id')->toArray();
            }elseif($request->incdecb == 3){
                $mergeData1 = ErpApproval::whereBetween('created_at',[$startDate,$endDate])->whereIn('type',[0,5])->whereIn('increased_warehouse_id',$warehouseIdArray)->pluck('id')->toArray();
                $mergeData2 = ErpApproval::whereBetween('created_at',[$startDate,$endDate])->whereIn('type',[0,5])->whereIn('dwindling_warehouse_id',$warehouseIdArray)->pluck('id')->toArray();
                $data = array_unique(Arr::collapse([$mergeData1,$mergeData2]));
            }else{
                return Session::flash('error', 'Girilen bilgiler hatalı.'); 
            }
            $fileName = 'Transfer Raporu-['.$startDate.'---'.$endDate.'].xlsx';
            return Excel::download(new WarehouseTransferExport($data), $fileName);
        }
    }
    
    public function productionReports()
    {
        $title = 'Üretim Raporları';
        return view('erp.productionReports', compact('title'));
    }
}
