<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\UserLastAction;

class LastAction extends Component
{

    public function render()
    {
        return view('livewire.auth.last-action',[
            'datas' => UserLastAction::orderByDesc('updated_at')->get(),
        ]);
    }


}
