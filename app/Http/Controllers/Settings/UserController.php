<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:settings');
    }

    public function index()
    {
        $title = 'Kullanıcı yönetimi';
        return view('settings.user.index',compact('title'));
    }
    
}
