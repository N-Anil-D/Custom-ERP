<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Erp\Item\{ErpItem,ErpItemsWarehouses};
use App\Models\User;
use App\Models\Erp\Warehouse\ErpWarehouse;
use App\Models\Erp\Warehouse\ErpProductionRecipe;
use App\Models\Kgs\KgsKimlikleri;
use App\Models\Erp\Warehouse\ErpFinishedProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\{ErpItemEkle,ErpWarehouseItems,ErpItemLocationImport,ErpFinishedProducts};

class DevFunctions extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('developer.access');
    }

    public function importErpItems(Request $request){
        Validator::validate($request->all(), [
            'importFile' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new ErpItemEkle, $request->importFile);
        dd('Ürünler Eklendi.');
    }

    public function importErpItemsToWarehouse(Request $request){
        Validator::validate($request->all(), [
            'importFile' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new ErpWarehouseItems, $request->importFile);
        dd('Ürünler Eklendi.');
    }

    public function importErpItemLocations(Request $request){
        Validator::validate($request->all(), [
            'importFile' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new ErpItemLocationImport, $request->importFile);
        dd('Ürün Rafları Eklendi.');
    }

    public function importErpFinishedProducts(Request $request){
        ErpFinishedProduct::truncate();
        Validator::validate($request->all(), [
            'importFile' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new ErpFinishedProducts, $request->importFile);
        dd('Bitmiş Ürünler Eklendi.');
    }

    public function index(){
        return view('dev-blade');
    }

    public function sqlUpdate(){
        // $users = User::get();
        $i=0;
        // $pn=998;
        // $ti=100000001;
        // foreach ($users as $user) {
        //     if ($user->id != 8) {
        //         $user->name = 'user'.$i;
        //         $user->email = 'user'.$i.'@user.com';
        //         $user->tel_no = '('.$pn.') 999 9999';
        //         $user->telegram_id = $ti;
        //         $user->save();
        //         $i++;
        //         $pn--;
        //         $ti++;
        //     }
        // }
        ########################################################
        // $items = ErpItem::withTrashed()->get();
        // foreach ($items as $item) {
        //     $item->name = 'item'.$i;
        //     $item->content = 'item'.$i;
        //     $item->save();
        //     $i++;
        // }
        ########################################################
        // $items = ErpWarehouse::get();
        // foreach ($items as $item) {
        //     $item->name = 'Room'.$i;
        //     $item->save();
        //     $i++;
        // }
        ########################################################
        $kgs_users = KgsKimlikleri::get();
        foreach ($kgs_users as $kgs_user) {
            $kgs_user->name = 'Worker'.$i;
            $kgs_user->save();
            $i++;
        }
    dd(
        'Query Injected'
    );

    }

} 