<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErpEndProduct extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:urun-bitirme');
    }

    public function sekk(){
        $title = 'Steril - Etiket - Kalite - Kutu';
        return view('erp.warehouse.sekk', compact('title'));
    }

    public function finishedProducts()
    {
        $title = 'Bitmiş Ürünler';
        return view('erp.warehouse.finished-products', compact('title'));
    }

    public function sendProducts()
    {
        $title = 'Gönderilmiş Ürünler';
        return view('erp.warehouse.send-products', compact('title'));
    }

}
