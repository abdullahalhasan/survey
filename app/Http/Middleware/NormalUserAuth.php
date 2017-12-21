<?php

namespace App\Http\Middleware;

use Closure;

class NormalUserAuth
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
        if(!(\Auth::check()) || (\Auth::user()->user_type != "normal_user"))
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                \Session::flash('errormessage','You need to login to view this campaign list');
                \Session::put('pre_login_url',\URL::current());
                return redirect()->guest('sign-in'); /* default => 'auth/login' */
            }
        }
        return $next($request);
    }
}
