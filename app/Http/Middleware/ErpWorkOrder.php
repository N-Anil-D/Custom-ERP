<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\{ Auth, Session };

class ErpWorkOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->work_order_level>0){
            return $next($request);
        }else{
            session()->flash('error', 'İş emirlerine erişim yetkiniz yoktur.');
            return redirect()->route('my.products');
        }
    }
}
