<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->role==null){
           
               return redirect()->route('home');
           }
            // Debug line to confirm if middleware is called
       if($request->user()->role!='admin'){
        session()->flash('error', 'You do not have permission to access this page');
           return redirect()->route('profile');
       }
        return $next($request);
    }
}
