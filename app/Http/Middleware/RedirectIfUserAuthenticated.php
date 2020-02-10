<?php
namespace App\Http\Middleware;

use Closure;

use Auth;

class RedirectIfUserAuthenticated
{

  public function handle($request, Closure $next)
  {
 
      if (Auth::guard()->check()) {
          return redirect('/');
      }

      if (Auth::guard('web_user')->check()) {
          return redirect()->route('user-dashboard');
      }
      return $next($request);
  }
}
