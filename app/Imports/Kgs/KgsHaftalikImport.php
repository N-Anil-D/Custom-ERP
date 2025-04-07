<?php

namespace App\Imports\Kgs;

use App\Models\Kgs\{KgsKimlikleri,KgsGiris,KgsCikis};
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class KgsHaftalikImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function __construct(){
        KgsGiris::truncate();
        KgsCikis::truncate();
    }

    
    public function model(array $row)
    {
        $kgsID = $row['kgs_id'];
        if(!is_null(KgsKimlikleri::where('kgs_id',$kgsID)->first())){
            $arrayGiris = [
                $row['pazartesi_g'],
                $row['sali_g'],
                $row['carsamba_g'],
                $row['persembe_g'],
                $row['cuma_g'],
                $row['cumartesi_g'],
                $row['pazar_g'],
            ];
        
            $arrayCikis = [
                $row['pazartesi_c'],
                $row['sali_c'],
                $row['carsamba_c'],
                $row['persembe_c'],
                $row['cuma_c'],
                $row['cumartesi_c'],
                $row['pazar_c'],
            ];

            //mesai 8:30 giriş olarak kabul ediliyorsa 7 9 arası girişleri 8:30 a ayarla
            $fixedArrayGiris = [];
            foreach ($arrayGiris as $value) {
                $currentDate     = Carbon::parse($value);
                $currentHour     = $currentDate->format('H');
                if($value == null){
                    //do nothing        
                }else if ($currentHour == 7 || $currentHour == 8) {
                    $currentYear     = $currentDate->format('Y');
                    $currentMonth     = $currentDate->format('m');
                    $currentDay     = $currentDate->format('d');
                    $value = Carbon::create($currentYear, $currentMonth, $currentDay, 8, 30)->toDateTimeString();
                }else{
                    $value = $currentDate->toDateTimeString();
                }
                array_push($fixedArrayGiris,$value);
            }
            $fixedArrayGiris=array_filter($fixedArrayGiris);
            //mesai 8:30 giriş olarak kabul ediliyorsa 7 9 arası girişleri 8:30 a ayarla

            //mesai 17:30 çıkış olarak kabul ediliyorsa 17 18 arası girişleri 17:30 a ayarla
            $fixedArrayCikis = [];
            foreach ($arrayCikis as $value) {
                $currentDate     = Carbon::parse($value);
                $currentHour     = $currentDate->format('H');
                if($value == null){
                    //do nothing        
                }else if ($currentHour == 17) {
                    $currentYear     = $currentDate->format('Y');
                    $currentMonth     = $currentDate->format('m');
                    $currentDay     = $currentDate->format('d');
                    $value = Carbon::create($currentYear, $currentMonth, $currentDay, 17, 30)->toDateTimeString();
                }else{
                    $value = $currentDate->toDateTimeString();
                }
                array_push($fixedArrayCikis,$value);
            }
            $fixedArrayCikis=array_filter($fixedArrayCikis);

            //mesai 17:30 çıkış olarak kabul ediliyorsa 17 18 arası girişleri 17:30 a ayarla

            foreach ($fixedArrayGiris as $girisRow) {
                $girisKayit = new KgsGiris;
                    $girisKayit['kgs_id'] = $kgsID;
                    $girisKayit['giris'] = $girisRow;
                    $girisKayit->save();
            }
            foreach ($fixedArrayCikis as $cikisRow) {
                $girisKayit = new KgsCikis;
                    $girisKayit['kgs_id'] = $kgsID;
                    $girisKayit['cikis'] = $cikisRow;
                    $girisKayit->save();
            }
            
            return ;
        }

    }
}
