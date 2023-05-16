<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

//use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;

use GuzzleHttp\Client;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    use RestControllerTrait,ResponceTrait;

 /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  //  use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
         $this->user = $user;
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showindex()
    {
        return view('pages.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->forget('zone_id');
        return redirect('/');

        $request->session()->flush();
        $request->session()->forget('zone_id');
        $request->session()->regenerate();
        return redirect('/');
    }

  /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('web');
    }
/**
     * @param Request $request
     */
    public function attemptLogin( Request $request)
    {

        $cred =  $this->credentials($request);

         if(isset($cred['phone'])){   
           if (Auth::guard('web')->attempt(['phone_number' =>$cred['phone'], 'password' => $cred['password'], 'user_type' => 'user', 'role' => 'user', 'deleted_at' => null, 'status' => '1'])) {
           }
          }else{
              if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password, 'user_type' => 'user', 'role' => 'user', 'deleted_at' => null, 'status' => '1'])) {
            }

          }
    }
     
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), 5000, 3
        );
    }


    public function login(Request $request)
    {
     //  print_r($request->all()); die;
        $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {print_r($request->all()); die;
            return $this->sendLoginResponse($request);
        }
        $this->incrementLoginAttempts($request);
       Session::flash('danger',trans('user.check_details'));
       return $this->sendFailedLoginResponse($request);
    }

   
    

   protected function credentials(Request $request)
    {
        if(is_numeric($request->get('email'))){
            return ['phone'=>$request->get('email'),'password'=>$request->get('password')];
        }
        return $request->only($this->username(), 'password');
    }
   protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }
 // protected function validatemobileLogin(Request $request)
 //    {
 //        $this->validate($request, [
 //            'phone_number' => 'required|string',
 //            'otp' => 'required|string',
 //        ]);
 //    }
    
 //    protected function mobilecredentials(Request $request)
 //    {
 //         return ['phone_number'=>$request->get('phone_number'),'otp'=>$request->get('otp')];
        
 //        return $request->only('phone_number', 'otp');
 //    }
    public function attemptmobileLogin( Request $request)
    {
       if (Auth::guard('web')->attempt(['phone_number' =>$request->phone_number, 'otp' =>$request->otp, 'user_type' => 'user', 'role' => 'user', 'deleted_at' => null, 'status' => '1'])) {
           }
          
     }
 public function loginmobile(Request $request){
        $data = User::where(['phone_number'=>$request->phone_number])->first();

        if(!empty($data)){
            //$otp =  rand(100000,999999);
            $otp1 = rand(100, 999);
            $otp2 = rand(100, 999);
            $otp = $otp1.'-'.$otp2;

            
            $data->otp = $otp1.$otp2;

            $client = new Client();
            $authkey = env('AUTHKEY');
            $phone_number = $request->phone_number;
            $senderid = env('SENDERID');
            $tmp_id = '1207162028126071690';

            //http://login.yourbulksms.com/api/sendhttp.php?authkey=15162A0GsnjQDanNj5e327760P15&mobiles=918386931767&message=message&sender=darbarmart&route=4&country=91
            // $response = $client->request('GET',  "login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$mobiles."&message=".$smsText."&sender=darbarmart&route=".$route."&country=".$country);
            
            //$message = "Your OTP for Darbaar Mart is ".$otp;
            $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar");
              
            $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=".$tmp_id);

            $statusCode = $response->getStatusCode();

         $data->update(); 
        return view('pages.verifymobileotp')->with(['data'=>$data]);
      }else{
        Session::flash('danger',trans('user.check_details'));
          return redirect('/register')->with('error','Somthing went wrong');
      }
    }
   

    public function mobilelogin(Request $request){
        $data = User::where(['phone_number'=>$request->phone_number])->first();

        $validator = Validator::make($request->all(),
            array('otp_confirmation' => 'required|numeric|digits:6'),
            array('otp_confirmation' => 'OTP is required' ));

        if ($validator->fails()) {
            Session::flash('danger',$validator->errors()->first());
            //return view('pages.verifymobileotp')->withErrors($validator)->withInput();
            return view('pages.verifymobileotp')->with(['data'=>$data]);
        }else{

            $token = $request->_token;
            $phone_number = $request->phone_number;

            $user_found = $this->user->where(['phone_number'=>$request->phone_number,'otp'=>$request->otp_confirmation])->first();
            if(!empty($user_found)){
                $password = rand(100000,999999);
                $user_found->password = Hash::make($password);
                try { 
                    $user_found->update();

                    if($user_found){
                        if (Auth::guard('web')->attempt(['phone_number' =>$request->phone_number, 'password' =>$password, 'user_type' => 'user'])) {
                            return redirect('/home');
                        }else{
                            Session::flash('danger',trans('user.check_details'));
                            return redirect('/login')->with('error','Somthing went wrong');
                        }
                    }else{
                        Session::flash('danger',trans('user.invalid_user'));
                        return redirect('/login')->with('error','Somthing went wrong');
                    }
                }catch(Exception $e){  print_r($e->message()); die; }
            }else{
                Session::flash('danger','OTP Mismatch! Try again.');
                 return view('pages.verifymobileotp')->with(['data'=>$data]);
            }
        }   
    }
  
}
