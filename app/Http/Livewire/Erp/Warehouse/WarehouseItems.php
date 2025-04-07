<?php

namespace App\Http\Livewire\Erp\Warehouse;

use Livewire\{Component, WithPagination};
use App\Models\User;
use App\Models\Erp\Warehouse\{ErpWarehouse, ErpStockMovement, ErpUsersWarehouses};
use App\Models\Erp\Item\{ErpItemsWarehouses, ErpItem};
use App\Models\Erp\ErpApproval;
use Illuminate\Support\Facades\{Auth, Validator, Session};
use NotificationChannels\Telegram\TelegramMessage;

class WarehouseItems extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $warehouseId=null;
    public $currentWarehouseId=null;
    public $warehouseData;
    public $warehouseButtonChange;
    public $rowId;
    public $movements;
    public $item;
    public $search;
    public $transferRequest = [];
    public $transferAbleWarehouses;
    public $setDemandData;
    const model = 'itemsInWarehouses';

    public function render()
    {
        $warehouseItems = ErpItemsWarehouses::where('warehouse_id',$this->currentWarehouseId)->pluck('item_id');
        $itemsInWarehouse = ErpItem::whereIn('id',$warehouseItems);
        return view('livewire.erp.warehouse.warehouse-items',
            [
                'warehouses'=>ErpWarehouse::all(),
                'warehouseItems'=>$itemsInWarehouse->where(function($q){
                    $q->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
                })
                ->paginate(20),
            ]
        );
    }

    public function wareHouseSelect($id){
        $this->search = null;
        $this->warehouseButtonChange = $id;
        $this->currentWarehouseId = $id;
        $this->warehouseData = ErpWarehouse::find($id);
    }

    public function process($rowId, $action)
    {
        $this->rowId    = $rowId;
        
        if($action == 'movement'){
            $this->movements = ErpStockMovement::with('getDwindlingWarehouse', 'getIncreasedWarehouse', 'item', 'getSender', 'getApproval')
                ->where('item_id', $rowId)
                ->orderByDesc('created_at')
                ->get();
            $this->item = ErpItem::find($rowId);
            $this->dispatchBrowserEvent(self::model.'movementmodalShow');

        }elseif($action == 'warehouse'){

            $this->item = ErpItem::with('stocks')->find($rowId);
            $this->dispatchBrowserEvent(self::model.'warehousemodalShow');

        }elseif($action == 'demand'){

            if($this->calcNotifyCount() == 0) {

                $this->warehouseId = null;
                $this->transferRequest = [
                    'amount'                        => null,
                    'itemDemandedFromThisWarehouse' => null,
                    'itemDemandedToThisWarehouse'   => null,
                    'content'                       => null,
                ];
                $this->transferAbleWarehouses = null;
                
                $this->transferAbleWarehouses = ErpItemsWarehouses::with('warehouse')
                    ->where('item_id',$this->rowId)
                    ->where('amount','>',0)
                    ->whereNotIn('warehouse_id', Auth::user()->warehouses->pluck('warehouse_id'))
                    ->get();
                $this->setDemandData = ErpItem::find($this->rowId);
                $this->dispatchBrowserEvent(self::model.'demandItemModalShow');

            }

        }
    }

    public function demandItem(){
        $validateData = Validator::make($this->transferRequest, [
            'amount'                        => 'required',
            'itemDemandedFromThisWarehouse' => 'required',
            'itemDemandedToThisWarehouse'   => 'required',
            'content'                       => 'nullable|string',
        ])->validate();
        
        $itemInWarehouseAmount = ErpItemsWarehouses::where('item_id',$this->rowId)->where('warehouse_id',$validateData['itemDemandedFromThisWarehouse'])->first()->amount;
        
        if($itemInWarehouseAmount < $validateData['amount']){
            $this->dispatchBrowserEvent(self::model.'demandItemModalHide');
            Session::flash('error','Başarısız. Talep edilen ürün miktarı depodaki miktardan fazla.');
        }else{
            $newApproval = new ErpApproval;        
            $newApproval->amount                    = $validateData['amount'];
            $newApproval->item_id                   = $this->rowId;
            $newApproval->content                   = $validateData['content'];
            $newApproval->type                      = 5;
            $newApproval->sender_user               = Auth::user()->id;
            $newApproval->increased_warehouse_id    = $validateData['itemDemandedToThisWarehouse'];
            $newApproval->dwindling_warehouse_id    = $validateData['itemDemandedFromThisWarehouse'];
            $newApproval->save();

            $sendTelegramMessage = User::whereIn('id',ErpUsersWarehouses::where('warehouse_id', $newApproval->dwindling_warehouse_id)->pluck('user_id'))->where('active',1)->pluck('telegram_id');
            foreach($sendTelegramMessage as $userTelegramID){
                try {
                    TelegramMessage::create()
                        ->to($userTelegramID)
                        ->line('*#'.$newApproval->id.' NO. LU KAYIT [TRANSFER TALEBİ] ONAYINIZI BEKLİYOR*')
                        ->line('')
                        ->line('Ürün kodu : '. $newApproval->getItem?->code)
                        ->line('Ürün adı : '. $newApproval->getItem?->name)
                        ->line('Miktar : '. $newApproval->amount.' '.$newApproval->getItem?->itemToUnit->content)
                        ->line('Hareket yönü : '. $newApproval->getDwindlingWarehouse->name .'->'.$newApproval->getIncreasedWarehouse->name)
                        ->line('')
                        ->line('Talebi açan : '. Auth::user()->name)
                        ->line('Onaylamak için :')
                        ->line(url('/bildirimlerim'))
                        ->line('adresini ziyaret ediniz.')
                        ->send();
                } catch (\Throwable $th) {
                    //throw $th;
                }

                $newApproval->notify++;
                $newApproval->save(); 
            }

            // depo sahiplerine bildirim gönderilecek 

            $this->dispatchBrowserEvent(self::model.'demandItemModalHide');
            $this->emit('updateNotifications');
            Session::flash('success','Ürün talebi başarıyla oluşturuldu.');
        }

    }

    public function calcNotifyCount()
    {
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
}
