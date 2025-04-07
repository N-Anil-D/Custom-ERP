<?php

namespace App\Http\Livewire\Erp\Warehouse;

use App\Models\Erp\Warehouse\{ErpWarehouse, ErpSendProducts};
use Illuminate\Support\Facades\{Validator, Session, Storage, Auth};
use Livewire\{Component, WithPagination, WithFileUploads};

class SendProducts extends Component
{

    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    const model = 'sendProducts';

    public $search;
    public $itemId;
    public $sendProduct;
    public $sendProductModalData = [];

    public function render()
    {
        return view('livewire.erp.warehouse.send-products', [
            'data' => ErpSendProducts::with('item','warehouse')->whereNotIn('status', [0,3])->where('lot_no', 'like', '%'.$this->search.'%')->orderByDesc('updated_at')->orderByDesc('send_date')->paginate(20),
            'warehouses' => ErpWarehouse::get(),
        ]);
    }

    public function takeBackModal($sendProductId)
    {
        $this->sendProduct = ErpSendProducts::find($sendProductId);
        $this->sendProductModalData = [
            'amount' => null,
        ];
        $this->dispatchBrowserEvent(self::model.'takeBackModalShow');
    }

    public function sendAgainModal($sendProductId)
    {
        $this->sendProduct = ErpSendProducts::find($sendProductId);
        $this->sendProductModalData = [
            'amount' => $this->sendProduct->amount,
            'send_to' => $this->sendProduct->send_to,
            'send_date' => $this->sendProduct->send_date,
        ];
        $this->dispatchBrowserEvent(self::model.'sendAgainModalShow');
    }

    public function takeBack()
    {
        $validateData = Validator::make($this->sendProductModalData, [
            'amount'                    => 'required|numeric',
        ])->validate();

        
        if($this->sendProduct->amount == $validateData['amount']){
            $this->sendProduct->user_id = Auth::user()->id;
            $this->sendProduct->status = 2;
            $this->sendProduct->save();
        }else{
            $isThereAnyTakenBack = ErpSendProducts::where('lot_no', $this->sendProduct->lot_no)->where('fatura_irsaliye_no', $this->sendProduct->fatura_irsaliye_no)->where('status',2)->first();
            if($isThereAnyTakenBack){
                $isThereAnyTakenBack->amount = $isThereAnyTakenBack->amount + $validateData['amount'];
                $isThereAnyTakenBack->save();
            }else{
                $productsThatPulledBack = new ErpSendProducts;
                $productsThatPulledBack->item_id            = $this->sendProduct->item_id;
                $productsThatPulledBack->user_id            = Auth::user()->id;
                $productsThatPulledBack->last_warehouse_id  = $this->sendProduct->last_warehouse_id;
                $productsThatPulledBack->lot_no             = $this->sendProduct->lot_no;
                $productsThatPulledBack->fatura_irsaliye_no = $this->sendProduct->fatura_irsaliye_no;
                $productsThatPulledBack->amount             = $validateData['amount'];
                $productsThatPulledBack->send_to            = $this->sendProduct->send_to;
                $productsThatPulledBack->status             = 2;
                $productsThatPulledBack->send_date          = $this->sendProduct->send_date;
                $productsThatPulledBack->save();
            }
            $this->sendProduct->amount = $this->sendProduct->amount - $validateData['amount'];
            $this->sendProduct->save();
        }
        Session::flash('success', 'Geri çekme işlemi başarılı.');
        $this->dispatchBrowserEvent(self::model.'takeBackModalHide');
    }

    public function sendAgain()
    {
        $validateData = Validator::make($this->sendProductModalData, [
            'amount'                    => 'required|numeric',
            'send_to'                   => 'required',
            'send_date'                 => 'required',
        ])->validate();
        
        $takenBackRecord = ErpSendProducts::where('lot_no', $this->sendProduct->lot_no)->where('fatura_irsaliye_no', $this->sendProduct->fatura_irsaliye_no)->where('status',1)->where('send_to',$validateData['send_to'])->orderBy('updated_at')->first();
        if($takenBackRecord){
            $takenBackRecord->amount = $takenBackRecord->amount + $validateData['amount'];
            $takenBackRecord->send_date = $validateData['send_date'];
            $takenBackRecord->save();
            if($this->sendProduct->amount == $validateData['amount']){
                $this->sendProduct->delete();
            }else{
                $this->sendProduct->amount = $this->sendProduct->amount - $validateData['amount'];
                $this->sendProduct->save();
            }
        }else{
            if($this->sendProduct->amount == $validateData['amount']){
                    $this->sendProduct->user_id = Auth::user()->id;
                    $this->sendProduct->status = 1;
                    $this->sendProduct->send_date = $validateData['send_date'];
                    $this->sendProduct->send_to = $validateData['send_to'];
                    $this->sendProduct->save();
                }else{
                    $this->sendProduct->amount = $this->sendProduct->amount - $validateData['amount'];
                    $this->sendProduct->save();
                    $productsThatPulledBack = new ErpSendProducts;
                    $productsThatPulledBack->item_id = $this->sendProduct->item_id;
                    $productsThatPulledBack->user_id = Auth::user()->id;
                    $productsThatPulledBack->last_warehouse_id = $this->sendProduct->last_warehouse_id;
                    $productsThatPulledBack->lot_no = $this->sendProduct->lot_no;
                    $productsThatPulledBack->amount = $validateData['amount'];
                    $productsThatPulledBack->send_to = $validateData['send_to'];
                    $productsThatPulledBack->status = 1;
                    $productsThatPulledBack->send_date = $validateData['send_date'];
                    $productsThatPulledBack->save();
            }
        }
        Session::flash('success', 'Geri çekilmiş ürün başarı ile tekrar gönderildi.');
        $this->dispatchBrowserEvent(self::model.'sendAgainModalHide');
    }

}