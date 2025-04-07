<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PsBoxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Parola Kutusu';
        return view('auth.psbox', compact('title'));
    }
}
