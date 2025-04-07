<?php

namespace App\Http\Livewire\Erp\Warehouse;

use App\Models\Erp\Warehouse\{ErpWarehouse, ErpFinishedProduct, ErpSendProducts};
use App\Models\Erp\Item\{ErpItemsLocation};
use App\Models\Erp\ErpApproval;
use App\Models\User;
use Illuminate\Support\Facades\{Validator, Session, Storage, Auth};
use Livewire\{Component, WithPagination, WithFileUploads};
use NotificationChannels\Telegram\TelegramMessage;

class FinishedProducts extends Component
{

    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    const model = 'finishedProducts';

    public $operation;
    public $search;
    public $rowId;
    public $itemId;
    public $selectedItemID;
    public $sellFinishedProduct;
    public $finishedProductID;
    public $p1;
    public $p2;
    public $p3;

    public function render()
    {   
        return view('livewire.erp.warehouse.finished-products', [
            'data' => ErpFinishedProduct::with('item','warehouse')->where('lot_no', 'like', '%'.$this->search.'%')->orderByDesc('send_date')->orderByDesc('created_at')->paginate(20),
            'warehouses' => ErpWarehouse::get(),
        ]);
    }

    public function calcNotifyCount(){
        $notifyCount = 0;

        $requested = ErpApproval::where('status', 0)
                ->where('type', 5)
                ->whereIn('dwindling_warehouse_id', Auth::user()->warehouses->pluck('warehouse_id'))
                ->count();
        
        $notifyCount = Auth::user()->pendingApprovals->count() + $requested;

        if(Auth::user()->confirm_buy) {
            $notifyCount += ErpApproval::where('status', 0)->where('type', 3)->count();
        }

        return $notifyCount;
    }

    public function exitRequest(){
        $user = Auth::user();
        $validateData = Validator::make($this->exitRequest, [
            'amount'                => 'required',
            'lot'                   => 'required',
            'send_to'               => 'required',
            'send_date'             => 'required',
            'fatura_irsaliye_no'    => 'required',
            'content'               => 'nullable',
        ])->validate();

        $sold = new ErpSendProducts;
        $sold->user_id = $user->id;
        $sold->last_warehouse_id = $this->warehouseId;
        $sold->item_id = $this->itemId;
        $sold->lot_no = $validateData['lot'];
        $sold->fatura_irsaliye_no = $validateData['fatura_irsaliye_no'];
        $sold->amount = $validateData['amount'];
        $sold->send_to = $validateData['send_to'];
        $sold->status = 0;
        $sold->send_date = date("Y-m-d");
        $sold->save();

        $approval = ErpApproval::create([
            'item_id'                   => $this->itemId,
            'content'                   => $sold->id,
            'type'                      => 3,
            'status'                    => 0,
            'sender_user'               => $user->id,
            'dwindling_warehouse_id'    => $this->warehouseId,
            'increased_warehouse_id'    => 0,
            'amount'                    => $validateData['amount'],
            'lot_no'                    => $this->sellFinishedProduct->lot_no,
        ]);

        $updateErpFinishedProduct = ErpFinishedProduct::find($this->finishedProductID);
        if($updateErpFinishedProduct->amount == $validateData['amount']){
            $updateErpFinishedProduct->delete();
        }else{
            $updateErpFinishedProduct->update(['amount' => $updateErpFinishedProduct->amount - $validateData['amount']]);
        }
        
        # notification
        $confirmByUser = User::where('confirm_exit', 1)->where('active', 1)->get();
        
        foreach($confirmByUser as $user){
            if($user->telegram_id){
                #send notif
                try {
                    $telegram = TelegramMessage::create()
                        ->to($user->telegram_id)
                        ->line('*#'.$approval->id.' NO. LU KAYIT [ÜRÜN SATIŞI] ONAYINIZI BEKLİYOR*')
                        ->line('')
                        ->line('Ürün kodu : '. $approval->getItem->code)
                        ->line('Ürün adı : '. $approval->getItem->name)
                        ->line('Miktar : '. $approval->amount.' '.$approval->getItem->itemToUnit->content)
                        ->line('Çıkış yapılan kodu : ' .$approval->getDwindlingWarehouse->code)
                        ->line('Çıkış yapılan depo adı : '.$approval->getDwindlingWarehouse->name)
                        ->line('')
                        ->line('Onaylamak için :')
                        ->line(url('/bildirimlerim'))
                        ->line('adresini ziyaret ediniz.')
                        ->send();
                } catch (\Throwable $th) {
                    //throw $th;
                }
                if($telegram){
                    #update approval
                    $approval->update(['notify' => $approval->notify+1]);
                }
            }
        }
        Session::flash('success', 'Çıkış talebiniz alındı'); 
        $this->emit('updateNotifications');
        $this->dispatchBrowserEvent(self::model.'openExitModalHide');
    }

    public function openExitModal($warehouseId, $finishedProductId){
        if($this->calcNotifyCount() == 0){
            $this->exitRequest = [];
            $this->finishedProductID = $finishedProductId;
            $this->warehouseId = $warehouseId;
            $this->sellFinishedProduct = ErpFinishedProduct::find($finishedProductId);
            $this->itemId = $this->sellFinishedProduct->item_id;
            $this->dispatchBrowserEvent(self::model.'openExitModalShow');
        }else{
            Session::flash('error', trans('site.general.doTheApprovalsFirst'));
        }
    }

    public function asignNewLocationData($rowId,$itemId){
        $validateData = Validator::make(['itemId'=>$itemId], [
            'itemId'    => 'required|integer',
        ]);
        if ($validateData->fails()) {
            return Session::flash('error','Girilen bilgiler hatalı. Sayfayı yenileyiniz.');
        }
        $this->clearLocationData();
        $this->operation = 'asignNewLocation';
        $this->selectedItemID = $itemId;
        $this->rowId = $rowId;
        // dd($this->operation ,$this->selectedItemID,$this->rowId);
        return;
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

    public function editExistingLocation($locationID,$rowId){
        $this->rowId = $rowId;
        $this->selectedLocationData = ErpItemsLocation::find($locationID);
        if (isset($this->selectedLocationData)) {
            $this->p1 = $this->selectedLocationData->p1;
            $this->p2 = $this->selectedLocationData->p2;
            $this->p3 = $this->selectedLocationData->p3;
        }else{
            return Session::flash('error','Hatalı veri.');
        }
    }

    private function clearLocationData(){
        $this->p1 = null;
        $this->p2 = null;
        $this->p3 = null;
        $this->selectedItemID = null;
        $this->rowId = null;
        $this->operation = null;
        return;
    }

    private function saveNewLocation(){
        $newLocation = new ErpItemsLocation;
        $newLocation->item_id = $this->selectedItemID;
        $newLocation->warehouse_id = 99;
        $newLocation->p1 = $this->p1;
        $newLocation->p2 = $this->p2;
        $newLocation->p3 = $this->p3;
        $newLocation->save();
        $this->clearLocationData();
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

}