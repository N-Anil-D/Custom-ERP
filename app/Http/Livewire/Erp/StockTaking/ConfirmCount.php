<?php

namespace App\Http\Livewire\Erp\StockTaking;

use App\Models\Erp\Item\ErpItemsWarehouses;
use Livewire\{Component, WithPagination};
use App\Models\Erp\StockTaking\ErpStockTaking;
use App\Models\Erp\Warehouse\ErpStockMovement;
use Illuminate\Support\Facades\{Session, Auth};

class ConfirmCount extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    const model = 'confirmCount';

    public $rowId;
    public $action;

    public function render()
    {
        return view('livewire.erp.stock-taking.confirm-count', [
            'data' => ErpStockTaking::with('item', 'warehouse', 'countingUser')->where('status', 1)->paginate(50)
        ]);
    }

    public function process($rowId, $action)
    {
        $this->rowId    = $rowId;
        $this->action   = $action;

        $this->dispatchBrowserEvent(self::model.$this->action.'modalShow');
    }

    public function delete()
    {
        ErpStockTaking::find($this->rowId)->delete();
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function cancel()
    {
        ErpStockTaking::where('status', 1)->update([
            'status' => 3,
            'deleted_at' => now(),
            'approver_user' => Auth::user()->id
        ]);

        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
        Session::flash('success', 'Listedeki sayım verisi iptal edildi ve kaldırıldı');

    }

    public function confirmByRow()
    {     
        $stock = ErpStockTaking::find($this->rowId);
        $userId = Auth::user()->id;
        # sayım verisi durumu onayladın olarak işaretlenecek
        $stock->update([
            'status'        => 2,
            'approver_user' => Auth::user()->id
        ]);
        
        if($stock->item){
            # erp_stock_movements tablosuna veriler işlenecek {type = 4 olarak}
            ErpStockMovement::create([
                'item_id'                   => $stock->item_id,
                'type'                      => 4,
                'content'                   => now().' tarihli stok sayımı',
                'increased_warehouse_id'    => $stock->warehouse_id,
                'dwindling_warehouse_id'    => $stock->warehouse_id,
                'sender_user'               => $stock->counting_user,
                'approval_user'             => $userId,
                'amount'                    => $stock->amount,
                'old_warehouse_amount'      => ($stock->item->stock($stock->warehouse_id) == NULL) ? 0 : $stock->item->stock($stock->warehouse_id)->amount,
                'old_total_amount'          => $stock->item->stocks->sum('amount'),
            ]);

            # erp_items_warehouses verisi stok verisine göre update edilecek 
            ErpItemsWarehouses::updateOrInsert(
                [
                    'item_id'       => $stock->item_id,
                    'warehouse_id'  => $stock->warehouse_id,
                ],
                [
                    'amount'        => $stock->amount
                ]
            );
            Session::flash('success', 'Stok verisi onaylandı ve işlendi');
        }else{
            $stock->update([
                'status'        => 3,
                'approver_user' => Auth::user()->id
            ]);
            Session::flash('error', 'Ürün sistemde bulunamadı. Sayım ipral edildi.');
        }
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function confirmAll()
    {
        $stocks = ErpStockTaking::where('status', 1)->get();
        $userId = Auth::user()->id;

        # sayım verisi durumu onayladın olarak işaretlenecek
        ErpStockTaking::where('status', 1)->update([
            'status'        => 2,
            'approver_user' => $userId,
        ]);
        foreach($stocks as $stock){
            if($stock->item){
                # erp_stock_movements tablosuna veriler işlenecek {type = 4 olarak}
                ErpStockMovement::create([
                    'item_id'                   => $stock->item_id,
                    'type'                      => 4,
                    'content'                   => now().' tarihli stok sayımı',
                    'increased_warehouse_id'    => $stock->warehouse_id,
                    'dwindling_warehouse_id'    => $stock->warehouse_id,
                    'sender_user'               => $stock->counting_user,
                    'approval_user'             => $userId,
                    'amount'                    => $stock->amount,
                    'old_warehouse_amount'      => ($stock->item->stock($stock->warehouse_id) == NULL) ? 0 : $stock->item->stock($stock->warehouse_id)->amount,
                    'old_total_amount'          => $stock->item->stocks->sum('amount'),
                ]);


                # erp_items_warehouses verisi stok verisine göre update edilecek 
                ErpItemsWarehouses::updateOrInsert(
                    [
                        'item_id'       => $stock->item_id,
                        'warehouse_id'  => $stock->warehouse_id,
                    ],
                    [
                        'amount'        => $stock->amount
                    ]
                );
            }else{
                ErpStockTaking::find($stock->id)->update([
                    'status'        => 3,
                ]);
            }
        }
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
        Session::flash('success', 'Listedeki tüm stok verileri onaylandı ve işlendi');

    }
}
