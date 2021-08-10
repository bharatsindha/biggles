<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class Acl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeName = \Request::route()->getName();
        if(!$request->ajax()) {
            if (!Permission::checkAccessAllow($routeName) && $routeName!='lane.inter_state' ) {
                Session::flash('error', "You don't have permission to access this module. Kindly contact administration.");
                return redirect('dashboard');
            }
        }
        return $next($request);
    }
}
