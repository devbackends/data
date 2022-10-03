<?php

namespace Devvly\Core\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ApiKey;

class SubscriptionMiddlware
{
  /**
   * Check if the seller profile is filled in
   * @param Request $request
   * @param Closure $next
   * @return RedirectResponse|mixed
   */
  public function handle(Request $request, Closure $next)
  {

    if(auth()->guard('web')->check()){
      if(!auth()->guard('web')->user()->email_verified_at){
        return redirect()->intended(route('core.verify'));
      }
      if(auth()->guard('web')->user()->subscription==0){
        auth()->guard('web')->logout();
        Session()->flush();
        return redirect()->intended(route('core.subscription'))->with('error','Subscription is required');
      }
    }
    return $next($request);
  }
}
