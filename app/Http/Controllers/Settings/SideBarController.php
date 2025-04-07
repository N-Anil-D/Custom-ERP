<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SideBarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:settings');
    }

    public function index()
    {
        $title = 'Side menü yönetimi';
        return view('settings.sidebar.index',compact('title'));
    }


}
