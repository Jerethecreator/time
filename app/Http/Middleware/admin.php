<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redirect;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->hasAnyRole()){
            return $next($request);
        }
   
        return redirect('login');
    }
       
}
