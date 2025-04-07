<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoadMapController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:settings');
        $this->middleware('developer.access');
    }

    public function index()
    {
        $title = 'RoadMap yönetimi';
        return view('settings.roadMap.index',compact('title'));
    }

    public function subIndex($id)
    {
        $title = "RoadMap | Alt madde işlemleri";
        return view('settings.roadMap.sub',compact('title'));
    }
    
}
