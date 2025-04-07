<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Bildirimlerim (Onay vereceklerim)';
        return view('auth.my-notify', compact('title'));
    }

    public function demand()
    {
        $title = 'Taleplerim (Onay beklediklerim)';
        return view('auth.waiting-demands', compact('title'));
    }
}
