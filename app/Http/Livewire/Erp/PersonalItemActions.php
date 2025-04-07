<?php

namespace App\Http\Livewire\Erp;

use Livewire\{Component, WithPagination, WithFileUploads};
use App\Models\Erp\Item\{ErpItem, ErpUnit, ErpItemsPersonalActions, ErpItemsWarehouses};
use App\Models\Erp\Warehouse\{ErpWarehouse, ErpStockMovement, ErpUsersWarehouses};
use App\Models\Erp\ErpApproval;
use Illuminate\Support\Facades\{Validator, Session, Storage, Auth};
use NotificationChannels\Telegram\TelegramMessage;
use App\Exports\Erp\{StockMovementsExport, ItemWarehouseDispersionExport,SystemDataExport};
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class PersonalItemActions extends Component
{
    use WithPagination, WithFileUploads;
    
    protected $paginationTheme = 'bootstrap';
    
    protected $listeners = [
        'updateNotifications' => '$refresh'
    ];

    const model = 'personalItemActions';

    public $selectedArrayData = [];
    public $wtbRequest = [];
    public $buyRequest = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    public $search;
    public $warehouseId;
    public $transferAbleUsers = [];
    public $transferAbleWarehouses;
    public $transferRequest = [];
    public $setDemandData;
    public $setAlertData;
    public $deleteAlertId;
    public $alertArray = [];
    public $movements = [];
    public $item;
    public $orderColumn = 'id';
    public $itemTypeFilter = ['hm'=>true, 'ym'=>true, 'tm'=>true];
    public $itemTypeFilterArray = [];
    public $wtbFromUsers;
    public $buyToThisWarehouses;


    public function render()
    {
        $this->itemTypeFilter();

        $dataTypeFiltred = ErpItem::
        whereIn('type', $this->itemTypeFilterArray)
        ->with('itemToUnit', 'stocks', 'movements');

        $data = $dataTypeFiltred
        ->where(function($query){
            $query
            ->orWhere('id', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->orWhere('content', 'like', '%' . $this->search . '%')
            ->orWhereRelation('itemToUnit', 'content', 'like', '%'. $this->search . '%');
        })->orderBy($this->orderColumn)->paginate(20);
        
        $itemEnterEnabled = ErpWarehouse::where('can_take_from_outside',1)->get();

        return view('livewire.erp.personal-item-actions', [
            'units' => ErpUnit::all(),
            'data' => $data,
            'itemEnterEnabled' => $itemEnterEnabled,
        ]);
    }

    public function itemTypeFilter(){
        if($this->itemTypeFilter['hm']){
            $this->itemTypeFilterArray[0]=0;
        }else{
            $this->itemTypeFilterArray[0]=null;
        }
        
        if($this->itemTypeFilter['ym']){
            $this->itemTypeFilterArray[1]=1;
        }else{
            $this->itemTypeFilterArray[1]=null;
        }

        if($this->itemTypeFilter['tm']){
            $this->itemTypeFilterArray[2]=2;
        }else{
            $this->itemTypeFilterArray[2]=null;
        }
    }

    public function sort($by){
        $this->orderColumn = $by;
    }

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

                $this->warehouseId = null;
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

            $this->item = ErpItem::with('stocks')->find($rowId);
            $this->dispatchBrowserEvent(self::model.'warehousemodalShow');

        }elseif($this->action == 'wtb'){
            $this->item = ErpItem::find($rowId);
            $this->wtbFromUsers = User::select('id','name')->where('buy_assent', 1)->where('active', 1)->get();
            $this->buyToThisWarehouses = ErpWarehouse::where('can_take_from_outside', 1)->get();

            $this->wtbRequest = [
                'amount' => null,
                'note' => null,
                'wtbFromUser' => 0,
                'wtbToThisWarehouse' => null,
            ];
            
            $this->dispatchBrowserEvent(self::model.'WTBmodalShow');
        }
    }

    public function wtbRequest(){
        
        Validator::make($this->wtbRequest, [
            'amount'                   => 'required',
            'note'                     => 'nullable|string',
            'wtbFromUser'              => 'required',
            'wtbToThisWarehouse'       => 'required',
        ])->validate();

        $newWtbRequest = new ErpApproval;
        $newWtbRequest->item_id = $this->item->id;
        $newWtbRequest->content = $this->wtbRequest['note'];
        $newWtbRequest->amount = $this->wtbRequest['amount'];
        $newWtbRequest->type = 7;
        $newWtbRequest->increased_warehouse_id = $this->wtbRequest['wtbToThisWarehouse'];
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
                    ->line('*Ürünün adı :* '. $this->item->name)
                    ->line('*Ürünün kodu :* '. $this->item->code)
                    ->line('*Talep edilen miktar* : '. $this->wtbRequest['amount'].' '.$this->item->itemToUnit->content)
                    ->line('Eklenmesini istediği depo : '. ErpWarehouse::find($this->wtbRequest['wtbToThisWarehouse'])->name)
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

    public function showCancelAlert($item_id){
        $this->deleteAlertId = $item_id;
        $this->action   = 'cancel';
        $this->dispatchBrowserEvent(self::model.'alertCancelModalShow');
    }

    public function cancelPersonalAlert(){
        $deleteAlert = Auth::user()->myErpItemAlerts->where('item_id',$this->deleteAlertId)->first();
        $deleteAlert->delete();
        $this->deleteAlertId = null;
        $this->dispatchBrowserEvent(self::model.'alertCancelModalHide');
        $this->emit('updateNotifications');
        Session::flash('success','Alarm kaldırıldı.');
    }

    public function setAlert(){
        Validator::make($this->alertArray, [
            'amount' => 'required',
            'perma' => 'required',
        ])->validate();
        if($this->alertArray['alertCondition'] === ""){
            $this->alertArray['alertCondition'] = "<";
        }
        
        $newAlertToUser = new ErpItemsPersonalActions;
        $newAlertToUser->user_id            = Auth::user()->id;
        $newAlertToUser->item_id            = $this->rowId;
        $newAlertToUser->amount             = $this->alertArray['amount'];
        $newAlertToUser->alert_condition    = $this->alertArray['alertCondition'];
        $newAlertToUser->perma              = $this->alertArray['perma'];
        $newAlertToUser->warned             = false;
        $newAlertToUser->save();

        $this->dispatchBrowserEvent(self::model.'alertModalHide');
        Session::flash('success','Alarm kuruldu.');
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
        $this->emit('updateNotifications');
    }

    // Eğer beklenen transfer talebi varsa yeni bir transfer açmasını engelle
    // Mevcut depolarından çıkış varsa engelle
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

    public function exportStockMovements($month = 1){
        $itemCode = $this->movements[0]->item->code;
        $this->dispatchBrowserEvent(self::model.'movementmodalHide');
        $safeName = Str::slug(date('Y-m-d').' Stok Hareketleri '.$itemCode.' son'.$month.'ay', '-');
        $fileName = $safeName.'.xlsx';
        Session::flash('success', trans('site.general.download'));
        return Excel::download(new StockMovementsExport($this->rowId,$month), $fileName);
    }
   
    public function exportStockDispersion(){
        $itemCode = $this->item->code;
        $this->dispatchBrowserEvent(self::model.'warehousemodalHide');
        $safeName = Str::slug(date('Y-m-d').' Stok Dağılımı '.$itemCode, '-');
        $fileName = $safeName.'.xlsx';
        Session::flash('success', trans('site.general.download'));
        return Excel::download(new ItemWarehouseDispersionExport($this->rowId), $fileName);
    }
   
    public function itemsExports(){
        $systemDataType = ErpItem::class;
        Session::flash('success', trans('site.general.download'));
        return Excel::download(new SystemDataExport($systemDataType),'Tum Urunler.xlsx');
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
                        ->line('Ürün kodu : '. $approval->getItem?->code)
                        ->line('Ürün adı : '. $approval->getItem?->name)
                        ->line('Miktar : '. $approval->amount.' '.$approval->getItem?->itemToUnit->content)
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
        
        $this->dispatchBrowserEvent(self::model.'openbuymodalHide');
        $this->emit('updateNotifications');
    }
}