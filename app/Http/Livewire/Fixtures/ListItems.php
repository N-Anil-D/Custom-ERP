<?php

namespace App\Http\Livewire\Fixtures;

use Livewire\{Component, WithPagination};
use Illuminate\Support\Facades\{Validator, Session};
use App\Models\FixturesItem;
use App\Models\UretimTubeBrcLine;
use PDF;

class ListItems extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    const model = 'fixturesItems';

    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;
    public $search;


    public function render()
    {
        return view('livewire.fixtures.list-items', [
            'data' => FixturesItem::where('code', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%')
                ->orWhere('location', 'like', '%' . $this->search . '%')
                ->orWhere('section', 'like', '%' . $this->search . '%')
                ->orWhere('floor', 'like', '%' . $this->search . '%')
                ->orWhere('room_code', 'like', '%' . $this->search . '%')
                ->orWhere('item_name', 'like', '%' . $this->search . '%')
                ->orWhere('brand', 'like', '%' . $this->search . '%')
                ->orWhere('content', 'like', '%' . $this->search . '%')
                ->orderBy('id')
                ->paginate(50),
        ]);
    }

    public function process($rowId, $action)
    {
        $this->rowId    = $rowId;
        $this->action   = $action;

        if ($action == 'delete') {
            $this->dispatchBrowserEvent(self::model.$this->action.'modalShow');
        } else {
            if ($action == 'insert') {
                $this->clearItem();
            } else {               
              
                $this->selectedModelData = FixturesItem::find($this->rowId);
                $this->selectedArrayData = [
                    'code'      => $this->selectedModelData->code,
                    'barcode'   => $this->selectedModelData->barcode,
                    'location'  => $this->selectedModelData->location,
                    'section'   => $this->selectedModelData->section,
                    'floor'     => $this->selectedModelData->floor,
                    'room_code' => $this->selectedModelData->room_code,
                    'content'   => $this->selectedModelData->content,
                    'item_name' => $this->selectedModelData->item_name,
                    'brand'     => $this->selectedModelData->brand,
                    'amount'    => $this->selectedModelData->amount,
                ];
                
            }
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');
        }
    }

    public function upsert()
    {
        $validateData = $this->validateData();

        if ($this->action == 'insert') {
            FixturesItem::create($validateData);
        } else {
            $this->selectedModelData->update($validateData);
        }

        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.'upsertmodalHide');
    }

    public function clearItem()
    {
        $this->selectedArrayData = [
            'code'      => '',
            'barcode'   => '',
            'location'  => '',
            'section'   => '',
            'floor'     => '',
            'room_code' => '',
            'content'   => '',
            'item_name' => '',
            'brand'     => '',
            'amount'    => '',
        ];
    }

    public function delete()
    {
        FixturesItem::find($this->rowId)->delete();
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function validateData()
    {
        if($this->action == 'insert'){
            $validateData = Validator::make($this->selectedArrayData, [
                'code'      => 'required|min:3|unique:fixtures_items,code',
                'location'  => 'required',
                'section'   => 'required',
                'floor'     => 'required',
                'room_code' => 'required',
                'content'   => 'required',
                'item_name' => 'required',
                'brand'     => '',
                'amount'    => 'required',
            ])->validate();
        }else{
            $validateData = Validator::make($this->selectedArrayData, [
                'barcode'   => '',
                'location'  => 'required',
                'section'   => 'required',
                'floor'     => 'required',
                'room_code' => 'required',
                'content'   => 'required',
                'item_name' => 'required',
                'brand'     => '',
                'amount'    => 'required',
            ])->validate();
        }
        return $validateData;
    }
}
