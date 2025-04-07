<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserLog;

class Logs extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public function refresh()
    {
        $this->render();
    }
    
    public function render()
    {
        return view('livewire.settings.logs',[
            'data' => UserLog::with('logToUsr')->orderBy('id','desc')
                ->paginate(30),                
        ]);
    }
}
