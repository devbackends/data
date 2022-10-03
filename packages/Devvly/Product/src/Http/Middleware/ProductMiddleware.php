<?php

namespace Devvly\Product\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ApiKey;

class ProductMiddleware
{
  /**
   * Check if the seller profile is filled in
   * @param Request $request
   * @param Closure $next
   * @return RedirectResponse|mixed
   */
  public function handle(Request $request, Closure $next)
  {


    $data=$request->all();
    if(!isset($data['distributor_api_key'])){
      return response()->json([
        'message' => 'distributor_api_key is Required',
        'status' => 403
      ],403);
    }
    //check user has ffl_api_key
    $api_key=ApiKey::Where('api_key',$data['distributor_api_key'])->get()->first();
    if(!$api_key){
      return response()->json([
        'message' => 'You Have Entered A wrong Distributor Api Key',
        'status' => 403
      ],403);
    }else{
      $user = User::find($api_key->uid);
      if (!$user->email_verified_at) {
        return response()->json(['message' => 'You need to verify your account first, we have sent you an email to complete the process',
          'status' => 403], 403);
      }
    }
    return $next($request);
  }
}
