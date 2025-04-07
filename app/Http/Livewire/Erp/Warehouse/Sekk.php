<?php

namespace App\Http\Livewire\Erp\Warehouse;

use App\Models\View\Products;
use App\Models\User;
use App\Models\Erp\EndProduct\ErpSekk;
use App\Models\Erp\Warehouse\{ErpWarehouse, ErpFinishedProduct, ErpStockMovement};
use App\Models\Erp\Item\{ErpItem, ErpItemsWarehouses};
use Illuminate\Support\Facades\{Validator, Session, Auth};
use NotificationChannels\Telegram\TelegramMessage;
use Livewire\{Component, WithPagination, WithFileUploads};

class Sekk extends Component
{
    use WithPagination, WithFileUploads;

    public $tab;
    public $selectedItem;
    public $maxProductAmount;
    public $startQualityControlModalData = [];
    public $endQualityControlModalData = [];
    public $endQualityControlproccess;
    public $finishProductModalData = [];

    const model = 'sekk';

    public function render()
    {
        return view('livewire.erp.warehouse.sekk',[
            'seDatas' => ErpSekk::with('item','warehouse')->where('general_status',1)->paginate(20),
            'qualityControlDatas' => ErpSekk::with('item','warehouse')->where('general_status',2)->paginate(20),
            'readyToPackageDatas' => ErpSekk::with('item','warehouse')->whereIn('general_status',[4,5 ])->paginate(20),
            'warehouses' => ErpWarehouse::where('can_send_to_outside',1)->get(),
            'data' => Products::where('user_id', Auth::user()->id)->get(),
        ]);
    }

    public function tagProcess($tag){
        $this->tab = $tag;
    }

    public function seProcess($id,$columnName){
        $updateErpSekk = ErpSekk::find($id);
        $updateErpSekk->$columnName = !$updateErpSekk->$columnName;
        $updateErpSekk->user_id = Auth::user()->id;
        $updateErpSekk->save();
    }

    public function startQualityControlModal($id){
        $this->selectedItem = ErpSekk::find($id);
        $this->startQualityControlModalData = [
            'work_order_no' => null,
        ];
        
        $this->dispatchBrowserEvent(self::model.'startQualityControlModalShow');
    }

    public function startQualityControl(){
        $validateData = Validator::make($this->startQualityControlModalData, [
            'work_order_no'     => 'required',
        ]);
        if($validateData->fails()){
            $this->dispatchBrowserEvent(self::model.'startQualityControlModalHide');
            return Session::flash('error','İş emri numarasını giriniz.');
        }

        $isDuplicated = ErpSekk::where('item_id',$this->selectedItem->item_id)->where('general_status',2)->where('work_order_no',$this->startQualityControlModalData['work_order_no'])->first();
        if($isDuplicated){
            $isDuplicated->amount = $isDuplicated->amount + $this->selectedItem->amount;
            $isDuplicated->save();
            $this->selectedItem->delete();
            Session::flash('success','Kalite kontrol süreci başlatıldı, Sterilizasyondaki diğer ürünlere eklendi.');
        }else{
            $this->selectedItem->general_status = 2;
            $this->selectedItem->work_order_no = $this->startQualityControlModalData['work_order_no'];
            $this->selectedItem->save();
            Session::flash('success','Kalite kontrol süreci başlatıldı.');
        }
        $this->dispatchBrowserEvent(self::model.'startQualityControlModalHide');
    }

    public function endQualityControlModal($id,$proccess){
        $this->endQualityControlproccess = $proccess;
        $this->selectedItem = ErpSekk::find($id);
        if($this->selectedItem->clean_status){
            $this->maxProductAmount = $this->selectedItem->amount;
            $this->endQualityControlModalData = [
                'lotNo' => $this->selectedItem->lot_no,
                'wastedItemCount' => 0,
                'text' => null,
            ];
            $this->dispatchBrowserEvent(self::model.'endQualityControlModalShow');
        }else{
            Session::flash('error','Sterilizasyon süreci tamamlanlanmadı.');

        }
        
    }

    public function endQualityControl(){
        if($this->endQualityControlproccess == 1){
            $validateData = Validator::make($this->endQualityControlModalData, [
                // 'lotNo'             => 'required',
                'wastedItemCount'   => 'required|numeric',
                'text'              => 'required|string',
            ]);
            if($validateData->fails()){
                $this->dispatchBrowserEvent(self::model.'endQualityControlModalHide');
                return Session::flash('error','Eksik parametre hatası.');
            }
            $this->selectedItem->update([
                // 'lot_no' => $this->endQualityControlModalData['lotNo'],
                'user_id' => Auth::user()->id,
                'general_status' => 3,
                'amount' => $this->selectedItem->amount - $this->endQualityControlModalData['wastedItemCount'],
                'text' => $this->endQualityControlModalData['text'],
            ]);

            $qualityManagers = User::where('confirm_quality_control',1)->get();
            foreach($qualityManagers as $user){
                try {
                    if($user->telegram_id){
                        TelegramMessage::create()
                            ->to($user->telegram_id)
                            ->line('*ÜRÜN KALİTE KONTROLÜ TAMAMLANDI VE ONAYINIZI BEKLİYOR*')
                            ->line('*ÜRÜN : *'.$this->selectedItem?->item->name)
                            ->line('*MİKTAR : *'.$this->selectedItem->amount - $this->endQualityControlModalData['wastedItemCount'])
                            ->line('*LOT NO : *'.$this->endQualityControlModalData['lotNo'])
                            ->line('*KALİTE SÜRECİNİ TAMLAYAN : *'.Auth::user()->name)
                            ->line('*AÇIKLAMA : *'.$this->endQualityControlModalData['text'])
                        ->send();
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            $this->dispatchBrowserEvent(self::model.'endQualityControlModalHide');
            return Session::flash('success','Kalite kontrol tamamlandı ve onaya gönderildi.');
        }elseif ($this->endQualityControlproccess == 2) {
            $validateData = Validator::make($this->endQualityControlModalData, [
                'text'       => 'required|string',
            ]);
            if($validateData->fails()){
                $this->dispatchBrowserEvent(self::model.'endQualityControlModalHide');
                return Session::flash('error','Eksik parametre hatası.');
            }
            $this->selectedItem->update([
                'user_id' => Auth::user()->id,
                'general_status' => 6,
                'text' => $this->endQualityControlModalData['text'],
            ]);
            $this->selectedItem->delete();
            $qualityManagers = User::where('confirm_quality_control',1)->get();
            foreach($qualityManagers as $user){
                if($user->telegram_id){
                    #send message by Telegram
                    TelegramMessage::create()
                        ->to($user->telegram_id)
                        ->line('*ÜRÜN KALİTE KONTROL AŞAMASINDA İMHA EDİLDİ*')
                        ->line('*ÜRÜN : *'.$this->selectedItem?->item->name)
                        ->line('*MİKTAR : *'.$this->selectedItem->amount)
                        ->line('*İMHA EDEN : *'.$this->selectedItem?->user->name)
                        ->line('*AÇIKLAMA : *'.$this->selectedItem->text)
                    ->send();
                }
            }
            $this->dispatchBrowserEvent(self::model.'endQualityControlModalHide');
            return Session::flash('success','ÜRÜN KALİTE KONTROL AŞAMASINDA İMHA EDİLDİ');
        }
        $this->dispatchBrowserEvent(self::model.'endQualityControlModalHide');
        Session::flash('error','Bilinmeyen bir hata oluştu.');
    }

    public function kProcess($sekkID){
        $this->selectedItem = ErpSekk::find($sekkID);
        if($this->selectedItem->general_status == 4){
            $updateGenralStatus = 5;
        }elseif($this->selectedItem->general_status == 5){
            $updateGenralStatus = 4;
        }
        $this->selectedItem->update([
            'user_id' => Auth::user()->id,
            'general_status' => $updateGenralStatus,
            'amount' => $this->selectedItem->amount,
        ]);
    }

    public function finishProductModal($sekkID){
        $this->selectedItem = ErpSekk::find($sekkID);
        if($this->selectedItem->general_status == 5){
            $this->finishProductModalData = [
                'amount' => $this->selectedItem->amount,
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
            $this->dispatchBrowserEvent(self::model.'finishProductModalShow');
        }else{
            Session::flash('error','Kolileme işlemini tamamlayınız');
        }
    }

    public function finishProduct(){
        $validateData = Validator::make($this->finishProductModalData, [
            // 'lot_no' => 'required',
            // 'send_to' => 'required',
            'warehouse_id'          => 'required|numeric',
            'send_date'             => 'required',
            'note'                  => 'required|string',
            'amount'                => 'required|numeric',
            'use_item_1.item_id'    => 'nullable|numeric',
            'use_item_1.amount'     => 'nullable|numeric',
            'use_item_2.item_id'    => 'nullable|numeric',
            'use_item_2.amount'     => 'nullable|numeric',
            'use_item_3.item_id'    => 'nullable|numeric',
            'use_item_3.amount'     => 'nullable|numeric',
            'use_item_4.item_id'    => 'nullable|numeric',
            'use_item_4.amount'     => 'nullable|numeric',
        ])->validate();
        $isDuplicated = ErpFinishedProduct::where('lot_no',$this->selectedItem->lot_no)->where('warehouse_id',$validateData['warehouse_id'])->where('item_id',$this->selectedItem->item_id)->first();
        if($isDuplicated){
            $isDuplicated->amount += $validateData['amount'];
            $isDuplicated->warehouse_id = $validateData['warehouse_id'];
            $isDuplicated->note = $isDuplicated->note.' / Ek Not : '.$validateData['note'];
            $isDuplicated->save();
        }else{
            $newFinishedProduct = new ErpFinishedProduct;
            $newFinishedProduct->item_id = $this->selectedItem->item_id;
            $newFinishedProduct->user_id = Auth::user()->id;
            $newFinishedProduct->warehouse_id = $validateData['warehouse_id'];
            $newFinishedProduct->lot_no = is_null($this->selectedItem->lot_no) ? 0:$this->selectedItem->lot_no;
            $newFinishedProduct->amount = $validateData['amount'];
            $newFinishedProduct->note = $validateData['note'];
            $newFinishedProduct->status = 1;
            $newFinishedProduct->send_date = $validateData['send_date'];
            $newFinishedProduct->save();
        }
        if($this->selectedItem->amount == $validateData['amount']){
            $this->selectedItem->delete();
        }else{
            $this->selectedItem->amount -= $validateData['amount'];
            $this->selectedItem->save();
        }
        $this->decreaseUsedItemInProccess($this->finishProductModalData['use_item_1']['item_id'],$this->finishProductModalData['use_item_1']['amount'],23,13,Auth::user()->id);
        $this->decreaseUsedItemInProccess($this->finishProductModalData['use_item_2']['item_id'],$this->finishProductModalData['use_item_2']['amount'],23,13,Auth::user()->id);
        $this->decreaseUsedItemInProccess($this->finishProductModalData['use_item_3']['item_id'],$this->finishProductModalData['use_item_3']['amount'],23,13,Auth::user()->id);
        $this->decreaseUsedItemInProccess($this->finishProductModalData['use_item_4']['item_id'],$this->finishProductModalData['use_item_4']['amount'],23,13,Auth::user()->id);
        Session::flash('success',$this->selectedItem->name . ' bitmiş ürünlere eklendi.');
        $this->dispatchBrowserEvent(self::model.'finishProductModalHide');
    }

    private function decreaseUsedItemInProccess($itemID,$amount,$warehouseID,$type,$userID){
        if(!is_null($itemID) && !is_null($amount)){
            $decreaseItemForProccess = ErpItemsWarehouses::where('warehouse_id',$warehouseID)->where('item_id',$itemID)->first();
            if($decreaseItemForProccess){
                ErpStockMovement::create([
                    'item_id'                   => $itemID,
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
