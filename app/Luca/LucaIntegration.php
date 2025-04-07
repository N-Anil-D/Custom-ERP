<?php

namespace App\Luca;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;

class LucaIntegration
{
    
    
    public function getLogin()
    {
        
        $serviceUrl = 'service/login';
        $url = env('LUCA_URL').$serviceUrl;
        
        $resBody = [
            'musteriId' => env('LUCA_MUSTERIID'),
            'kullaniciKodu' => env('LUCA_KULLANICIKODU'),
            'parola' => env('LUCA_PAROLA'),
            'firmaId' => env('LUCA_FIRMAID'),
            'donemKod' => env('LUCA_DONEMKOD'),
        ];
        
        $jHeaders = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8'
        ];
        
        $response = Http::withHeaders($jHeaders)->post($url, $resBody);
        $data = $response->json();
        
        if(isset($data['error'])){
            return FALSE;
        }else{
            
            Session::put('jsession_id','JSESSIONID='.Arr::get($data,'session.jsession_id'));
            return TRUE;
        }
        
        
    }
    
    public function getStockList()
    {
        $serviceUrl = 'service/rest/stok/list';
        $url = env('LUCA_URL').$serviceUrl;
        
        $jHeaders = [
            'Cookie' => Session::get('jsession_id'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8'
        ];

        $resBody = [
            'pageNumber' => 1,
        ];

        $response = Http::withHeaders($jHeaders)->post($url, $resBody);
        $data = $response->json();
        
        $dongu = ceil($data['collection']['fullListSize']/$data['collection']['pageSize']);
        $resData = [];
            
            for($i = 0; $i < $dongu; $i++){
                
                $resBody = [
                    'pageNumber' => $i+1,
                ];
                
                $response = Http::withHeaders($jHeaders)->post($url, $resBody);
                $data = $response->json();
                
                foreach($data['collection']['list'] as $row){
                    array_push($resData,Arr::dot($row));
                }
                
            }
            
        return $resData;
    }
    
    public function getStock($code)
    {
        
        $serviceUrl = 'service/rest/stok/select';
        $url = env('LUCA_URL').$serviceUrl;
        
        $jHeaders = [
            'Cookie' => Session::get('jsession_id'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8'
        ];
        
        $resBody = [
            'stokKartKodu' => $code,
            'bakiyeli' => 'E'
        ];
        
        $response = Http::withHeaders($jHeaders)->post($url, $resBody);
        $data = $response->json();
        
        return Arr::dot($data['json']);
        
    }
}
