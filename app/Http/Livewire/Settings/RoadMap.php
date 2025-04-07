<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\RoadMapSection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RoadMap extends Component
{
     public $sections = [];
     public $section = [];
     public $search;
     public $rowId;
     public $action;
    
    //başlangıçta gel
    public function boot()
    {
        $this->calc();
    }

    //güncellenir ise
    public function updating()
    {
        $this->calc();
    }

    //yeni kayıtlarda
    public function inserting()
    {
        $this->calc();
    }

    //yenile
    public function refresh()
    {
        $this->calc();
    }

    //neler güncellenecek
    public function calc()
    {
        $this->sections = RoadMapSection::where('content','like','%'.$this->search.'%')
            ->orderBy('line')->get();
    }
    
    public function process($rowId,$action)
    {
        $this->rowId = $rowId;
        $this->action = $action;
        $this->section = RoadMapSection::find($this->rowId);

        if($action == 'delete'){
            $this->dispatchBrowserEvent('roadMapSectionDeleteModalShow');
        }else{
            
            if($action == 'insert'){
                $this->clearItem();
            }else{
                $this->section = $this->section->toArray();
            }

            $this->dispatchBrowserEvent('roadMapSectionInsertOrUpdateModalShow');
        }
    }
    

    public function delete()
    {
        $this->section->update([
            'active' => (!$this->section->active),
        ]);
        $this->calc();
        $this->dispatchBrowserEvent('roadMapSectionDeleteModalHide');
        Session::flash('success', trans('site.general.complete'));
    }

    public function clearItem()
    {
        $this->section = [];
    }

    public function insertOrUpdate()
    {
        Validator::make($this->section, [
            'content' => 'required',
            'line' => 'required|numeric'
        ])->validate();

        if($this->rowId == 0){
            RoadMapSection::create($this->section);
            $message = "Temel hedef oluşturuldu";

        }else{
            RoadMapSection::find($this->rowId)->update($this->section);
            $message = "Temel hedef düzenlendi";
        }

        $this->dispatchBrowserEvent('roadMapSectionInsertOrUpdateModalHide');
        $this->updating();
        Session::flash('success',$message);
    }
    
    public function render()
    {
        return view('livewire.settings.road-map');
    }

}
