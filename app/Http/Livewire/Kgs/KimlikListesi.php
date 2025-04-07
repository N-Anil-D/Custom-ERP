<?php

namespace App\Http\Livewire\Kgs;

use Livewire\{Component, WithPagination};
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Kgs\KgsKimlikleri;
use App\Exports\Kgs\{OrnekKgsKimlikImport};
use App\Imports\Kgs\{KgsKimlikImport};

class KimlikListesi extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $traffic;
    public $action;
    public $search;
    public $kgsUser=[];

    public function render()
    {
        return view('livewire.kgs.kimlik-listesi',[
            
            'kgsKimlikleri'=>KgsKimlikleri::where('name', 'like', '%' . $this->search . '%')->paginate(10),
        ]);
    }

    public function exampleInsertUsers(){
        
        return new OrnekKgsKimlikImport();
    }

    public function process($traffic,$action){
        $this->traffic = $traffic;
        $this->action = $action;
        if ($this->traffic == 1) {
            $this->dispatchBrowserEvent('importKgsUserShow');
        }

    }

    public function importUsers(){
        Validator::validate($this->kgsUser, [
            'file' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new KgsKimlikImport, $this->kgsUser['file']);
        $this->dispatchBrowserEvent('importKgsUserHide');
    }


}
