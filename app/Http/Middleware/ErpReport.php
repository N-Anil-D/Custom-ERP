<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ErpReport
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->can_request_report){
            return $next($request);
        }else{
            session()->flash('error','Rapor almada erişimiz sadece kendi üretimleriniz ile sınırlandırılmıştır.');         
            // return redirect()->route('index');
            return redirect()->back();
        }
    }
}
