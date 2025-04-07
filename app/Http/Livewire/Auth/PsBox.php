<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\PsBox as PSB; # model ve component isminin karışmaması için
use Illuminate\Support\Facades\{Session, Crypt, Validator, Auth};

class PsBox extends Component
{
    public $data = [];
    public $psbox = [];
    public $search;
    public $action;
    public $rowId;

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

    //temizle
    public function clearItem()
    {
        $this->psbox = [];
    }

    //neler güncellenecek
    public function calc()
    {
        $this->data = PSB::where('userId',Auth::user()->id)
            ->where('definition','like','%'.$this->search.'%')
            ->orderBy('id','desc')
            ->get();
    }

    public function process($rowId,$action)
    {
        
        $this->rowId = $rowId;
        $this->action = $action;
        
        if($action == 'delete'){
            $this->dispatchBrowserEvent('psboxDeleteModalShow');
        }else{
            if($action == "insert"){
                $this->clearItem();
            }else{
                $psbox = PSB::find($rowId);
                $this->psbox = [
                    'id'        => $psbox->id,
                    'definition'=> $psbox->definition,
                    'def1'      => Crypt::decryptString($psbox->def1),
                    'def2'      => Crypt::decryptString($psbox->def2),
                    'def3'      => Crypt::decryptString($psbox->def3),
                ];
            }
            $this->dispatchBrowserEvent('psboxInsertOrUpdateModalShow');
        }
        
    }

    public function delete()
    {
        $this->psbox = PSB::find($this->rowId)->delete();
        $this->dispatchBrowserEvent('psboxDeleteModalHide');
        $this->updating();
        Session::flash('success', trans('site.general.complete'));
    }

    public function insertOrUpdate()
    {
        Validator::make($this->psbox, [
            'definition' => 'required',
            'def1'       => 'required',
        ])->validate();

        if($this->rowId == 0){

            $this->arrayControl();

            PSB::create([
                'userId'        => Auth::user()->id,
                'definition'    => $this->psbox['definition'],
                'def1'          => Crypt::encryptString($this->psbox['def1']),
                'def2'          => Crypt::encryptString($this->psbox['def2']),
                'def3'          => Crypt::encryptString($this->psbox['def3']),
            ]);

            $this->updating();
            $this->dispatchBrowserEvent('psboxInsertOrUpdateModalHide');
            Session::flash('success', trans('site.general.complete'));
            
        }else{

            PSB::find($this->rowId)->update([
                'definition'=> $this->psbox['definition'],
                'def1'      => Crypt::encryptString($this->psbox['def1']),
                'def2'      => Crypt::encryptString($this->psbox['def2']),
                'def3'      => Crypt::encryptString($this->psbox['def3']),
            ]);

            $this->updating();
            $this->dispatchBrowserEvent('psboxInsertOrUpdateModalHide');
            Session::flash('success', trans('site.general.complete'));

        }
    }

    public function arrayControl()
    {
        if(empty($this->psbox['def2'])){
            $this->psbox['def2']="";
        }
        if(empty($this->psbox['def3'])){
            $this->psbox['def3']="";
        }
    }

    public function render()
    {
        return view('livewire.auth.ps-box');
    }

    
}
