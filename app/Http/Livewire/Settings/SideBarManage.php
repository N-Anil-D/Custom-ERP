<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\Sidebar;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SideBarManage extends Component
{
    
    public $sideBar = [];
    public $itemId;
    public $item;
    public $hid;    
    public $data = [];
    public $action;
    public $search;

    //dinleyici
    protected $listeners = [
        'updateSidebar' => '$refresh'
    ];

    //başlangıçta gel
    public function boot()
    {
        $this->calc();
    }

    //güncellenir ise
    public function updating()
    {
        $this->calc();
        $this->emit('updateSidebar');
    }

    //yeni kayıtlarda
    public function inserting()
    {
        $this->calc();
        $this->emit('updateSidebar');
    }

    //neler güncellenecek
    public function calc()
    {
        $this->sideBar = Sidebar::where('name','like','%'.$this->search.'%')
                ->orWhere('link','like','%'.$this->search.'%')
                ->orderBy('line')->orderBy('link')->get();
    }

    //formdan gelen ilk click insert update delete ayrımı
    public function process($itemId,$action)
    {
        $this->itemId = $itemId;
        $this->action = $action;   
        
        if($action == 'delete'){
            
            $this->dispatchBrowserEvent('sideBarDeleteModalShow');
            
        }else{
            
            if($action == 'insert'){

                $this->clearItem();

            }else{
                
                $this->item = Sidebar::find($this->itemId);
                $this->data = [
                    'name'  => $this->item->name,
                    'icon'  => $this->item->icon,
                    'hid'   => $this->item->hid,
                    'link'  => $this->item->link,
                    'line'  => $this->item->line,
                ];   
                
            }
            
            $this->dispatchBrowserEvent('sideBarInsertOrUpdateModalShow');                
            
        }

    } 
    
    //yeni kayıt yada güncellenen kayıt işlemi
    public function insertOrUpdate()
    {      
        Validator::make($this->data, [
            'name' => 'required',
            'link' => 'required',
            'line' => 'required|numeric'
            
        ])->validate();        
        
        if(isset($this->data['icon'])){
            $icon = $this->data['icon'];
        }else{
            $icon = null;
        }
        
        
        if($this->action == 'insert'){
            
            Sidebar::create([
                'name' => $this->data['name'],
                'icon' => $icon,
                'link' => $this->data['link'],
                'line' => $this->data['line'],
                'hid'  => $this->itemId,
            ]);
            
            $message = trans('site.alert.data.insert.success');
            
        }else{
            
            $this->item->update([
                'name' => $this->data['name'],
                'icon' => $icon,
                'link' => $this->data['link'],
                'line' => $this->data['line'],
            ]);
            
            $message = trans('site.alert.data.update.success');
            
        }        
        

        $this->dispatchBrowserEvent('sideBarInsertOrUpdateModalHide');
        $this->updating();
        Session::flash('success',$message);

    }

    //kayıt sil
    public function delete()
    {
        Sidebar::destroy($this->itemId);
        Sidebar::where('hid',$this->itemId)->delete();
        $this->dispatchBrowserEvent('sideBarDeleteModalHide');
        $this->updating();
        Session::flash('success','Menü kaldırıldı');
    }

    //form temizle
    public function clearItem()
    {
        $this->data = [
            'name'  => "",
            'icon'  => "",
            'hid'   => "",
            'link'  => "",
            'line'  => "",
        ];
    }

    //görüntü ver
    public function render()
    {
        return view('livewire.settings.side-bar-manage');
    }
}
