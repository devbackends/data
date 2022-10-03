<?php
namespace Devvly\Ffl\Http\Middleware;
use App\Models\ApiKey;
use App\Models\Subscription;
use App\Models\Package;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
class FflMiddleware {
  /**
   * Check if the seller profile is filled in
   *
   * @param  Request  $request
   * @param  Closure  $next
   * @return RedirectResponse|mixed
   */
  public function handle(Request $request, Closure $next) {

    $data = $request->all();
    if (!isset($data['ffl_api_key'])) {
      return response()->json(['message' => 'ffl_api_key is Required',
        'status' => 403], 403);
    }
    //check user has ffl_api_key
    $api_key = ApiKey::Where('api_key', $data['ffl_api_key'])->get()->first();
    if (!$api_key) {
      return response()->json(['message' => 'You Have Entered A wrong Ffl Api Key',
        'status' => 403], 403);
    } else {
      $subscription = Subscription::where('uid', $api_key->uid)->where('package', 'like', 'Ffl%')->first();
      $user = User::find($api_key->uid);
      if ($user->email_verified_at) {
        if (!$subscription) {
          return response()->json(['message' => 'You are not subscribed to ffl',
            'status' => 403], 403);
        } else {
          $package = $subscription->package;
          if ($package != 'Ffl Pro' && $package != 'Ffl Advanced' && $package != 'Ffl Lite' && $package != 'Ffl Free') {
            return response()->json(['message' => 'You are not subscribed to ffl',
              'status' => 403], 403);
          } else {
            $end = $subscription->end;
            if ($end < now()) {
              return response()->json(['message' => 'You subscription has been expired',
                'status' => 403], 403);
            }
            $package_max_request_per_day= Package::where('name', $package)->first();
            if($package_max_request_per_day){
              if($subscription->calls_number >= $package_max_request_per_day->max_requests){
                return response()->json(['message' => 'You have exceed your maximum allowed requests per day',
                  'status' => 403], 403);
              }
            }
          }
        }
      } else {
        return response()->json(['message' => 'You need to verify your account first, we have sent you an email to complete the process',
          'status' => 403], 403);
      }
    }
    return $next($request);
  }
}