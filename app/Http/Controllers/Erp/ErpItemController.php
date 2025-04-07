<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErpItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:erp-urun-islemleri');
    }

    public function warehouses()
    {
        $title = 'Depolar';
        return view('erp.item.warehouses', compact('title'));
    }
    
    public function units()
    {
        $title = 'Birimler';
        return view('erp.item.units',compact('title'));
    }

    public function items()
    {
        $title = 'Ürünler';
        return view('erp.item.items',compact('title'));
    }

    public function variety()
    {
        $title = 'Ürün Çeşitleri';
        return view('erp.item.variety',compact('title'));
    }

}
