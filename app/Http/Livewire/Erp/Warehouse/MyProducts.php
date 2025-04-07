<?php

namespace App\Http\Livewire\Erp\Warehouse;

use App\Models\User;
use App\Models\View\Products;
use App\Models\Erp\ErpApproval;
use App\Models\Erp\Item\{ErpItem, ErpItemsWarehouses, ErpItemsPersonalActions};
use App\Models\Erp\Warehouse\{ErpStockMovement, ErpWarehouse, ErpUsersWarehouses, ErpUsersWarehouseNotes, ErpFinishedProduct};
use App\Models\Erp\EndProduct\ErpSekk;
use App\Exports\Erp\MyProductListExport;
use Illuminate\Support\Facades\{Validator, Session, Storage, Auth};
use Illuminate\Support\Str;
use NotificationChannels\Telegram\TelegramMessage;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\{Component, WithPagination, WithFileUploads};

class MyProducts extends Component
{

    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    const model = 'myproducts';

    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    public $demandWarehouseId;
    public $transferAbleUsers = [];
    public $transferAbleWarehouses;
    public $setDemandData;
    public $setAlertData;
    public $deleteAlertId;
    public $alertArray = [];
    public $movements = [];

    public $search;
    public $gteORgt = '>=';
    protected $queryString = ['search'];
    public $buyRequest = [];
    public $transferRequest = [];
    public $exitRequest = [];
    public $processRequest = [];
    public $finishProduct = [];
    public $cleanProductModalData = [];
    public $cleanProductWarehouseID;
    public $cleanProductMaxAmount;
    public $itemId;
    public $warehouseId;
    public $item;
    public $itemDistribution;
    public $noteItemId;
    public $noteWarehouseId;
    public $noteText;
    public $noteAddedItem;
    public $wtbFromUsers;
    public $wtbRequest;
    public $transferItemsBetweenMyWarehousesData;
    public $transferItemsBetweenMyWarehousesModalData = [];

    public function render()
    {
        // dd($this->onlyRoom8CanTransferTo23());
        return view('livewire.erp.warehouse.my-products', [
            'data' => Products::where('user_id', Auth::user()->id)->where(function ($q){
                $q->orWhere('erp_items_code', 'like', '%'.$this->search.'%')
                ->orWhere('erp_items_name', 'like', '%'.$this->search.'%')
                ->orWhere('erp_warehouses_name', 'like', '%'.$this->search.'%')
                ->orWhere('erp_items_warehouses_amount', 'like', '%'.$this->search.'%')
                ->orWhere('erp_units_content', 'like', '%'.$this->search.'%');
            })
            ->where('erp_items_warehouses_amount',$this->gteORgt,0)
            ->paginate(20),
            'warehouses' => $this->onlyRoom8CanTransferTo23(),
        ]);
    }
    
    public function onlyRoom8CanTransferTo23()
    {
        // Oda ID leri 8 ve 23 fakat collection 0 ile başladığı için kodda 7 ve 22 
        // 7 => Temiz Oda 7
        // 22 => Strerilizasyon Paketleme
        if(in_array(8,Auth::user()->warehouses->pluck('warehouse_id')->toArray()) || in_array(1,Auth::user()->warehouses->pluck('warehouse_id')->toArray())){
            return ErpWarehouse::get();
        }else{
            return ErpWarehouse::get()->reject(
                function($element,$key) {
                    return $key == 22;
                }
            );
        }
    }

    public function toggle_gteORgt()
    {
        if($this->gteORgt == '>='){
            $this->gteORgt = '>';
        }else{
            $this->gteORgt = '>=';
        }
    }

    public function openBuyModal($itemId)
    {
        $this->buyRequest = [
            'content'                   => null,
            'file'                      => null,
            'amount'                    => null,
            'increased_warehouse_id'    => null,
        ];
        $this->itemId = $itemId;
        $this->item = ErpItem::find($itemId);
        $this->dispatchBrowserEvent(self::model.'openbuymodalShow');
    }

    public function openBuyRequest()
    {       

        $validateData = Validator::make($this->buyRequest, [
            'content'                   => 'required',
            'file'                      => 'nullable|mimetypes:application/pdf,image/jpeg',
            'amount'                    => 'required',
            'increased_warehouse_id'    => 'required',
        ])->validate();

        if(is_null($validateData['file'])){
            $fileNameForSave = null;
        }else{
            $fileName = Str::random(11) . "_" .Str::kebab($validateData['file']->getClientOriginalName());
            Storage::putFileAs('fatura-gorselleri/', $validateData['file'], $fileName);
            $fileNameForSave = 'fatura-gorselleri/'.$fileName;
        }

        $approval = ErpApproval::create([
            'item_id' => $this->itemId,
            'content' => $validateData['content'],
            'file' => $fileNameForSave,
            'type' => 2,
            'sender_user' => Auth::user()->id,
            'amount' => $validateData['amount'],
            'increased_warehouse_id' => $validateData['increased_warehouse_id'],
        ]);

        # notification
        $confirmByUser = User::where('confirm_buy', 1)->where('active', 1)->get();
        
        foreach($confirmByUser as $user){
            if($user->telegram_id){
                #send notif
                try {
                    $telegram = TelegramMessage::create()
                        ->to($user->telegram_id)
                        ->line('*#'.$approval->id.' NO. LU KAYIT [ALIM İŞLEMİ] ONAYINIZI BEKLİYOR*')
                        ->line('')
                        ->line('Ürün kodu : '. $approval->getItem->code)
                        ->line('Ürün adı : '. $approval->getItem->name)
                        ->line('Miktar : '. $approval->amount.' '.$approval->getItem->itemToUnit->content)
                        ->line('Alım depo kodu : ' .$approval->getIncreasedWarehouse->code)
                        ->line('Alım depo adı : '.$approval->getIncreasedWarehouse->name)
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

        Session::flash('success', 'Alım talebiniz alındı'); 
        
        $this->emit('updateNotifications');
        $this->dispatchBrowserEvent(self::model.'openbuymodalHide');
        
    }

    public function openTransferModal($itemId, $warehouseId)
    {
        if($this->calcNotifyCount() == 0){
            $this->transferRequest = [];
            $this->itemId = $itemId;
            $this->warehouseId = $warehouseId;
            $this->item = ErpItem::find($itemId);
            $this->dispatchBrowserEvent(self::model.'openTransferModalShow');
        }else{
            Session::flash('error', trans('site.general.doTheApprovalsFirst'));
        }
    }

    public function openTransferRequest()
    {
        
        $user = Auth::user();
        
        $validateData = Validator::make($this->transferRequest, [
            'amount'                    => 'required',
            'increased_warehouse_id'    => 'required',
            'content'                   => 'required',
        ])->validate();
        $amountAtWarehouse = ErpItemsWarehouses::where('item_id',$this->itemId)->where('warehouse_id',$this->warehouseId)->first();

        if($validateData['amount'] > $amountAtWarehouse->amount){
            return Session::flash('error', 'Gönderilecek miktar deponun mevcut stoğundan fazla olamaz.'); 
        }
        if ($validateData['increased_warehouse_id'] === "out") {
            $item = ErpItem::find($this->itemId);
            ErpStockMovement::create([
                'item_id'                   => $this->itemId,
                'type'                      => 10,
                'content'                   => $validateData['content'],
                'dwindling_warehouse_id'    => $this->warehouseId,
                'sender_user'               => Auth::user()->id,
                'approval_user'             => Auth::user()->id,
                'amount'                    => $validateData['amount'],
                'old_warehouse_amount'      => ($item->stock($this->warehouseId) == NULL) ? 0 : $item->stock($this->warehouseId)->amount,
                'old_total_amount'          => $item->stocks->sum('amount'),
            ]);
            $amountAtWarehouse->amount -= $validateData['amount'];
            $amountAtWarehouse->save();

        }else{
            $approval = ErpApproval::create([
                'item_id'   => $this->itemId,
                'content'   => $validateData['content'],
                'type'      => 0,
                'status'    => 0,
                'sender_user' => $user->id,
                'dwindling_warehouse_id' => $this->warehouseId,
                'increased_warehouse_id' => $validateData['increased_warehouse_id'],
                'amount'    => $validateData['amount'],
            ]);
            
            $amountAtWarehouse->amount -= $validateData['amount'];
            $amountAtWarehouse->save();

            $warehouseUser = ErpUsersWarehouses::with('user')->where('warehouse_id', $validateData['increased_warehouse_id'])->get();
            foreach($warehouseUser as $wUser){
                if(isset($wUser->user) && $wUser->user->telegram_id && $wUser->user->active){
                    try {
                        $telegram = TelegramMessage::create()
                            ->to($wUser->user->telegram_id)
                            ->line('*#'.$approval->id.' NO. LU KAYIT [TRANSFER İŞLEMİ] ONAYINIZI BEKLİYOR*')
                            ->line('')
                            ->line('Ürün kodu : '. $approval->getItem->code)
                            ->line('Ürün adı : '. $approval->getItem->name)
                            ->line('Miktar : '. $approval->amount.' '.$approval->getItem->itemToUnit->content)
                            ->line('Hareket yönü : '. $approval->getDwindlingWarehouse->name .'->'.$approval->getIncreasedWarehouse->name)
                            ->line('')
                            ->line('Onaylamak için :')
                            ->line(url('/bildirimlerim'))
                            ->line('adresini ziyaret ediniz.')
                            ->send();
                            if($telegram){
                                #update approval
                                $approval->update(['notify' => $approval->notify+1]);
                            }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }
        }
        Session::flash('success', 'Transfer talebiniz alındı'); 
        $this->emit('updateNotifications');
        $this->dispatchBrowserEvent(self::model.'openTransferModalHide');


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

    public function exportMyProductList(){
        Session::flash('success', trans('site.general.download'));
        return Excel::download(new MyProductListExport(),'Mevcut_Urunlerim - '.date('Y-m-d').'.xlsx');
    }

    public function openProcessModal($itemId, $warehouseId)
    {
        $this->itemId = $itemId;
        $this->warehouseId = $warehouseId;
        $this->item = ErpItem::find($itemId);
        $this->dispatchBrowserEvent(self::model.'openProcessModalShow');
    }

    // public function openProcessRequest()
    // {
    //     $validateData = Validator::make($this->processRequest, [
    //         'amount'                    => 'required',
    //         'wastage'                   => 'required',
    //         'content'                   => 'required',
    //     ])->validate();

    // }

    public function process($rowId, $action)
    {
        $this->rowId    = $rowId;
        $this->action   = $action;
        if($this->action == 'alertMe') {

            $this->alertArray = [
                'perma'             => 0,
                'amount'            => '',
                'alertCondition'    => '',
            ];
            $this->dispatchBrowserEvent(self::model.'alertModalShow');
            $this->setAlertData   = ErpItem::find($this->rowId);

        }elseif($this->action == 'demand'){

            if($this->calcNotifyCount() == 0) {

                $this->demandWarehouseId = null;
                $this->transferRequest = [
                    'amount'                        => '',
                    'itemDemandedFromThisWarehouse' => '',
                    'itemDemandedToThisWarehouse'   => '',
                    'content'                       => '',
                ];
                $this->transferAbleWarehouses = null;
                
                $this->transferAbleWarehouses = ErpItemsWarehouses::with('warehouse')
                    ->where('item_id',$this->rowId)
                    ->where('amount','>',0)
                    ->whereNotIn('warehouse_id', Auth::user()->warehouses->pluck('warehouse_id'))
                    ->get();
                $this->setDemandData = ErpItem::find($this->rowId);
                $this->dispatchBrowserEvent(self::model.'demandItemModalShow');

            }else{

                Session::flash('error', trans('site.general.doTheApprovalsFirst'));

            }

        }elseif($this->action == 'movement'){
           
            $this->movements = ErpStockMovement::with('getDwindlingWarehouse', 'getIncreasedWarehouse', 'item', 'getSender', 'getApproval')
                ->where('item_id', $rowId)
                ->orderByDesc('created_at')
                ->get();
            $this->item = ErpItem::find($rowId);
            $this->dispatchBrowserEvent(self::model.'movementmodalShow');

        }elseif($this->action == 'warehouse'){

            $this->itemDistribution = ErpItem::with('stocks')->find($rowId);
            $this->dispatchBrowserEvent(self::model.'warehousemodalShow');

        }elseif($this->action == 'wtb'){

            $this->item = ErpItem::with('stocks')->find($rowId);

            $this->wtbFromUsers = User::select('id','name')->where('buy_assent', 1)->where('active', 1)->get();

            $this->wtbRequest = [
                'amount' => null,
                'note' => null,
                'wtbFromUser' => 0,
            ];
            $this->dispatchBrowserEvent(self::model.'WTBmodalShow');

        }
    }

    public function wtbRequest(){
        $newWtbRequest = new ErpApproval;
        $newWtbRequest->item_id = $this->item->id;
        $newWtbRequest->content = $this->wtbRequest['note'];
        $newWtbRequest->amount = $this->wtbRequest['amount'];
        $newWtbRequest->type = 7;
        $newWtbRequest->increased_warehouse_id = 1;
        $newWtbRequest->answer_user = $this->wtbRequest['wtbFromUser']; // answer_user burada cevap vermesini istediği kişiyi atamak için kullanıldı. ilk seferde tek kişiye gönderilecek şekilde olacak fakat sonradan whereIn için bir array şeklinde yazılacak
        $newWtbRequest->sender_user = Auth::user()->id;
        $newWtbRequest->save();

        $answerUser = User::find($newWtbRequest->answer_user);
        Session::flash('success','Satın alma talebiniz ilgili kişiye iletilmiştir.');
        if(isset($answerUser->telegram_id)){
            try {
                TelegramMessage::create()
                    ->to($answerUser->telegram_id)
                    ->line('*Sayın '.$answerUser->name.'* yeni satın alma talebi onayınızı bekliyor.')
                    ->line('')
                    ->line('*Talebi oluşturan :* '. Auth::user()->name)
                    ->line('')
                    ->line('*Ürünün portaldaki ismi :* '. $this->item->name)
                    ->line('*Ürünün portaldaki kodu :* '. $this->item->code)
                    ->line('*Talep edilen miktar* : '. $this->wtbRequest['amount'].' '.$this->item->itemToUnit->content)
                    ->line('')
                    ->line('*Talebi açanın notu* : '. $this->wtbRequest['note'])
                    ->line('Onaylamak için :')
                    ->line(url('/bildirimlerim'))
                    ->line('adresini ziyaret ediniz.')
                    ->send();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $this->dispatchBrowserEvent(self::model.'WTBmodalHide');
        $this->emit('updateNotifications');
    }

    public function startCleanProduct($itemID,$warehouseID,$warehouseItemAmount){
        $this->item = ErpItem::with('stocks')->find($itemID);
        $this->itemId = $itemID;
        $this->cleanProductMaxAmount = $warehouseItemAmount;
        $this->cleanProductWarehouseID = $warehouseID;

        $this->cleanProductModalData = [
            'amount' => null,
            'use_item_1' => [
                "item_id" => null,
                "amount" => null
            ],
            'use_item_2' => [
                "item_id" => null,
                "amount" => null
            ],
            'use_item_3' => [
                "item_id" => null,
                "amount" => null
            ],
            'use_item_4' => [
                "item_id" => null,
                "amount" => null
            ]
        ];
        $this->dispatchBrowserEvent(self::model.'sendToCleanProductModalShow');

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
                        ->line('Ürün kodu : '. $newApproval->getItem->code)
                        ->line('Ürün adı : '. $newApproval->getItem->name)
                        ->line('Miktar : '. $newApproval->amount.' '.$newApproval->getItem->itemToUnit->content)
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

    public function setAlert(){
        Validator::make($this->alertArray, [
            'perma'  => 'required',
            'amount' => 'required',
        ])->validate();
        if($this->alertArray['alertCondition'] === ""){
            $this->alertArray['alertCondition'] = "<";
        }
        $newAlertToUser = new ErpItemsPersonalActions;
        $newAlertToUser->user_id            = Auth::user()->id;
        $newAlertToUser->item_id            = $this->rowId;
        $newAlertToUser->amount             = $this->alertArray['amount'];
        $newAlertToUser->alert_condition    = $this->alertArray['alertCondition'];
        $newAlertToUser->warned             = false;
        $newAlertToUser->perma              = $this->alertArray['perma'];
        $newAlertToUser->save();

        $this->dispatchBrowserEvent(self::model.'alertModalHide');
        Session::flash('success','Alarm kuruldu.');
    }

    public function transferItemsBetweenMyWarehouses(){
        $validateData = Validator::make($this->transferItemsBetweenMyWarehousesModalData, [
            'amount'                    => 'required',
            'increased_warehouse_id'    => 'required|integer',
        ]);
        if ($validateData->fails()) {
            Session::flash('error','Girilen bilgiler hatalı.');
            return $this->dispatchBrowserEvent(self::model.'transferItemsBetweenMyWarehousesModalHide');
        }

        $increaseMyWarehouse = ErpItemsWarehouses::where('item_id',$this->itemId)->where('warehouse_id',$this->transferItemsBetweenMyWarehousesModalData['increased_warehouse_id'])->first();
        if($increaseMyWarehouse){
            $this->transferItemsBetweenMyWarehousesData->amount -= $this->transferItemsBetweenMyWarehousesModalData['amount'];
            $this->transferItemsBetweenMyWarehousesData->save();
            $increaseMyWarehouse->amount += $this->transferItemsBetweenMyWarehousesModalData['amount'];
            $increaseMyWarehouse->save();
        }else{
            $this->transferItemsBetweenMyWarehousesData->amount -= $this->transferItemsBetweenMyWarehousesModalData['amount'];
            $this->transferItemsBetweenMyWarehousesData->save();
            $new = new ErpItemsWarehouses;
            $new->item_id = $this->itemId;
            $new->warehouse_id = $this->transferItemsBetweenMyWarehousesModalData['increased_warehouse_id'];
            $new->amount = $this->transferItemsBetweenMyWarehousesModalData['amount'];
            $new->save();
        }
        $this->dispatchBrowserEvent(self::model.'transferItemsBetweenMyWarehousesModalHide');
        Session::flash('success','Ürünler aktarıldı.');

    }

    public function transferItemsBetweenMyWarehousesModal($itemId, $warehouseId){
        $this->warehouseId = $warehouseId;
        $this->itemId = $itemId;
        $this->item = ErpItem::find($itemId);
        $this->transferItemsBetweenMyWarehousesData = ErpItemsWarehouses::where('item_id',$this->itemId)->where('warehouse_id',$this->warehouseId)->first();
        $this->transferItemsBetweenMyWarehousesModalData = [
            'amount' => $this->transferItemsBetweenMyWarehousesData->amount
        ];
        $this->dispatchBrowserEvent(self::model.'transferItemsBetweenMyWarehousesModalShow');
    }

    public function showCancelAlert($item_id)
    {
        $this->deleteAlertId = $item_id;
        $this->action   = 'cancel';
        $this->dispatchBrowserEvent(self::model.'alertCancelModalShow');
    }
    
    public function cancelPersonalAlert()
    {
        $deleteAlert = Auth::user()->myErpItemAlerts->where('item_id',$this->deleteAlertId)->first();
        $deleteAlert->delete();
        $this->deleteAlertId = null;
        $this->dispatchBrowserEvent(self::model.'alertCancelModalHide');
        $this->emit('updateNotifications');
        Session::flash('success','Alarm kaldırıldı.');
    }

    public function note($itemId,$warehouseId){
        $this->noteItemId=$itemId;
        $this->noteWarehouseId=$warehouseId;
        $this->noteAddedItem=ErpItem::find($itemId);
        $this->itemNote($this->noteItemId,$this->noteWarehouseId);
        $this->dispatchBrowserEvent(self::model.'noteModalShow');
    }

    public function addNote(){
        $note = ErpUsersWarehouseNotes::where('item_id',$this->noteItemId)->where('warehouse_id',$this->noteWarehouseId)->where('user_id',Auth::user()->id)->first();
        if(!is_null($note)){
            $note->note = $this->noteText;
            $note->save();
        }else{
            $newItemNote = new ErpUsersWarehouseNotes;
            $newItemNote->item_id = $this->noteItemId;
            $newItemNote->warehouse_id = $this->noteWarehouseId;
            $newItemNote->user_id = Auth::user()->id;
            $newItemNote->note = $this->noteText;
            $newItemNote->save();
        }
        $this->dispatchBrowserEvent(self::model.'noteModalHide');
    }

    public function sendProductToCleanRoom(){
        $validateData = Validator::make($this->cleanProductModalData, [
            // 'lot_no'    => 'required',
            'amount'    => 'required',
            // 'status'    => 'required|numeric',
            // 'send_date' => 'required',
            // 'note'      => 'nullable|string',
            'use_item_1.item_id'   => 'nullable|numeric',
            'use_item_1.amount'    => 'nullable|numeric',
            'use_item_2.item_id'   => 'nullable|numeric',
            'use_item_2.amount'    => 'nullable|numeric',
            'use_item_3.item_id'   => 'nullable|numeric',
            'use_item_3.amount'    => 'nullable|numeric',
            'use_item_4.item_id'   => 'nullable|numeric',
            'use_item_4.amount'    => 'nullable|numeric',
        ])->validate();

        $cleanProduct = ErpSekk::where('warehouse_id',$this->cleanProductWarehouseID)->where('item_id',$this->itemId)->where('general_status',1)->first();
        if($cleanProduct){
            $cleanProduct->amount = $cleanProduct->amount+$validateData['amount'];
        }else{
            $cleanProduct = new ErpSekk;
            $cleanProduct->user_id = Auth()->user()->id;
            $cleanProduct->warehouse_id = $this->cleanProductWarehouseID;
            $cleanProduct->item_id = $this->itemId;
            $cleanProduct->amount = $validateData['amount'];
            $cleanProduct->clean_status = 0;
            $cleanProduct->general_status = 1;
        }
        $decreaseItemFromWareHouse = ErpItemsWarehouses::where('warehouse_id',$this->cleanProductWarehouseID)->where('item_id',$this->itemId)->first();
        $decreaseItemFromWareHouse->amount -= $validateData['amount'];

        ErpStockMovement::create([
            'item_id'                   => $this->itemId,
            'type'                      => 9,
            'content'                   => null,
            'dwindling_warehouse_id'    => $this->cleanProductWarehouseID,
            'increased_warehouse_id'    => 23,
            'sender_user'               => Auth::user()->id,
            'approval_user'             => Auth::user()->id,
            'amount'                    => $validateData['amount'],
            'old_warehouse_amount'      => $decreaseItemFromWareHouse->amount,
            'old_total_amount'          => $this->item->stocks->sum('amount'),
        ]);
        $this->decreaseUsedItemInProccess($this->cleanProductModalData['use_item_1']['item_id'],$this->cleanProductModalData['use_item_1']['amount'],$this->cleanProductWarehouseID,12,Auth::user()->id);
        $this->decreaseUsedItemInProccess($this->cleanProductModalData['use_item_2']['item_id'],$this->cleanProductModalData['use_item_2']['amount'],$this->cleanProductWarehouseID,12,Auth::user()->id);
        $this->decreaseUsedItemInProccess($this->cleanProductModalData['use_item_3']['item_id'],$this->cleanProductModalData['use_item_3']['amount'],$this->cleanProductWarehouseID,12,Auth::user()->id);
        $this->decreaseUsedItemInProccess($this->cleanProductModalData['use_item_4']['item_id'],$this->cleanProductModalData['use_item_4']['amount'],$this->cleanProductWarehouseID,12,Auth::user()->id);

        $decreaseItemFromWareHouse->save();
        $cleanProduct->save();

        Session::flash('success','Sterilizasyon süreci başlatıldı.');
        $this->dispatchBrowserEvent(self::model.'sendToCleanProductModalHide');
    }

    private function itemNote(){
        $note = ErpUsersWarehouseNotes::where('item_id',$this->noteItemId)->where('warehouse_id',$this->noteWarehouseId)->where('user_id',Auth::user()->id)->first();
        if(is_null($note)){
            return $this->noteText=null;
        }else{
            return $this->noteText=$note->note;
        }
    }

    private function decreaseUsedItemInProccess($itemID,$amount,$warehouseID,$type,$userID){
        if(!is_null($itemID) && !is_null($amount)){
            $decreaseItemForProccess = ErpItemsWarehouses::where('warehouse_id',$warehouseID)->where('item_id',$itemID)->first();
            if($decreaseItemForProccess){
                ErpStockMovement::create([
                    'item_id'                    => $itemID,
                    'type'                      => $type,
                    'content'                   => null,
                    'dwindling_warehouse_id'    => $warehouseID,
                    'increased_warehouse_id'    => 0,
                    'sender_user'               => $userID,
                    'approval_user'             => $userID,
                    'amount'                    => $amount,
                    'old_warehouse_amount'      => $decreaseItemForProccess->amount,
                    'old_total_amount'          => ErpItem::find($itemID)->stocks->sum('amount'),
                ]);
                $decreaseItemForProccess->amount -= $amount;
                $decreaseItemForProccess->save();
            }
        }
        return;
    }
}
