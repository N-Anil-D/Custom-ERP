<?php

namespace App\Http\Livewire\Part;

use Livewire\Component;

class WaitApproval extends Component
{
    protected $listeners = [
        'updateNotifications' => '$refresh'
    ];
    
    public function render()
    {
        return view('livewire.part.wait-approval');
    }
}
