<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LogoutUsers
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
        $user = Auth::user();
        if (!empty($user->logout) && $user->logout > 0) {
            $user->logout = 0;
            $user->save();

            // Log her out
            Auth::logout();

            $request->session()->invalidate();

            return redirect()->route('login');
        }
        return $next($request);
    }
}
