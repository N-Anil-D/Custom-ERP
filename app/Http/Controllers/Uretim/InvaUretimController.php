<?php

namespace App\Http\Controllers\Uretim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\UretimPackageBrcLine;
use App\Log\Log;
use App\Models\UretimTubeBrcLine;
use App\Models\UretimSerumBrcLine;
use App\Models\UretimMiniBrcLine;


class InvaUretimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:uretim');
    }


    // barkod oluştur - paket
    public function barcodePackage()
    {
        $title = 'Barkod oluştur - Paket';
        return view('uretim.barcode-package.index',compact('title'));
    }
    
    public function packageDownload($id)
    {
        
        $barcodes = UretimPackageBrcLine::where('listId',$id)->get();
        //type = point
        //1 mm = 2.8346456693 point
        
        
        //100x100 atlas etiket eski version
        //$customPaper = array(0,0,283.46,283.46);
        //$pdf = PDF::loadView('uretim.barcode-package.old-version-detail',compact('barcodes'))->setPaper($customPaper);
        
        
        $customPaper = array(0,0,283.46456693,566.92913386);
        $pdf = PDF::loadView('uretim.barcode-package.detail',compact('barcodes'))->setPaper($customPaper);
        return $pdf->download($id.'_'.date('Ymd_Hi').'_barcode.pdf');
        //return $pdf->stream();

    }
    
    public function packageSee($id)
    {
        $barcodes = UretimPackageBrcLine::where('listId',$id)->get();
        return view('uretim.barcode-package.detail',compact('barcodes'));
    } 


    // barkod oluştur - tüp 
    public function barcodeTube()
    {
        $title = 'Barkod oluştur - Tüp';
        return view('uretim.barcode-tube.index',compact('title'));
    }
    
    public function tubeDownload($id)
    {
        $barcodes = UretimTubeBrcLine::where('listId',$id)->get();
        //50*18
        $customPaper = array(0,0,141.73228346,51.023622047);
        $pdf = PDF::loadView('uretim.barcode-tube.detail',compact('barcodes'))->setPaper($customPaper);
        
        //return $pdf->stream();
        return $pdf->download($id.'_'.date('Ymd_Hi').'_barcode.pdf');
    }
    
    public function tubeSee($id)
    {
        $barcodes = UretimTubeBrcLine::where('listId',$id)->get();
        return view('uretim.barcode-tube.detail',compact('barcodes'));
        
    }

    // barkod oluştur - serum
    public function barcodeSerum()
    {
        $title = 'Barkod oluştur - Serum';
        return view('uretim.barcode-serum.index',compact('title'));
    }

    public function serumDownload($id)
    {
        $barcodes = UretimSerumBrcLine::where('listId',$id)->get();
        //150*90
        $customPaper = array(0,0,425.19685039,255.11811024);
        $pdf = PDF::loadView('uretim.barcode-serum.detail',compact('barcodes'))->setPaper($customPaper);
        
        //return $pdf->stream();
        return $pdf->download($id.'_'.date('Ymd_Hi').'_barcode.pdf');
    }

    public function serumSee($id)
    {
        $barcodes = UretimSerumBrcLine::where('listId',$id)->get();
        return view('uretim.barcode-serum.detail',compact('barcodes'));
    }

    // diğer etiketler
    public function mini($type)
    {
        session()->put('ticketType',$type);
        $title = 'Barkod oluştur - Diğer etiketler / ('.$type.')';
        return view('uretim.mini.index',compact('title','type'));
    }

    public function miniDownload($type,$id)
    {
        $barcodes = UretimMiniBrcLine::where('listId',$id)->get();

        //type = point
        //1 mm = 2.8346456693 point

        switch ($type) {
            case 'package':
                //120*60
                $customPaper = array(0,0,340.15748031,170.07874016);
                break;
            case 'tube':
                //15*25
                $customPaper = array(0,0,42.519685039,70.866141732);
                break;
            case 'besiyeri-koli':
                //150*90
                $customPaper = array(0,0,425.19685039,255.11811024);
                break;
            case 'besiyeri-tup':
                //50*18
                $customPaper = array(0,0,141.73228346,51.023622047);
                break;
        }

        $pdf = PDF::loadView('uretim.mini.detail-'.session()->get('ticketType'),compact('barcodes'))->setPaper($customPaper);
        
        //return $pdf->stream();
        return $pdf->download($id.'_'.date('Ymd_Hi').'_barcode.pdf');

    }

    public function miniSee($type,$id)
    {
        $barcodes = UretimMiniBrcLine::where('listId',$id)->get();
        return view('uretim.mini.detail-'.session()->get('ticketType'),compact('barcodes'));

    }
    
}
