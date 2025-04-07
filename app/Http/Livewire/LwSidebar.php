<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sidebar;
use Illuminate\Support\Facades\Auth;

class LwSidebar extends Component
{
    public $sideBar = [];
    protected $listeners = [
        'updateSidebar' => '$refresh'
    ];

    public function boot()
    {
        $this->calc();
    }
    
    public function hydrate()
    {
        $this->calc();
    }

    public function calc()
    {
        $this->sideBar = Sidebar::with('sidToSub')->orderBy('hid')->orderBy('line')->get();        
    }

    public function navbar()
    {
        Auth::user()->sidebar = (!Auth::user()->sidebar);
        Auth::user()->save();
    }

    public function render()
    {
        return view('livewire.lw-sidebar', [
            'userBar' => Auth::user()->where('id',Auth::user()->id)->first()
        ]);
    }
}
