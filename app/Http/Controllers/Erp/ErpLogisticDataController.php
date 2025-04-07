<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErpLogisticDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:storage-data');
    }

    public function warehouseDatas()
    {
        $title = 'ERPlogistic Bilgi Bankası';
        return view('erp.warehouse.logistic-data', compact('title'));
    }

}