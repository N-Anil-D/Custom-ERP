<?php

namespace App\Http\Livewire\Kgs;

use Livewire\{Component, WithPagination};
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Kgs\KgsHaftalikImport;
use App\Exports\Kgs\HaftalikKgsRaporu;
use Carbon\Carbon;
use App\Models\Kgs\{KgsPuantaj,KgsHataliPuantaj,KgsGiris,KgsKimlikleri};

class Haftalik extends Component
{
    use WithFileUploads;
    use WithPagination;
    
    public $traffic;
    public $action;
    public $search;
    public $kgsHaftalik=[];

    public function render()
    {
        return view('livewire.kgs.haftalik');
    }

    public function exampleWeeklyShema(){
        return response()->download(storage_path('OrnekHaftalikCizelge.xlsx'));
    }

    public function process($traffic,$action){
        $this->traffic = $traffic;
        $this->action = $action;
        if ($this->traffic == 2) {
            $this->dispatchBrowserEvent('importKgsHaftalikShow');
        }
    }

    public function importAndCalculateHaftalikPuantaj(){
        Validator::validate($this->kgsHaftalik, [
            'file' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new KgsHaftalikImport, $this->kgsHaftalik['file']);
        $this->dispatchBrowserEvent('importKgsHaftalikHide');
    }

    public function calculate(){
        KgsPuantaj::truncate();
        $this->activity();
        return Excel::download(new HaftalikKgsRaporu, 'Haftalık Puantaj - '.Carbon::now()->toDateTimeString().'.xlsx');
    }

    private function silGirisCikis($kgsUser){//kullanılan kaydı sil (SoftDelete)
        if (!is_null($kgsUser->kgsUsrToLogin)) {
            $kgsUser->kgsUsrToLogin->delete();
        }
        if (!is_null($kgsUser->kgsUsrToLogout)) {
            $kgsUser->kgsUsrToLogout->delete();
        }
    }

    private function giris0cikis0($kgsUser){
        $puantajKaydi = KgsPuantaj::where('kgs_id',$kgsUser->kgs_id)->first();
        if(!is_null($puantajKaydi)){
            $puantajKaydi->calisma_dakika = $puantajKaydi->calisma_dakika+0;
            $puantajKaydi->save();
        }else{
            $puantaj = new KgsPuantaj;
            $puantaj->kgs_id = $kgsUser->kgs_id;//kgs_id
            $puantaj->calisma_dakika = 0;
            $puantaj->save();
        }
        $this->silGirisCikis($kgsUser);
    }
    private function giris1cikis0($kgsUser,$giris){
        $multiLogin = $kgsUser->kgsUsrToLoginMultiple;
        if ($multiLogin->count() > 1) {//girişi olan biri çıkış yapmadan başka giriş yaparsa 8 saatlik mesai yaz
            $enErkenGiris = Carbon::parse($kgsUser->kgsUsrToLoginMultiple->first()->giris);
            $multiLogin->shift();
            // foreach ($multiLogin as $login) {//8 saat içerisindeki çoklu kayıtları silip en erken olanını bırak
            //     if(!is_null($login->giris)){
            //         if ($enErkenGiris->diffInMinutes(Carbon::parse($login->giris)) < 480) {
            //             $login->delete();
            //         }
            //     }
            // }
            $giris = $enErkenGiris;
            $currentYear    = $giris->format('Y');
            $currentMonth   = $giris->format('m');
            $currentDay     = $giris->format('d');
            $girisSifir = Carbon::create($currentYear, $currentMonth, $currentDay, 0, 0);
            $girisSabah = Carbon::create($currentYear, $currentMonth, $currentDay, 5, 0);
            $girisGunici = Carbon::create($currentYear, $currentMonth, $currentDay, 16, 30);
            $girisAksam = Carbon::create($currentYear, $currentMonth, $currentDay, 20, 0);
            if($giris->between($girisSifir,$girisSabah)){//giriş sabah 00:00 ve 05:00 arasındaysa çıkışı 8:30 a yaz
                $cikis = Carbon::create($currentYear, $currentMonth, $currentDay, 8, 30);
            }else if ($giris->between($girisSabah,$girisGunici)){//giriş sabah 05 ve 16:30 arasındaysa çıkışı 17:30 a yaz
                $cikis = Carbon::create($currentYear, $currentMonth, $currentDay, 17, 30);
            }else if ($giris->between($girisGunici,$girisAksam)){//giriş sabah 17:30 ve 20:00 arasındaysa çıkışı ertesi gün 00:30 a yaz
                $cikis = Carbon::create($currentYear, $currentMonth, $currentDay+1, 00, 30);
            }else{//giriş sabah 20:00 sonrasındaysa çıkışı 17:30 a yaz
                $cikis = Carbon::create($currentYear, $currentMonth, $currentDay+1, 00, 30);
            }
            $puantajKaydi = KgsPuantaj::where('kgs_id',$kgsUser->kgs_id)->first();
            $calismaSuresi = $giris->diffInMinutes($cikis);
            if(!is_null($puantajKaydi)){
                $puantajKaydi->calisma_dakika = $puantajKaydi->calisma_dakika + $calismaSuresi;
                $puantajKaydi->save();
            }else{
                $puantaj = new KgsPuantaj;
                $puantaj->kgs_id = $kgsUser->kgs_id;
                $puantaj->calisma_dakika = $giris->diffInMinutes($cikis);
                $puantaj->save();
            }
            $this->silGirisCikis($kgsUser);
        }else {
            if (Carbon::now() > Carbon::parse($giris)->addHours(36)) {//girişten itibareen 36 saat geçmişse ve başka giriş/çıkış işlemi yok ise 8 saatlik mesai sayılır
                $cikis = Carbon::parse($giris)->addHours(8);
                $puantajKaydi = KgsPuantaj::where('kgs_id',$kgsUser->kgs_id)->first();
                $calismaSuresi = $giris->diffInMinutes($cikis);
                if(!is_null($puantajKaydi)){
                    $puantajKaydi->calisma_dakika = $puantajKaydi->calisma_dakika + $calismaSuresi;
                    $puantajKaydi->save();
                }else{
                    $puantaj = new KgsPuantaj;
                    $puantaj->kgs_id = $kgsUser->kgs_id;
                    $puantaj->calisma_dakika = $giris->diffInMinutes($cikis);
                    $puantaj->save();
                }
                $this->silGirisCikis($kgsUser);
            }
        }    
    }
    private function giris0cikis1($kgsUser, $cikis){
        $hataliPuantaj = new KgsHataliPuantaj;
        $hataliPuantaj->kgs_id = $kgsUser->kgs_id;
        $hataliPuantaj->giris = null;
        $hataliPuantaj->cikis = $cikis->format('Y-m-d H:i:s');
        $hataliPuantaj->save();
        $kgsUser->kgsUsrToLogout->delete();
    }
    private function giris1cikis1($kgsUser, $giris, $cikis){
        if ($cikis < $giris) {
            if($kgsUser->kgsUsrToLogoutMultiple->count() >= 2){
                $sonrakiCikis = $kgsUser->kgsUsrToLogoutMultiple->where('cikis','>',$giris)->first()->cikis;
                $anlamsizCikislar = $kgsUser->kgsUsrToLogoutMultiple->where('cikis','<',$giris);
                foreach ($anlamsizCikislar as $anlamsizCikis) {
                    $anlamsizCikis->delete();
                }
                $cikis = Carbon::parse($sonrakiCikis);
            }else{
                $cikis=Carbon::parse($giris)->addHours(8)->addMinute(30);
            }
        }
        $puantajKaydi = KgsPuantaj::where('kgs_id',$kgsUser->kgs_id)->first();
        $from =  Carbon::parse($giris)->addMinute(5);
        $to =  Carbon::parse($cikis);
        $cikisVeGirisArasindaBaskaGiris = KgsGiris::where('kgs_id',$kgsUser->kgs_id)->whereBetween('giris', [$from, $to])->get()->count();
        if($cikisVeGirisArasindaBaskaGiris){
            $cikis=Carbon::parse($giris)->addHours(8)->addMinute(30);
        }
        $calismaSuresi = $giris->diffInMinutes($cikis);
        if(!is_null($puantajKaydi)){
            $puantajKaydi->calisma_dakika = $puantajKaydi->calisma_dakika + $calismaSuresi;
            $puantajKaydi->save();
        }else{
            $puantaj = new KgsPuantaj;
            $puantaj->kgs_id = $kgsUser->kgs_id;
            $puantaj->calisma_dakika = $giris->diffInMinutes($cikis);
            $puantaj->save();
        }
        if($cikisVeGirisArasindaBaskaGiris){
            $kgsUser->kgsUsrToLogin->delete();
        }else{
            $this->silGirisCikis($kgsUser);
        }
    }

    public function activity(){
        for($i=0;$i<7;$i++){
            foreach (KgsKimlikleri::all() as $kgsUser) {
                if(!is_null($kgsUser->kgsUsrToLogin) && !is_null($kgsUser->kgsUsrToLogout)){
                    $giris = Carbon::parse($kgsUser->kgsUsrToLogin->giris);
                    $cikis = Carbon::parse($kgsUser->kgsUsrToLogout->cikis);
                    if (is_null($kgsUser->kgsUsrToLogin->giris) && is_null($kgsUser->kgsUsrToLogout->cikis)) {
                        
                        $this->giris0cikis0($kgsUser);
                        
                    }else if (is_null($kgsUser->kgsUsrToLogin->giris) && !is_null($kgsUser->kgsUsrToLogout->cikis)) {
                        
                        $this->giris0cikis1($kgsUser,$cikis);
                        
                    }else if(!is_null($kgsUser->kgsUsrToLogin->giris) && is_null($kgsUser->kgsUsrToLogout->cikis)){
                        
                        $this->giris1cikis0($kgsUser, $giris);
                        
                    }else if(!is_null($kgsUser->kgsUsrToLogin->giris) && !is_null($kgsUser->kgsUsrToLogout->cikis)){
                        
                        $this->giris1cikis1($kgsUser,$giris,$cikis);
                        
                    }
                }else if (!is_null($kgsUser->kgsUsrToLogin) && is_null($kgsUser->kgsUsrToLogout)){
                    $giris = Carbon::parse($kgsUser->kgsUsrToLogin->giris);
                    $this->giris1cikis0($kgsUser,$giris,null);
                }else if (is_null($kgsUser->kgsUsrToLogin)){
                    $this->giris0cikis0($kgsUser);
                }
            }
        }
    }
}
