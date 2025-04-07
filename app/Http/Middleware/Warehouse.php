<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Log\Log;

class Warehouse
{
    public function handle(Request $request, Closure $next)
    {
        Log::createLog();


        if(Auth::user()->warehouses->count() == 0){
            
            session()->flash('error', trans('site.accessDenied'));
            return redirect()->route('index');

        }

        return $next($request);
    }
}
