<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
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
        if(!(\Auth::check()) || (\Auth::user()->user_type != "admin"))
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                \Session::flash('errormessage','Invalid Request');
                \Session::put('pre_login_url',\URL::current());
                return redirect()->guest('/auth/login'); /* default => 'auth/login' */
            }
        }

        return $next($request);
    }
}
