<?php
namespace App\Http\Middleware;

use Closure;

use Auth;

class AuthenticateUser
{
   public function handle($request, Closure $next)
   {
 
       if (! Auth::guard('web_user')->check()) {
          return redirect(url('/'))->with('error', 'Please login to user account first!');
       }

       return $next($request);
   }
}