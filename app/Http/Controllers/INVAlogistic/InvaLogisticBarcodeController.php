<?php

namespace App\Http\Controllers\INVAlogistic;

use Illuminate\Http\Request;
use PDF;
use App\Models\{InvaLogisticBrcLine,ItemDefinitionList};
use App\Http\Controllers\Controller;

class InvaLogisticBarcodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:INVAlogistic');
    }

    public function index()
    {
        $title = 'Barkod oluştur';
        return view('INVAlogistic.barcode.index',compact('title'));
    }
    
    public function index2()
    {
        $title = 'Malzeme Tanım Etiketi Oluştur';
        return view('INVAlogistic.item-definition',compact('title'));
    }
    
    public function pdf($id, $type)
    {
        $barcodes = InvaLogisticBrcLine::where('listId',$id)->get();
        $customPaper = array(0, 0, 198.42519685, 141.73228346);
        $pdf = PDF::loadView('INVAlogistic.barcode.detail',compact('barcodes', 'type'))->setPaper($customPaper);                
        return $pdf->stream($id.'_'.date('Ymd_Hi').'_'.$type.'_barcode.pdf');
    }

    public function pdf2($id)
    {
        //1 mm = 3.779527559 px
        # etiket boyutu en x boy (mm x mm)
        $point  = 3.779527559;
        $en = 75;
        $boy = 75;
        $itemDefinitions = ItemDefinitionList::where('listId',$id)->get();
        
        $customPaper = array(0, 0, $en*$point, $boy*$point);
        #sonuc 100mm x 100mm
        $pdf = PDF::loadView('INVAlogistic.item-definition-label',compact('itemDefinitions'))->setPaper($customPaper);                
        return $pdf->stream($id.'_'.date('Ymd_Hi').'_barcode.pdf');
    }
    
}
