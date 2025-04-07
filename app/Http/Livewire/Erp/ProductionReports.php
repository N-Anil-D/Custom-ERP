<?php

namespace App\Http\Livewire\Erp;

use App\Http\Middleware\ErpReport;
use Livewire\Component;
use App\Models\Erp\Item\ErpItem;
use App\Models\Erp\Warehouse\ErpWarehouse;
use App\Models\User;
use Illuminate\Support\Facades\{Session, Auth};

class ProductionReports extends Component
{
    public function render()
    {
        return view('livewire.erp.production-reports',[
            'craftableItems'    => ErpItem::with('itemToUnit')->whereIn('type',[1,2])->get(),
            'warehouses'        => ErpWarehouse::get(),
        ]);
    }

}
