<?php

namespace App\Http\Livewire\Auth;

use App\Models\Erp\ErpApproval;
use Livewire\Component;
use Illuminate\Support\Facades\{Session, Auth};
use App\Models\Erp\Item\ErpItemsWarehouses;

class WaitingDemands extends Component
{
    const model = 'my-waiting-demands';

    public $rowId;
    public $typeId;
    public $action;
    public $selectedModelData;
    public $content_answer;

    protected $rules = [
        'content_answer' => 'required|min:6',
    ];

    public function render()
    {
        return view('livewire.auth.waiting-demands');
    }

    public function confirmModal($rowId, $typeId, $action)
    {
        $this->rowId                = $rowId;
        $this->typeId               = $typeId;
        $this->action               = $action;
        $this->selectedModelData    = ErpApproval::find($rowId);
        $this->content_answer       = '';

        $this->dispatchBrowserEvent(self::model.'confirmOrCancelModalShow');

    }

    public function confirm()
    {
        $this->selectedModelData = ErpApproval::find($this->rowId);

        if($this->selectedModelData->status == 0){
                
                $message        = '';
                $messageType    = 'success';
                $user           = Auth::user();
                
                if($this->action == 'cancel'){
                    $this->selectedModelData->update([
                        'content_answer'    => $this->content_answer,
                        'status'            => 2,
                        'answer_user'       => $user->id,
                    ]);
                    
                    //talep red edilirse azaltılmış depoya stoğu iade edilecek
                    if($this->typeId == 0){
                    // if($this->typeId == 0 || $this->typeId == 5){
                        $warehouse = ErpItemsWarehouses::where('item_id', $this->selectedModelData->item_id)
                        ->where('warehouse_id', $this->selectedModelData->dwindling_warehouse_id)
                        ->first();
                        $warehouse->update([
                            'amount' => $warehouse->amount + $this->selectedModelData->amount
                        ]);
                    }
                    //talep red edilirse azaltılmış depoya stoğu iade edilecek
                
                    // burda iptal edildiğinin bilgisini karşı onaylayıcı kişiye göndermeliyiz ?
                    // alım talebinde alım sorumlusuna / sorumlularına
                    // çıkış işleminde çıkış sorumlusuna / sorumlularına
                    // talep işlemlerinde depo sorumlusuna / sorumlularına
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


}
