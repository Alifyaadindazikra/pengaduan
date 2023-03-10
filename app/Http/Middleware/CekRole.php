<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (in_array($request->user()->role, $roles)){
            return $next($request);
        }else{
            return redirect()->back();
        }
    }
} //...$role akan mengaubah string yang dipisah dengan koma menjadi item array, namanya spead operator
//$request->user()-.role akan mengambil datra user yag login bagian role
