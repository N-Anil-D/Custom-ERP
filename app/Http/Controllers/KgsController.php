<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Kgs\KgsKimlikImport;
use App\Models\KgsKimlikleri;

class KgsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:kgs');
    }

    public function index(){

        $title = 'KGS';
        return view('kgs.dashboard', ['title' => $title]);
    }

}
