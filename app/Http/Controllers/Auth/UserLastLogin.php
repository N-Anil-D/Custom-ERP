<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLastLogin extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authority:son-kayit');
    }

    public function userLastAction(){
        $title = 'Kullanıcı Son İşlemleri';
        return view('auth.user-last-action', compact('title'));
    }
}
