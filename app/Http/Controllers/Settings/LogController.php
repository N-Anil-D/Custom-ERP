<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:settings');
    }
    
    public function index()
    {
        $title = 'Kullanıcı Logları';
        return view('settings.log.index', compact('title'));
    }

}
