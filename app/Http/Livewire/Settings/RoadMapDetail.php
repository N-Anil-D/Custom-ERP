<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\RoadMapSection;
use App\Models\RoadMapDetail as RDM;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RoadMapDetail extends Component
{

    public $main;
    public $mainId;
    public $sub;
    public $subId;
    public $action;

    public function boot()
    {
        $this->mainId = \Request::segment(3);
        $this->calc();
    }

    public function updating()
    {
        $this->calc();
    }

    public function inserting()
    {
        $this->calc();
    }

    public function refresh()
    {
        $this->calc();
    }

    public function calc()
    {
        $this->main = RoadMapSection::find($this->mainId);
    }

    public function process($subId,$action)
    {
        $this->subId = $subId;
        $this->action = $action;
        $this->sub = RDM::find($this->subId);

        if($action == 'delete'){
            $this->dispatchBrowserEvent('rdmDeleteModalShow');
        }else{
            if($action == 'insert'){
                $this->clearItem();                
            }else{
                $this->sub = $this->sub->toArray();
            }   
            $this->dispatchBrowserEvent('rdmInsertOrUpdateModalShow');
        }
    }

    public function delete()
    {
        $this->sub->delete();
        $this->calc();
        $this->dispatchBrowserEvent('rdmDeleteModalHide');
        Session::flash('success', trans('site.general.complete'));
    }

    public function clearItem()
    {
        $this->sub = [];
    }

    public function insertOrUpdate()
    {
        Validator::make($this->sub, [
            'content'=>'required',
        ])->validate();

        if($this->subId == 0){
            $rdm = RDM::create($this->sub);
            $rdm->update(['sectionId'=>$this->mainId]);
            $message = trans('site.alert.data.insert.success');
        }else{
            RDM::find($this->subId)->update($this->sub);
            $message='Kayıt düzenlendi';
        }

        $this->dispatchBrowserEvent('rdmInsertOrUpdateModalHide');
        $this->updating();
        Session::flash('success',$message);
        
    }

    public function render()
    {
        return view('livewire.settings.road-map-detail');
    }
}
