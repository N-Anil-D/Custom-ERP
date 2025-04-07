<?php

namespace App\Http\Controllers\Fixtures;

use App\Http\Controllers\Controller;
use App\Models\FixturesItem;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Str;

class FixturesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:demirbas');
    }

    public function index()
    {
        $title = 'Demirbaş ürünlerim';
        return view('fixture.index', compact('title'));
    }

    public function download($id, $type)
    {
        $labels = FixturesItem::where('id', $id)->get();
        if($id == '0'){
            $labels = FixturesItem::get();
        }
        //type = point
        //1 mm = 2,8346456693 point
        //30*20
        $point  = 2.8346456693;
        # etiket boyutu en x boy
        $width  = 70;
        $height = 50;

        $customPaper = array(0, 0, $width * $point, $height * $point);
        $pdf = PDF::loadView('fixture.label',compact('labels'))->setPaper($customPaper);

        if($type == 'indir'){
            return $pdf->download(Str::padleft($id, 5, '0').'_'.date('Ymd_Hi').'_demirbas_barkodlari.pdf');
        }
        
        return $pdf->stream();
    }
}
