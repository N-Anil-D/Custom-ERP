<?php

namespace App\Http\Livewire\Erp\StockTaking;

use Livewire\{Component, WithPagination};
use App\Models\Erp\Item\ErpItem;
use App\Models\Erp\StockTaking\ErpStockTaking;
use App\Models\Erp\Warehouse\ErpWarehouse;
use Illuminate\Support\Facades\{Validator, Session, Auth};

class StockTaking extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    const model = 'stockTaking';

    public $form = [
        'item_id'       => null,
        'warehouse_id'  => null,
    ];
    public $rowId;
    public $action;

    public function render()
    {
        return view('livewire.erp.stock-taking.stock-taking', [
            'items'         => ErpItem::get(),
            'warehouses'    => ErpWarehouse::get(),
            'data'          => ErpStockTaking::with('item', 'warehouse', 'countingUser')->where('status', 0)->paginate(50),
        ]);
    }

    public function addStockTaking()
    {
        
        $validateData = Validator::make($this->form, [
            'item_id'           => 'required',
            'warehouse_id'      => 'required',
            'amount'            => 'required|numeric'
        ])->validate();
            
        ErpStockTaking::updateOrInsert(
            [
                'item_id'       => $this->form['item_id'],
                'warehouse_id'  => $this->form['warehouse_id'],
                'status'        => 0,
                'counting_user' => Auth::user()->id,
            ],
            [
                'amount'        => $this->form['amount'],
                'deleted_at'    => NULL
                ]
            );

        // $this->dispatchBrowserEvent('refresh-page');
        Session::flash('success', 'Sayım değeri girildi/güncellendi');
                
    
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

    public function confirm()
    {
        if(ErpStockTaking::where('status',1)->count() == 0){
            ErpStockTaking::where('status', 0)->where('counting_user', Auth::user()->id)
                ->update([
                    'status' => 1
                ]);

            Session::flash('success', 'Sayım verileriniz onaya gönderildi');
        }else{
            Session::flash('error', 'Daha önce onaya gönderilmiş ve bekleyen bir sayım işlemi bulunamakta.');
        }

        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

}
