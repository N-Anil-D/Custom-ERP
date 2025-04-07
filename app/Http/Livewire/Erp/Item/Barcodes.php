<?php

namespace App\Http\Livewire\Erp\Item;

use App\Models\Erp\ErpBarcode;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Barcodes extends Component
{

    // protected $listeners = [
    //     'updateBarcodes' => 'render'
    // ];

    public $rowId;

    // public function mount()
    // {
    //     $this->item_id = Session::get('barcodeModal-itemId');
    // }


    public function render()
    {

        // dd($this->rowId);
        return view('livewire.erp.barcodes', ['rowId' => $this->rowId]);
    }
}
