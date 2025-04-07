<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\LucaStockAlert;
use App\Log\Log;
use App\Models\Erp\Warehouse\ErpProduction;
use Jenssegers\Agent\Agent;


class PortalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' =>
            [
                'detectDevice',
            ]
        ]);
    }

    public function index()
    {
        Log::createLog();

        if (Auth::user()->active) {

            $title = 'Anasayfa';
            return view('index', compact('title'));
        } else {

            Auth::logout();
            return redirect()->route('index');
        }
    }

    public function theme(Request $request)
    {
        Log::createLog();

        Auth::user()->theme = $request->theme;
        Auth::user()->save();

        return redirect()->back();
    }

    public function mailTest()
    {
        Log::createLog();

        //new user mail view test
        $data = Auth::user();
        $subData = [
            'password' => Str::upper(Str::random(8))
        ];
        return view('mail.newUserV2', compact('data', 'subData'));

        //luca stok alarm mail view test

        //        $data = LucaStockAlert::first();
        //        $subData = [];
        //        return view('mail.lucaStockAlert', compact('data','subData'));
    }

    public function mailTestV2()
    {
        Log::createLog();

        $data = LucaStockAlert::first();
        $subData = [];
        return view('mail.lucaStockAlertV2', compact('data', 'subData'));
    }

    public function template()
    {
        Log::createLog();

        $title = "Template Page";
        return view('template', compact('title'));
    }

    public function productionReport()
    {
        $data = Auth::user();
        $subData = ErpProduction::with('contents', 'item', 'warehouse', 'user')->find(1);
        return view('mail.production-report', compact('data', 'subData'));
    }

    ### old mac detect ###
    // public function detectDevice()
    // {
    //     ob_start();  
    //     system('arp -a');  
    //     $content = ob_get_contents();   
    //     ob_clean();   
    //     exec('getmac', $result);
    //     $macList = [];
    //     $i = 0;
    //     foreach($result as $key => $row){
    //         if($key > 2){
    //             $arr = explode('   ', $row);
    //             foreach($arr as $k => $r){
    //                 if($k == 0){
    //                     $macList[$i] = $r;
    //                     $i++;
    //                 }
    //             }
    //         }
    //     }
    //     dd($content,$macList);

    //     // $ipconfig =   shell_exec ("ipconfig/all");  
    //     // // display mac address   
    //     // dd($agent, $macAddr, $ipconfig);


    //     $agent      = new Agent();
    //     $device     = $agent->platform();
    //     $browser    = $agent->browser();

    //     $requestInfo = [
    //         'ip'            => \Request::ip(),
    //         'device'        => $device,
    //         'deviceVer'     => $agent->version($device),
    //         'browser'       => $browser,
    //         'browserVer'    => $agent->version($browser)
    //     ];

    //     $title = 'macList';

    //     return view('mac-request', compact('macList', 'requestInfo', 'title'));

    //     // dd($macList, $requestInfo);


    // }

    public function detectDevice(Request $request)
    {

        ob_start();
        system('arp -a');
        $content = ob_get_contents();
        ob_clean();

        $requestIp = $request->ip();
        $mac = "not detected";

        $lines  = explode("\n", $content);
        foreach($lines as $line){
            $cols = preg_split('/\s+/', trim($line));
            if (isset($cols[2]) && $cols[2] == 'dynamic') {
                if($cols[0] == $requestIp){
                    $mac = Str::upper($cols[1]);
                }
            }
        }

        $agent      = new Agent();
        $device     = $agent->platform();
        $browser    = $agent->browser();

        $requestInfo = [
            'ip'            => $requestIp,
            'mac'           => $mac,
            'device'        => $device,
            'deviceVer'     => $agent->version($device),
            'browser'       => $browser,
            'browserVer'    => $agent->version($browser)
        ];

        $title = 'macList';

        return view('mac-request', compact('requestInfo', 'title'));


       
    }
}
