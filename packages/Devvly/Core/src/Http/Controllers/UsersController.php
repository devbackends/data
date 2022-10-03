<?php

namespace Devvly\Core\Http\Controllers;



use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Session;
use Devvly\FluidPayment\Services\FluidCharge;
use Devvly\FluidPayment\Services\FluidApi;
use Illuminate\Support\Facades\Mail;
use Devvly\Core\Mail\UserVerification;

class UsersController extends Controller
{
  /**
   * Contains route related configuration
   *
   * @var array
   */
  protected $_config;

  public function __construct()
  {
    $this->_config = request('_config');
  }

  /**
   * Function to retrieve states with respect to countries with codes and names for both of the countries and states.
   *
   * @return \Illuminate\View\View
   */
  public function login()
  {
    if(auth()->guard('web')->check()){
      return redirect()->intended(route('core.home'));
    }
    return view($this->_config['view']);
  }
  public function loginAction(){

    $this->validate(request(), [
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (auth()->guard('web')->attempt(request(['email', 'password']),true)) {
      if(auth()->guard('web')->user()->email_verified_at){
        $subscriptions= \App\Models\Subscription::where('uid',auth()->guard('web')->user()->id)->get();
       if(count($subscriptions) > 0){
         foreach ($subscriptions as $subscription){
           if(Carbon::createFromTimeString( $subscription->end) < Carbon::now()){
             return redirect()->intended(route('core.subscription'));
           }
         }
       }else{
         return redirect()->intended(route('core.subscription'));
       }
      }else{
        return redirect()->intended(route('core.login'))->with('error','You need to verify your account before, we have sent you an email to complete the process.');
      }
      return redirect()->intended(route($this->_config['redirect']));


    }else{
      return redirect()->intended(route('core.login'))->with('error','Invalid credentials');
    }
  }

  public function verifyDistributor(){

    $this->validate(request(), [
      'user' => 'required',
      'password' => 'required',
      'host' => 'required',
      'distributor' => 'required'
    ]);
    try{
      $data=request()->all();
      $host=$data['host'];
      $user=$data['user'];
      $password=$data['password'];
      $ftpConn = ftp_connect($host);
      // check connection
      if (!ftp_login($ftpConn,$user,$password)) {
        session()->flash('error', 'FTP connection has failed! Attempted to connect to '. $host. ' for user '.$user.'.'  );
        return redirect()->intended(route($this->_config['redirect']));
      } else{
        $distAccount=\Devvly\Product\Models\DistAccount::where('uid',auth()->guard('web')->user()->id)->where('distributor',$data['distributor'])->first();
        if(!$distAccount){
          $distributor= \Devvly\Product\Models\Distributor::where('name',$data['distributor'])->first();
          if($distributor){
            $distAccount=new \Devvly\Product\Models\DistAccount();
            $distAccount->uid=auth()->guard('web')->user()->id;
            $distAccount->did=$distributor->id;
            $distAccount->distributor=$distributor->name;
            $distAccount->active=1;
            $distAccount->save();
          }
        }
        $directory = ftp_nlist($ftpConn,'');
        ftp_close($ftpConn);
        session()->flash('success',  'Login Success '. $host. ' for user '.$user.'.');
        return redirect()->intended(route($this->_config['redirect']));
      }
    }catch (Exception $exception) {
      session()->flash('error', 'FTP connection has failed! Attempted to connect to '. $host. ' for user '.$user.'.'  );
      return redirect()->intended(route($this->_config['redirect']));
    }
    ftp_close($ftpConn);
  }

  public function register()
  {
    return view($this->_config['view']);
  }

  public function registerAction(){

    $this->validate(request(), [
      'email' => 'email|required|unique:users,email',
      'first_name' => 'string|required|max:35',
      'last_name' => 'string|required|max:35',
      'company_name' => 'string|required|max:35',
      'phone' => 'string|required|max:35',
      'password' => 'confirmed|min:6|required',
    ]);
    $data = request()->input();
    $user = new \App\Models\User();
    $user->first_name=$data['first_name'];
    $user->last_name=$data['last_name'];
    $user->company_name=$data['company_name'];
    $user->phone=$data['phone'];
    $user->email=$data['email'];
    $user->password= bcrypt($data['password']);
    $user->save();
    if($user){
      try {
        Mail::to($user->email)->send(new UserVerification($user));
      } catch (\Exception $e) {
        $x=$e;
      }
      session()->flash('success', 'User registered successfully');
      if (auth()->guard('web')->attempt(request(['email', 'password']),true)) {
        return redirect()->intended(route($this->_config['redirect']));
      }
    }else{
      session()->flash('error', 'Error You are not able to register');
      return redirect()->back();
    }
  }

  public function verify(){
    return view($this->_config['view']);
  }
  public function account(){
    return view($this->_config['view']);
  }
  public function verifyAccount($id){
    $id=base64_decode($id);
    $user = \App\Models\User::find($id);
    if($user){
      $user->email_verified_at= date('Y-m-d H:i:s');
      $user->save();
      session()->flash('success', 'Your account has been verified successfully');
    }else{
      session()->flash('error', 'Your account is not verified for a reason , Please contact our support team to help you');
    }
    return redirect()->intended(route($this->_config['redirect']));
  }

  public function subscription(){
    if(!auth()->guard('web')->check()){
      return redirect()->intended(route('core.login'));
    }
    $user=auth()->guard('web')->user();
    $click_request_amounts=0;
    $data['ffl_lite_expired']=0;
    $data['ffl_lite_expire_on']=false;
    $ffl_lite=\App\Models\Subscription::where('uid',$user->id)->where('package','Ffl Lite')->first();
    if($ffl_lite){
      $data['ffl_lite_expire_on']=$ffl_lite->end;
      if($ffl_lite->calls_number > 0){
        $package_record=\App\Models\Package::where('name','Ffl Lite')->first();
        if($package_record){
          $click_request_amounts+=$ffl_lite->calls_number * $package_record->cpr;
        }

      }
      if(Carbon::createFromTimeString($ffl_lite->end) < Carbon::now()){
        $data['ffl_lite_expired']=1;
      }
    }
    $data['ffl_advanced_expired']=0;
    $data['ffl_advanced_expire_on']=false;
    $ffl_advanced=\App\Models\Subscription::where('uid',$user->id)->where('package','Ffl Advanced')->first();
    if($ffl_advanced){
      if($ffl_advanced->calls_number > 0){
        $package_record=\App\Models\Package::where('name','Ffl Advanced')->first();
        if($package_record){
          $click_request_amounts+=$ffl_advanced->calls_number * $package_record->cpr;
        }
      }
      $data['ffl_advanced_expire_on']=$ffl_advanced->end;
      if(Carbon::createFromTimeString($ffl_advanced->end) < Carbon::now()){
        $data['ffl_advanced_expired']=1;
      }
    }
    $data['ffl_pro_expired']=0;
    $data['ffl_pro_expire_on']=false;
    $ffl_pro=\App\Models\Subscription::where('uid',$user->id)->where('package','Ffl Pro')->first();
    if($ffl_pro){
      $data['ffl_pro_expire_on']=$ffl_pro->end;
      if($ffl_pro->calls_number > 0){
        $package_record=\App\Models\Package::where('name','Ffl Pro')->first();
        if($package_record){
          $click_request_amounts+=$ffl_pro->calls_number * $package_record->cpr;
        }
      }
      if(Carbon::createFromTimeString($ffl_pro->end) < Carbon::now()){
        $data['ffl_pro_expired']=1;
      }
    }
    $data['distributor_rsr_expired']=0;
    $data['distributor_rsr_expire_on']=false;
    $rsr=\App\Models\Subscription::where('uid',$user->id)->where('package','Rsr')->first();
    if($rsr){
      $data['distributor_rsr_expire_on']=$rsr->end;
      if(Carbon::createFromTimeString($rsr->end) < Carbon::now()){
        $data['distributor_rsr_expired']=1;
      }
    }
    $data['distributor_zanders_expired']=0;
    $data['distributor_zanders_expire_on']=false;
    $zanders=\App\Models\Subscription::where('uid',$user->id)->where('package','Zanders')->first();
    if($zanders){
      $data['distributor_zanders_expire_on']=$zanders->end;
      if(Carbon::createFromTimeString($zanders->end) < Carbon::now()){
        $data['distributor_zanders_expired']=1;
      }
    }
    $data['distributor_davidsons_expired']=0;
    $data['distributor_davidsons_expire_on']=false;
    $davidsons=\App\Models\Subscription::where('uid',$user->id)->where('package','Davidsons')->first();
    if($davidsons){
      $data['distributor_davidsons_expire_on']=$davidsons->end;
      if(Carbon::createFromTimeString($davidsons->end) < Carbon::now()){
        $data['distributor_davidsons_expired']=1;
      }
    }
    $data['click_request_amounts']=$click_request_amounts;
    return view($this->_config['view'],compact('data'));
  }
  public function subscriptionAction(){

    $data=request()->all();
    $user=auth()->guard('web')->user();
    $charge['status']='declined';
    if(isset($data['total'])){
      $amount=0;
      //calculate the new packages subscription amount
      foreach (explode(',',$data['packages']) as $package){
        $package_record=\App\Models\Package::where('name',$package)->first();
        if($package_record){
          $amount+=$package_record->price;

          //check if there is an api_keys , if not create new one
          $type=strtolower(strtok($package, " "));
          $checkForApiKey= \App\Models\ApiKey::where('uid',$user->id)->where('type',$type)->first();
          if(!$checkForApiKey){
            $var  = Str::random(32);
            $apiKey=new \App\Models\ApiKey();
            $apiKey->uid=$user->id;
            $apiKey->type=$type;
            $apiKey->name=$package.' Api Key';
            $apiKey->api_key=$var;
            $apiKey->save();
          }
        }
      }

      //calculate the old packages calls amount
      $subscriptions=\App\Models\Subscription::where('uid',$user->id)->get();
      foreach ($subscriptions as $subscription){
        $package_record=\App\Models\Package::where('name',$subscription->package)->first();
        if($package_record){
          if($subscription->calls_number > 0 && $package_record->cpr > 0){
            $amount+=$subscription->calls_number * $package_record->cpr;
          }
        }
      }
      if($amount==0 && $data['packages'] =='Ffl Free'){
        if( strpos($package, 'Ffl') !== false){
          $ffl_packages=\App\Models\Subscription::where('uid',$user->id)->where('package','like','Ffl%')->get();
          foreach ($ffl_packages as $ffl_package){
            $ffl_package->delete();
          }
        }
          $subscription=new \App\Models\Subscription();
          $subscription->uid=$user->id;
          $subscription->package='Ffl Free';
          $subscription->end='2030-01-01 10:14:56.000000';
          $subscription->token=$data['token'];
          $subscription->save();
          $user->subscription=1;
          $user->save();
          if(!$user->email_verified_at){
            return response()->json(['status'=>'success','message'=>'You subscribed successfully','redirect' => route('core.verify')],200);
          }
          return response()->json(['status'=>'success','message'=>'You subscribed successfully','redirect' => route('core.home')],200);
      }
      $this->chargeService = new FluidCharge();
      $this->fluidApi=new  FluidApi(getenv('FLUID_API_KEY'),getenv('FLUID_API_URL'));
      $customer=$this->fluidApi->createCustomer($data['token'],$data['billingInfo']);
      if($customer){
        $options = [
          'amount' => $amount,
          'sellerPaymentInfo' => [
            'customer' => [
              'id' => $customer['id'],
              'paymentMethodId' => $customer['payment_method_id'],
            ],
          ],
        ];
        $charge=$this->chargeService->charge($options);
      }else{
        return response()->json(['status'=>'fail','message'=>'Error, please check your billing and card info'],200);
      }
    }
    if($charge['status']=='success' || $charge['status']=='pending_settlement'){

      session()->flash('success', 'You Subscribed successfully');
      foreach (explode(',',$data['packages']) as $package){
        //check if there is already a subscription
        $old_subscription=\App\Models\Subscription::where('uid',$user->id)->where('package',$package)->first();
        if($old_subscription){
          if($old_subscription->end > today())
          $end= $old_subscription->end > today() ? date('Y-m-d', strtotime($old_subscription->end. ' + 30 days')) : date('Y-m-d', strtotime(today(). ' + 30 days')) ;
          $old_subscription->end=$end;
          $old_subscription->save();
        }else{
          if( strpos($package, 'Ffl') !== false){
            $ffl_packages=\App\Models\Subscription::where('uid',$user->id)->where('package','like','Ffl%')->get();
            foreach ($ffl_packages as $ffl_package){
              $ffl_package->delete();
            }
          }
          $subscription=new \App\Models\Subscription();
          $subscription->uid=$user->id;
          $subscription->package=$package;
          $subscription->end=date('Y-m-d', strtotime(today(). ' + 30 days'));
          $subscription->token=$data['token'];
          $subscription->save();
        }
      }

      $user->subscription=1;
      $user->save();
       if(!$user->email_verified_at){
         return response()->json(['status'=>'success','message'=>'You subscribed successfully','redirect' => route('core.verify')],200);
       }
       return response()->json(['status'=>'success','message'=>'You subscribed successfully','redirect' => route('core.home')],200);
    }else{
      return response()->json(['status'=>'fail','message'=> $charge['msg']],200);
    }
  }

  public function home(){
    if(auth()->guard('web')->check()){
      $data=[];
      $user=auth()->guard('web')->user();
      $ffl_package=\App\Models\Subscription::where('uid',$user->id)->where('package','like','%Ffl%')->first();
      if($ffl_package){
        $data['calls_number']=$ffl_package->calls_number;
      }
      $data['packages']=\App\Models\Subscription::where('uid',$user->id)->get()->pluck('package')->toArray();
      $data['distributors']=\Devvly\Product\Models\Distributor::leftJoin('dist_accounts', function($join)  use($user) {
          $join->on('distributors.id', '=', 'dist_accounts.did')->where('uid',$user->id);
        })->get();
      return view($this->_config['view'],compact('data'));
    }
    return redirect()->intended(route('core.login'));
  }

  public function apiKeys(){
    if(auth()->guard('web')->check()){
      $packages= \App\Models\Subscription::where('uid',auth()->guard('web')->user()->id)->get()->pluck('package');
      return view($this->_config['view'],compact('packages'));
    }
    return redirect()->intended(route('core.login'));
  }

  public function changePassword(Request $request){
      try {
        $data = $request->all();
        if (isset($data['old_password']) && isset($data['new_password']) && isset($data['confirm_password'])) {
          if($data['new_password'] == $data['confirm_password']){
            $user=auth()->guard('web')->user();
            if (!(Hash::check($request->get('old_password'), Auth::user()->password))) {
              // The passwords matches
              return response()->json([
                'status'  => 400,
                'message' => 'Your current password does not matches with the password you provided. Please try again.',
              ], 200);
            }

            if(strcmp($request->get('old_password'), $request->get('new_password')) == 0){
              //Current password and new password are same
              return response()->json([
                'status'  => 400,
                'message' => 'New Password cannot be same as your current password. Please choose a different password.',
              ], 200);
            }

            //Change Password
            $user = Auth::user();
            $user->password = bcrypt($request->get('new_password'));
            $user->save();
            return response()->json([
              'status'  => 200,
              'message' => 'Password changed successfully !',
            ], 200);
        }else{
            return response()->json([
              'status'  => 400,
              'message' => 'Confirmation Password Does Not Match',
            ], 200);
          }
        }
        return response()->json([
          'status'  => 400,
          'message' => 'All Fields Are Required To Change Password',
        ], 200);
      } catch (Exception $exception) {
        return response()->json([
          'status'  => 400,
          'message' => $exception->getMessage(),
        ], 200);
      }

  }
  public function logout(){
    auth()->guard('web')->logout();
    Session()->flush();
    return redirect()->route($this->_config['redirect']);
  }

  public function registerApiKey(Request $request){

      if(auth()->guard('web')->check()){
       try{
        $data = $request->all();
        $packages= \App\Models\Subscription::where('uid',auth()->guard('web')->user()->id)->get()->pluck('package')->toArray();
        if($data['type']=='ffl'){
          if(!in_array('Ffl Pro',$packages) && !in_array('Ffl Advanced',$packages) && !in_array('Ffl Lite',$packages) && !in_array('Ffl Free',$packages)){
            return response()->json([
              'status'  => 400,
              'message' => 'You are not subscribed to ffl package',
            ], 200);
          }
         }
         if($data['type']=='distributor'){
           if(!in_array('Rsr',$packages) && !in_array('Zanders',$packages) && !in_array('Davidsons',$packages)){
             return response()->json([
               'status'  => 400,
               'message' => 'You are not subscribed to distributor package',
             ], 200);
           }
         }
        if (isset($data['type']) && isset($data['name'])) {
          $var  = Str::random(32);
          $apiKey=new \App\Models\ApiKey();
          $apiKey->uid=auth()->guard('web')->user()->id;
          $apiKey->type=$data['type'];
          $apiKey->name=$data['name'];
          $apiKey->api_key=$var;
          $apiKey->save();
          return response()->json([
            'status'          => 200,
            'message'         => 'success',
          ], 200);
        }
        return response()->json([
          'status'  => 400,
          'message' => 'Missing Parameters',
        ], 200);
      } catch (Exception $exception) {
      return response()->json([
        'status'  => 400,
        'message' => $exception->getMessage(),
      ], 200);
    }
      }else{
        return response()->json([
          'status'  => 400,
          'message' => 'Not Authenticated',
        ], 200);
      }
  }

  public function getApiKeys(){
    $apiKeys= \App\Models\ApiKey::where('uid',auth()->guard('web')->user()->id)->get();
    return response()->json([
      'status'          => 200,
      'message'         => 'success',
      'api_keys' => $apiKeys
    ], 200);
  }

  public function deleteApiKeys($id){
    if(auth()->guard('web')->check()){
      $apiKeys= \App\Models\ApiKey::where('uid',auth()->guard('web')->user()->id)->get();
      foreach ($apiKeys as $apiKey){
        if($apiKey->id==$id){
          $apiKey->delete();
        }
      }
    }
  }


}