<?php

namespace App\Http\Livewire\Part;

use Livewire\Component;
use App\Models\Erp\ErpApproval;
use App\Models\Erp\EndProduct\ErpSekk;
use Illuminate\Support\Facades\Auth;

class Notification extends Component
{
    protected $listeners = [
        'updateNotifications' => '$refresh'
    ];

    public function render()
    {
        
        return view('livewire.part.notification', [
            'purchaseConfirmation' => ErpApproval::where('status', 0)
                ->where('type', 2)
                ->get(),
            'validatedPurchase' => ErpApproval::where('status', 0)
                ->where('type', 9)
                ->get(),
            'user' => Auth::user(),
            'saleConfirmation' => ErpApproval::where('status', 0)
                ->where('type', 3)
                ->get(),
            'wtbRequestsFromMe' => ErpApproval::where('status', 0)
                ->where('type', 7)
                ->where('answer_user',Auth::user()->id)
                ->get(),
            'requested' => ErpApproval::where('status', 0)
                ->where('type', 5)
                ->whereIn('dwindling_warehouse_id', Auth::user()->warehouses->pluck('warehouse_id'))
                ->get(),
            'reConfirm' => ErpApproval::where('status', 0)
                ->where('type', 8)
                ->whereIn('increased_warehouse_id', Auth::user()->warehouses->pluck('warehouse_id'))
                ->get(),
            'packageConfirm' => ErpSekk::where('general_status', 3)->get(),
        ]);
    }
}
