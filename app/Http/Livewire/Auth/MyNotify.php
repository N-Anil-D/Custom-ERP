<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use App\Models\Erp\ErpApproval;
use App\Models\Erp\Item\{ErpItem, ErpItemsWarehouses};
use App\Models\Erp\Warehouse\{ErpStockMovement,ErpWarehouse,ErpSendProducts,ErpFinishedProduct};
use App\Models\Erp\EndProduct\{ErpSekk};
use Livewire\Component;
use Illuminate\Support\Facades\{Session, Auth, Storage, Validator};
use Illuminate\Support\Str;
use NotificationChannels\Telegram\TelegramMessage;
use Livewire\WithFileUploads;

class MyNotify extends Component
{
    use WithFileUploads;

    const model = 'my-notify';

    public $rowId;
    public $typeId;
    public $action;
    public $selectedModelData;
    public $selectedSekkModalData;
    public $sekkProcedure;
    public $content_answer;
    public $item;
    public $buy;
    public $buyRecord = [];
    public $modalData = [];
    public $swapRequestAbleUsers;
    public $changeIncreasingWarehouse = [];
    public $swapRequest = [];

    protected $rules = [
        'content_answer' => 'required|min:6',
    ];

    public function render()
    {

        return view('livewire.auth.my-notify', [
            'canConfirmBuy' => ErpApproval::where('status', 0)->where('type', 2)->get(),
            'canBuy' => ErpApproval::where('status', 0)->where('type', 9)->get(),
            'canExit' => ErpApproval::where('status', 0)->where('type', 3)->get(),
            'demandedFromMyWarehouse' => ErpApproval::whereIn('dwindling_warehouse_id',Auth::user()->warehouses->pluck('warehouse_id'))
                ->where('status', 0)
                ->where('type', 5)
                ->get(),
            'acceptedDemandsApproval' => ErpApproval::whereIn('increased_warehouse_id',Auth::user()->warehouses->pluck('warehouse_id'))
                ->where('status', 0)
                ->where('type', 8)
                ->get(),
            'wtbRequest' => ErpApproval::where('status', 0)->where('type', 7)->where('answer_user',Auth::user()->id)->get(),
            'buyToThisWarehouses' => ErpWarehouse::where('can_take_from_outside', 1)->get(),
            'readyToPackege' => ErpSekk::where('general_status', 3)->get(),
        ]);
    }

    public function fileDownload($file)
    {
        return Storage::download($file);
    }

    public function confirmModal($rowId, $typeId, $action)
    {
        $this->rowId                        = $rowId;
        $this->typeId                       = $typeId;
        $this->action                       = $action;
        $this->selectedModelData            = ErpApproval::find($rowId);
        $this->content_answer               = '';
        $this->modalData                    = ['amount' => $this->selectedModelData->amount];
        $this->changeIncreasingWarehouse    = ['id' => $this->selectedModelData->increased_warehouse_id];

        $this->dispatchBrowserEvent(self::model.'confirmOrCancelModalShow');
    }

    public function confirm()
    {
        if($this->selectedModelData->status == 0){
            
            $message        = '';
            $messageType    = 'success';

            if($this->action == 'confirm'){

                if($this->confirmControl()){

                    switch ($this->typeId) {

                        ########################################################################################################################################
                        case 0: # doğrudan stok transferi // gönderici talep sahibi

                            ################################### erp_approval update edilecek
                            $this->selectedModelData->update([
                                'status' => 1
                            ]);

                            ################################### erp_stock_movements doldurulacak
                            ErpStockMovement::create([
                                'item_id'                   => $this->selectedModelData->item_id,
                                'type'                      => $this->selectedModelData->type,
                                'content'                   => $this->selectedModelData->content,
                                'dwindling_warehouse_id'    => $this->selectedModelData->dwindling_warehouse_id,
                                'increased_warehouse_id'    => $this->selectedModelData->increased_warehouse_id,
                                'sender_user'               => $this->selectedModelData->sender_user,
                                'approval_user'             => Auth::user()->id,
                                'amount'                    => $this->selectedModelData->amount,
                            ]);
                            ################################### erp_items_warehouses doldurulacak yada update edilecek
                            
                            $warehouse = ErpItemsWarehouses::where('item_id', $this->selectedModelData->item_id)
                                ->where('warehouse_id', $this->selectedModelData->increased_warehouse_id)
                                ->first();

                            if($warehouse){
                                $warehouse->update([
                                    'amount' => $warehouse->amount + $this->selectedModelData->amount
                                ]);
                            }else{
                                ErpItemsWarehouses::create([
                                    'item_id' => $this->selectedModelData->item_id,
                                    'warehouse_id' => $this->selectedModelData->increased_warehouse_id,
                                    'amount' => $this->selectedModelData->amount
                                ]);
                            }
                            
                            # bildirim 
                            $messageType = "success";
                            $message = "Transfer işlemi tamamlandı.";
                            if($this->selectedModelData->getSender?->telegram_id){
                                try {
                                    TelegramMessage::create()
                                        ->to($this->selectedModelData->getSender?->telegram_id)
                                        ->line('*#'.$this->selectedModelData->id.' NO. LU KAYIT [TRANSFER İŞLEMİNİZ] ONAYLANDI*')
                                        ->line('')
                                        ->line('Ürün kodu : '. $this->selectedModelData->getItem->code)
                                        ->line('Ürün adı : '. $this->selectedModelData->getItem->name)
                                        ->line('Miktar : '. $this->selectedModelData->amount.' '.$this->selectedModelData->getItem->itemToUnit->content)
                                        ->line('Hareket yönü : '. $this->selectedModelData->getDwindlingWarehouse->name .'->'.$this->selectedModelData->getIncreasedWarehouse->name)
                                        ->line('')
                                        ->line('Onaylayan : '. Auth::user()->name)
                                        ->send();
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                        break;
                        ########################################################################################################################################
                        case 1: # üretime çıkış
                            dd('üretime çıkış');
                        break;
                        ########################################################################################################################################
                        case 2: # ürün satın alma işlemi

                            ################################### erp_approval update edilecek
                            $this->selectedModelData->update([
                                'status' => 1,
                                'answer_user' => Auth::user()->id
                            ]);
                            ################################### erp_stock_movements doldurulacak
                            if(is_null($this->selectedModelData->getItem->stock($this->selectedModelData->increased_warehouse_id))){
                                $old_warehouse_amount = 0;
                            }else{
                                $old_warehouse_amount = $this->selectedModelData->getItem->stock($this->selectedModelData->increased_warehouse_id)->amount;
                            }
                            ErpStockMovement::create([
                                'item_id'                   => $this->selectedModelData->item_id,
                                'type'                      => $this->selectedModelData->type,
                                'content'                   => $this->selectedModelData->content,
                                'dwindling_warehouse_id'    => $this->selectedModelData->dwindling_warehouse_id,
                                'increased_warehouse_id'    => $this->selectedModelData->increased_warehouse_id,
                                'sender_user'               => $this->selectedModelData->sender_user,
                                'approval_user'             => Auth::user()->id,
                                'amount'                    => $this->selectedModelData->amount,
                                'old_warehouse_amount'      => $old_warehouse_amount,
                                'old_total_amount'          => $this->selectedModelData->getItem->stocks->sum('amount'),
                            ]);
                            ################################### erp_items_warehouses doldurulacak yada update edilecek

                            $warehouse = ErpItemsWarehouses::where('item_id', $this->selectedModelData->item_id)
                                ->where('warehouse_id', $this->selectedModelData->increased_warehouse_id)
                                ->first();
                                if($warehouse){
                                    $warehouse->update([
                                        'amount' => $warehouse->amount + $this->selectedModelData->amount
                                    ]);
                                }else{
                                    ErpItemsWarehouses::create([
                                        'item_id' => $this->selectedModelData->item_id,
                                        'warehouse_id' => $this->selectedModelData->increased_warehouse_id,
                                        'amount' => $this->selectedModelData->amount
                                    ]);
                                }

                            ################################### bildirim 
                            $messageType = "success";
                            $message = "Transfer işlemi tamamlandı.";
                            if($this->selectedModelData->getSender?->telegram_id){
                                try {
                                    TelegramMessage::create()
                                        ->to($this->selectedModelData->getSender?->telegram_id)
                                        ->line('*#'.$this->selectedModelData->id.' NO. LU KAYIT [SATIN ALMA İŞLEMİNİZ] ONAYLANDI*')
                                        ->line('')
                                        ->line('Ürün kodu : '. $this->selectedModelData->getItem->code)
                                        ->line('Ürün adı : '. $this->selectedModelData->getItem->name)
                                        ->line('Miktar : '. $this->selectedModelData->amount.' '.$this->selectedModelData->getItem->itemToUnit->content)
                                        ->line('Alım depo kodu : ' .$this->selectedModelData->getIncreasedWarehouse->code)
                                        ->line('Alım depo adı : '.$this->selectedModelData->getIncreasedWarehouse->name)
                                        ->line('')
                                        ->line('Onaylayan : '. Auth::user()->name)
                                        ->send();
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                        break;
                        ########################################################################################################################################
                        case 3: # fatura çıkışı
                            ################################### erp_approval update edilecek
                            $this->selectedModelData->update([
                                'status' => 1,
                            ]);
                            ################################### erp_send_products güncellenecek
                            $soldProductUpdate = ErpSendProducts::find($this->selectedModelData->content);
                            $soldProductUpdate->update([
                                'status' => 1,
                            ]);
                            ################################### erp_items_warehouses doldurulacak yada update edilecek

                            ################################### bildirim 
                            $messageType = "success";
                            $message = "Transfer işlemi tamamlandı.";
                            if($this->selectedModelData->getSender?->telegram_id){
                                try {
                                    TelegramMessage::create()
                                        ->to($this->selectedModelData->getSender?->telegram_id)
                                        ->line('*#'.$this->selectedModelData->id.' NO. LU KAYIT [ÜRÜN SATIŞI] ONAYLANDI*')
                                        ->line('')
                                        ->line('Ürün kodu : '. $this->selectedModelData->getItem->code)
                                        ->line('Ürün adı : '. $this->selectedModelData->getItem->name)
                                        ->line('Miktar : '. $this->selectedModelData->amount.' '.$this->selectedModelData->getItem->itemToUnit->content)
                                        ->line('Çıkış yapılan depo kodu : ' .$this->selectedModelData->getDwindlingWarehouse->code)
                                        ->line('Çıkış yapılan depo adı : '.$this->selectedModelData->getDwindlingWarehouse->name)
                                        ->line('')
                                        ->line('Onaylayan : '. Auth::user()->name)
                                        ->send();
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                        break;
                        ########################################################################################################################################
                        case 4: # Sayım verisi
                            dd('sayım verisi');
                        break;
                        ########################################################################################################################################
                        case 5: # doğrudan stok transferi // alıcı talep sahibi
                            
                            $validateData = Validator::make($this->modalData, [
                                'amount'                    => 'required|numeric',
                            ])->validate();

                            ################################### erp_approval update edilecek
                            $this->selectedModelData->update([
                                'status' => 1,
                                'answer_user' => Auth::user()->id,
                                'notify' => $this->selectedModelData->notify + 1
                            ]);

                            ################################### stok transfer talebi onaylandıktan sonra talebi oluşturana aldı mı diye tekrar onaylatılacak
                            $newApproval = new ErpApproval;        
                            $newApproval->item_id                   = $this->selectedModelData->item_id;
                            $newApproval->content                   = Auth::user()->name.' : '.$this->selectedModelData->id . "ID li talebini kabul ettim. Lütfen talebinizin elinize geçtiğini onaylayınız.";
                            $newApproval->type                      = 8;
                            $newApproval->sender_user               = Auth::user()->id;
                            $newApproval->increased_warehouse_id    = $this->selectedModelData->increased_warehouse_id;
                            $newApproval->dwindling_warehouse_id    = $this->selectedModelData->dwindling_warehouse_id;
                            $newApproval->amount                    = $this->modalData['amount'];
                            $newApproval->save();
                            ################################### stok transfer talebi onaylandıktan sonra talebi oluşturana aldı mı diye tekrar onaylatılacak

                            
                            # azalan depo için
                            $warehouse = ErpItemsWarehouses::where('item_id', $this->selectedModelData->item_id)
                                ->where('warehouse_id', $this->selectedModelData->dwindling_warehouse_id)
                                ->first();
                            $warehouse->update([
                                'amount' => $warehouse->amount - $this->modalData['amount']
                            ]);
                            
                            # bildirim 
                            $messageType = "success";
                            $message = "Transfer işlemi kabul edildi.";
                            if($this->selectedModelData->getSender?->telegram_id){
                                try {
                                    TelegramMessage::create()
                                        ->to($this->selectedModelData->getSender?->telegram_id)
                                        ->line('*#'.$this->selectedModelData->id.' NO. LU KAYIT [TRANSFER İŞLEMİNİZ] ONAYLANDI. Lütfen talebinizin elinize geçtiğini onaylayınız.*')
                                        ->line('')
                                        ->line('Ürün kodu : '. $this->selectedModelData->getItem->code)
                                        ->line('Ürün adı : '. $this->selectedModelData->getItem->name)
                                        ->line('Miktar : '. $this->modalData['amount'].' '.$this->selectedModelData->getItem->itemToUnit->content)
                                        ->line('Hareket yönü : '. $this->selectedModelData->getDwindlingWarehouse->name .'->'.$this->selectedModelData->getIncreasedWarehouse->name)
                                        ->line('')
                                        ->line('Kabul eden : '. Auth::user()->name)
                                        ->send();
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                        break;
                        ########################################################################################################################################
                        case 7: # satın alma talebi (onaylama)
                            $this->selectedModelData->update([
                                'status' => 1,
                                'answer_user' => Auth::user()->id,
                                'notify' => $this->selectedModelData->notify + 1
                            ]);

                            $wtbRequest = ErpApproval::find($this->rowId);
                            
                            ErpApproval::create([
                                'item_id' => $wtbRequest->item_id,
                                'content' => 'Talebi onaylayan : '.$this->selectedModelData->getSender->name.' | Talep sahibi : '.$wtbRequest->content,
                                'type' => 9,
                                'sender_user' => Auth::user()->id,
                                'amount' => $wtbRequest->amount,
                                'increased_warehouse_id' => $this->changeIncreasingWarehouse['id'],
                            ]);
                    
                            $botMessage = preg_replace('/[^A-Za-z0-9\-ÜİÇÖĞIığüşöç.,\/]/', ' ', $this->content_answer);
                            try {
                                TelegramMessage::create()
                                    ->to($this->selectedModelData->getSender?->telegram_id)
                                    ->line('*SATIN ALMA TALEBİNİZ '.Auth::user()->name.' TARAFINDAN ONAYLANDI.* Talebiniz satın alma sorumlularına iletildi.')
                                    ->line('')
                                    ->line('Detaylara aşağıdan ulaşabilirsiniz.')
                                    ->line('')
                                    ->line('*Talep Edilen Ürün Adı :* '. $this->selectedModelData->getItem->name)
                                    ->line('*Talep Edilen Ürün Kodu :* '. $this->selectedModelData->getItem->code)
                                    ->line('*Talep Edilen Miktar :* '. $this->selectedModelData->amount. ' '.$this->selectedModelData->getItem->itemToUnit->code)
                                    ->line('*Talebi onaylayan :* '. Auth::user()->name)
                                    ->send();
                            } catch (\Throwable $th) {
                                //throw $th;
                            }
                            $message        = 'Satın alma talebini onayladınız.';
                    
                        break;
                        ########################################################################################################################################
                        case 8: # doğrudan stok transferi // talep sahibi elim geçti onayı
                            ################################### erp_stock_movements doldurulacak
                            ErpStockMovement::create([
                                'item_id'                   => $this->selectedModelData->item_id,
                                'type'                      => $this->selectedModelData->type,
                                'content'                   => $this->selectedModelData->content,
                                'dwindling_warehouse_id'    => $this->selectedModelData->dwindling_warehouse_id,
                                'increased_warehouse_id'    => $this->selectedModelData->increased_warehouse_id,
                                'sender_user'               => Auth::user()->id,
                                'approval_user'             => $this->selectedModelData->sender_user,
                                'amount'                    => $this->selectedModelData->amount,
                            ]);
                            
                            ################################### erp_items_warehouses doldurulacak
                            $this->selectedModelData->update([
                                'status' => 1,
                                // 'answer_user' => Auth::user()->id,
                            ]);
                            
                            # artan depo -> ErpItemsWarehouses değiştirilecek
                            $warehouse = ErpItemsWarehouses::where('item_id', $this->selectedModelData->item_id)
                                ->where('warehouse_id', $this->selectedModelData->increased_warehouse_id);

                            if($warehouse->count() === 0){
                                ErpItemsWarehouses::create([
                                    'item_id' => $this->selectedModelData->item_id,
                                    'warehouse_id' => $this->selectedModelData->increased_warehouse_id,
                                    'amount' => $this->selectedModelData->amount
                                ]);
                            }elseif($warehouse->count() === 1){
                                $warehouse->update([
                                    'amount' => $warehouse->first()->amount + $this->selectedModelData->amount
                                ]);
                            }
                        break;
                        ########################################################################################################################################
                        case 9: # Onaylanmış satın alma talebi 7->9->2
                            ################################### erp_stock_movements doldurulacak
                            ErpStockMovement::create([
                                'item_id'                   => $this->selectedModelData->item_id,
                                'type'                      => $this->selectedModelData->type,
                                'content'                   => $this->selectedModelData->content,
                                'dwindling_warehouse_id'    => $this->selectedModelData->dwindling_warehouse_id,
                                'increased_warehouse_id'    => $this->selectedModelData->increased_warehouse_id,
                                'sender_user'               => $this->selectedModelData->sender_user,
                                'approval_user'             => Auth::user()->id,
                                'amount'                    => $this->selectedModelData->amount,
                            ]);
                            ################################### erp_items_warehouses doldurulacak yada update edilecek
                            $this->selectedModelData->update([
                                'status' => 1,
                                'answer_user' => Auth::user()->id
                            ]);
                            
                            # artan depo -- ErpItemsWarehouses doldurulacak
                            $warehouse = ErpItemsWarehouses::where('item_id', $this->selectedModelData->item_id)
                                ->where('warehouse_id', $this->selectedModelData->increased_warehouse_id)
                                ->first();

                            if($warehouse){
                                $warehouse->update([
                                    'amount' => $warehouse->amount + $this->selectedModelData->amount
                                ]);
                            }
                        break;
                    }
                
                }else{
                    $message     = 'Deponuzda bu miktarı göndereceğiniz stoğunuz bulunmamaktadır.';
                    $messageType = 'error';
                }
            }else{
                
                $this->selectedModelData->update([
                    'content_answer'    => $this->content_answer,
                    'status'            => 2,
                    'answer_user'       => Auth::user()->id,
                ]);

                //talep red edilirse azaltılmış depoya stoğu iade edilecek
                if($this->typeId == 0 || $this->typeId == 5 || $this->typeId == 8){
                    $warehouse = ErpItemsWarehouses::where('item_id', $this->selectedModelData->item_id)
                    ->where('warehouse_id', $this->selectedModelData->dwindling_warehouse_id)
                    ->first();
                    $warehouse->update([
                        'amount' => $warehouse->amount + $this->selectedModelData->amount
                    ]);
                }
                //talep red edilirse azaltılmış depoya stoğu iade edilecek

                //satış red edilirse bitmiş ürünlere iade edilecek
                if($this->typeId == 3){
                    $warehouse = ErpFinishedProduct::where('lot_no', $this->selectedModelData->lot_no)
                    ->first();
                    $soldProductUpdate = ErpSendProducts::find($this->selectedModelData->content);
                    $soldProductUpdate->update([
                        'status' => 3,
                    ]);
                    if($warehouse){
                        $warehouse->update([
                            'amount' => $warehouse->amount + $this->selectedModelData->amount
                        ]);
                    }else{
                        $warehouse = ErpFinishedProduct::where('lot_no', $this->selectedModelData->lot_no)->withTrashed()->first();
                        $warehouse->deleted_at = null;
                        $warehouse->amount = $this->selectedModelData->amount;
                        $warehouse->save();
                    }
                }
                //satış red edilirse bitmiş ürünlere iade edilecek

                $botMessage = preg_replace('/[^A-Za-z0-9\-ÜİÇÖĞIığüşöç.,\/]/', ' ', $this->content_answer);
                if(isset($this->selectedModelData->getSender?->telegram_id)){
                    try {
                        TelegramMessage::create()
                            ->to($this->selectedModelData->getSender?->telegram_id)
                            ->line('*#'. $this->selectedModelData->id .' NO. LU KAYIT İPTAL EDİLDİ*')
                            ->line('')
                            ->line('TÜRÜ : '. $this->selectedModelData->getType())
                            ->line('ÜRÜN ADI : '. $this->selectedModelData->getItem->name)
                            ->line('ÜRÜN KODU : '. $this->selectedModelData->getItem->code)
                            ->line('MİKTAR : '. $this->selectedModelData->amount)
                            ->line('')
                            ->line('İPTAL EDEN : '. Auth::user()->name)
                            ->line('İPTAL SEBEBİ : '. $botMessage)
                            ->send();
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }

                $message = 'İşlem iptal edildi.';

            }

            Session::flash($messageType, $message);
            $this->dispatchBrowserEvent(self::model.'confirmOrCancelModalHide');
            $this->emit('updateNotifications');
        }else{
            Session::flash('error', 'İşlem başka bir kullanıcı tarafından daha önce onaylandı ya da iptal edildi.');
            $this->dispatchBrowserEvent(self::model.'confirmOrCancelModalHide');
            $this->emit('updateNotifications');
        }
    }

    public function openBuyModal($approvalID,$itemId){
        $this->selectedModelData    = ErpApproval::find($approvalID);
        $this->buyRecord = [
            'content'                   => '',
            'file'                      => null,
            'amount'                    => '',
            'increased_warehouse_id'    => $this->selectedModelData->increased_warehouse_id,
        ];
        $this->itemId = $itemId;
        $this->item = ErpItem::find($itemId);
        $this->dispatchBrowserEvent(self::model.'openbuymodalShow');
    }

    public function confirmPackageModal($sekkID,$procedure){
        $this->selectedSekkModalData    = ErpSekk::find($sekkID);
        $this->sekkProcedure = $procedure;
        if($this->sekkProcedure == 'confirm'){
            $this->dispatchBrowserEvent(self::model.'openPackageCofirmModalShow');
        }elseif($this->sekkProcedure == 'cancel'){
            $this->dispatchBrowserEvent(self::model.'openPackageCofirmModalShow');
        }else{
            Session::flash('error', 'Sayfayı yenileyiniz.');
        }
        $this->emit('updateNotifications');
    }

    public function packageProccess(){

        if($this->sekkProcedure == 'confirm'){
            $this->selectedSekkModalData->update([
                'user_id'=>Auth::user()->id,
                'general_status'=>4,
                'text'=>$this->content_answer,
            ]);
            Session::flash('success', 'Ürün kutulama aşamasına aktarıldı.');
            $this->dispatchBrowserEvent(self::model.'openPackageCofirmModalHide');

        }elseif($this->sekkProcedure == 'cancel'){
            $this->selectedSekkModalData->update([
                'user_id'=>Auth::user()->id,
                'general_status'=>2,
                'text'=>$this->content_answer,
            ]);
            Session::flash('success', 'Ürün kalite kontrol aşamasına aktarıldı.');
            $this->dispatchBrowserEvent(self::model.'openPackageCofirmModalHide');

        }else{
            Session::flash('error', 'Sayfayı yenileyiniz.');
        }
        
        foreach (User::where('active',1)->where('quality_control',1)->get() as $value) {
            try {
                TelegramMessage::create()
                ->to($value->telegram_id)
                ->line('#'.$this->selectedSekkModalData->id.' ID. li Kutulama talebiniz : *'.$this->selectedSekkModalData->getGeneralStatus(). '* olarak güncellendi.')
                ->line('')
                ->line('Ürün adı : '. $this->selectedSekkModalData->item?->name)
                ->line('Miktar : '. $this->selectedSekkModalData->amount.' '.$this->selectedSekkModalData->item?->itemToUnit->content)
                ->line('Açıklama : '. $this->selectedSekkModalData->text)
                ->send();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        $this->emit('updateNotifications');
        return;
    }

    public function buyRecord(){

        $validateData = Validator::make($this->buyRecord, [
            'content'                   => 'required',
            'file'                      => 'nullable|mimetypes:application/pdf,image/jpeg',
            'amount'                    => 'required',
            'increased_warehouse_id'    => 'required',
        ])->validate();

        if(isset($validateData['file'])){
            $fileName = Str::random(11) . "_" .Str::kebab($validateData['file']->getClientOriginalName());
            Storage::putFileAs('fatura-gorselleri/', $validateData['file'], $fileName);
            $fileName = 'fatura-gorselleri/'.Str::random(11) . "_" .Str::kebab($validateData['file']->getClientOriginalName());
        }else{
            $fileName = null;
        }

        $this->selectedModelData->update([
            'status' => 1
        ]);

        ErpApproval::create([
            'item_id' => $this->itemId,
            'content' => $validateData['content'],
            'file' => $fileName,
            'type' => 2,
            'sender_user' => Auth::user()->id,
            'amount' => $validateData['amount'],
            'increased_warehouse_id' => $validateData['increased_warehouse_id'],
        ]);
        $this->dispatchBrowserEvent(self::model.'openbuymodalHide');
        $this->emit('updateNotifications');

    }

    public function confirmControl()
    {
        # type 5 ise
        if(in_array($this->typeId, [5])){
            $itemStock = ErpItemsWarehouses::where('item_id', $this->selectedModelData->item_id)
                ->where('warehouse_id', $this->selectedModelData->dwindling_warehouse_id)
                ->sum('amount');

            if($this->selectedModelData->amount > $itemStock){
                return FALSE;
            }
        }
        return TRUE;
    }

    public function swapRequestToAnotherUser($approvalID){
        $this->swapRequest = ['swap_to_this_user'=>0];
        $this->selectedModelData    = ErpApproval::find($approvalID);
        $this->swapRequestAbleUsers = User::where('buy_assent',1)->whereNot('id',Auth::user()->id)->get();

        $this->dispatchBrowserEvent(self::model.'swapRequestmodalShow');
    }

    public function swapRequestToAnother(){
        $validateData = Validator::make($this->swapRequest, [
            'swap_to_this_user' => 'required'
        ])->validate();

        $this->selectedModelData->update([
            'answer_user' => $validateData['swap_to_this_user']
        ]);
        
        $this->emit('updateNotifications');
        $this->dispatchBrowserEvent(self::model.'swapRequestmodalHide');
        Session::flash('success', 'Talebiniz aktarıldı.');

    }
}
