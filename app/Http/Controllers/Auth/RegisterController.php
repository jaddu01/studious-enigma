<?php

namespace App\Http\Controllers\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\CountryPhoneCode;
use App\User;
use App\Zone;
use App\AccessLevel;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\Tempcustomers;
use App\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Traits\RestControllerTrait;
use App\Traits\ResponceTrait;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers , RestControllerTrait,ResponceTrait  ;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
     protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct(Request $request, Tempcustomers $user,User $customer, Zone $zone, SiteSetting $site_setting)
    {
        parent::__construct();
        $this->user=$user;
        $this->customer=$customer;
        $this->zone=$zone;
        $this->site_setting=$site_setting;
        $this->method=$request->method();
        $this->middleware('guest');
    }


    /* Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
           'name' => 'required|min:3|string|max:255',
           'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone_code' => 'required|max:6',
            'phone_number' => 'required|regex:/^((?!(0))[0-9]{6,})$/|numeric|min:6',
           'dob' => 'required|string|max:255',
           'address' => 'required|string|max:255',
           'referral_code' => 'sometimes|nullable|valid_refferal',
        ],['email.email_validation' => 'Invalid Email Address Provided']);
    }


    public function showRegistrationForm(){
        $countryPhoneCode  = CountryPhoneCode::orderBy('phonecode')->pluck('phonecode','phonecode');     
        $validator = JsValidatorFacade::make($this->customer->rules('POST'));
        return view('auth.aregister')->with('validator',$validator)->with('countryPhoneCode',$countryPhoneCode);
    }

     public function create(array $data){
       $sitesetting = $this->site_setting->first();
        if(!empty($data['referral_code'])){
            $check_user_reffer  = $this->customer->withTrashed()->where(['referral_code'=>$data['referral_code']])->first();

            /*$check_user_reffer->wallet_amount =  $check_user_reffer->wallet_amount + $sitesetting->referred_by_amount;
            $check_user_reffer->save();*/

            $referred_by_user_id = $check_user_reffer->id;
            $referred_by_amount = $sitesetting->referred_by_amount;
            $referred_by_transaction_id = time().rand(10000,999999);
            $referred_by_description = " Referral bonus recharge";
            
            Helper::updateCustomerWallet($referred_by_user_id,$referred_by_amount,'CREDIT','Referral Bonus',$referred_by_transaction_id,$referred_by_description);

            $referred_by = $check_user_reffer->id;
            $wallet_balance = $sitesetting->referral_amount;
        }else{
            $referred_by = "";
            $wallet_balance = 0;
        }
        $referral_code = "DAR".strtoupper(substr($data['name'], 0, 3)).rand(100,999);
        $dataopt = rand(100000,999999);
        $results =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['phone_code'],
            'phone_code' => $data['phone_code'],
            'phone_number' => $data['phone_number'],
            'gender' => $data['gender'],
            'dob' => $data['dob'],
            'address' => $data['address'],
            'access_user_id' => $data['access_user_id'],
            'user_type' => $data['user_type'],
            'referral' => $data['referral_code'],
            'referred_by' => $referred_by,
            'referral_code' => trim($referral_code),
            'otp' => $dataopt,
        ]);

        # // $referral_user_id = $results->id;
        # // $referral_amount = $sitesetting->referral_amount;
        # // $referral_transaction_id = time().rand(10000,999999);
        # // $referral_description = " Referral bonus recharge";
        
        # // Helper::updateCustomerWallet($referral_user_id,$referral_amount,'CREDIT','Referral Bonus',$referral_transaction_id,$referral_description);

        return $results;
       
    }


    public function afterRegister(){
        $data = $this->user->orderBy('id', 'DESC')->first();
        return view('pages.after_register')->with('data',$data);
    }

    public function verifyOtp(Request $request){
    $userData =  $this->user->orderBy('id', 'DESC')->first();
    return view('pages.verifyotp')->with(['phone_number'=>$userData->phone_number]);
    }

    public function verifedOtp(Request $request){
        $input = $request->all();
        $userData =  $this->user->orderBy('id', 'DESC')->first();
             
        $validator = Validator::make($request->all(),
            array( 'otp' => 'required|numeric|digits:6'),
            array( 'otp' => 'OTP is required' ));

        if ($validator->fails()) {
            Session::flash('danger',$validator->errors()->first());
           return redirect('verifyOtp')->withErrors($validator)->withInput();
        }else{
               if($userData->otp==$input['otp']){
                             $this->user->phone_code = $userData->phone_code;
                             $this->user->phone_number = $userData->phone_number;
                             $this->user->email = '';
                             $this->user->password = '';
                             return redirect('/customerupdate/'.$userData->id);
                }else{
                    Session::flash('danger', "OTP mismatch! Try Again.");
                   return redirect('verifyOtp')->withErrors($validator)->withInput();
                }
        }
       
       return view('pages.verifyotp')->with(['validator'=>$validator,'phone_number'=>$userData->phone_number]);
    }
     /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function createregister(Request $request){
    
        $input = $request->all();
    
      // $validator = Validator::make($request->all(),$this->user->rules($this->method),$this->user->messages($this->method));
        //regex:/^((?!(0))[0-9])$
       $validator = Validator::make($request->all(),
        array( 'phone_number' => 'required|digits:10|min:6|unique:users,phone_number,NULL,id,deleted_at,NULL',
        ),array( 'phone_number' => 'Phone Number is required' ));

       if ($validator->fails()) {
            Session::flash('danger',$validator->errors()->first());
                return back()->withErrors($validator)->withInput();
        }else{
            $otp1 = rand(100,999);
            $otp2 = rand(100,999);

            $otp = $otp1.'-'.$otp2;
            $dataotp = $otp1.$otp2;
          Tempcustomers::create([
            'name' => 'Guest',
            'email' => 'guest@gmail.com',
            'password' => bcrypt('123456'),
            'phone_code' => $input['phone_code'],
            'phone_number' => $input['phone_number'],
            'gender' => 'Male',
            'dob' => '',
            'address' => 'Address',
            'access_user_id' => '0',
            'user_type' => 'user',
            'otp' => $dataotp,
            'isverified'=>'NO',
        ]);

        /*$Authkey='15162A0GsnjQDanNj5e327760P15';
        $site='darbarmart';
        $route='4';
        $smsText='Your OTP is '.$dataopt;
        $mobiles=$input['phone_code'].$input['phone_number'];
        $country = $input['phone_code'];*/

        $client = new Client();
        $authkey = env('AUTHKEY');
        $phone_number = $input['phone_number'];
        $senderid = env('SENDERID');
        $tmp_id = '1207162028126071690';

        //http://login.yourbulksms.com/api/sendhttp.php?authkey=15162A0GsnjQDanNj5e327760P15&mobiles=918386931767&message=message&sender=darbarmart&route=4&country=91
       // $response = $client->request('GET',  "login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$mobiles."&message=".$smsText."&sender=darbarmart&route=".$route."&country=".$country);
       // $message="Your OTP for Darbaar Mart is ".$dataopt;

        $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar");
              
        $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=".$tmp_id);

        $statusCode = $response->getStatusCode();

         //return redirect(url('/verifyOtp'))->with('phone_number',$phone_number);
        return view('pages.verifyotp')->with(['phone_number'=>$phone_number]);
       }
     }



    public function resendRegisterOtp(Request $request){
        $response = [];
        $status = false;
        $smessage = "";
        if ($request->isMethod('post')) {
            if(!empty($request->phone_number)){ 
                $data = Tempcustomers::where(['phone_number'=>$request->phone_number])->orderBy('id','desc')->first();
                if(!empty($data)){

                    $otp1 = rand(100, 999);
                    $otp2 = rand(100, 999);
                    $otp = $otp1.'-'.$otp2;

                    
                    $data->otp = $otp1.$otp2;
                    $data->save();

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
                    $status = true;
                    $smessage = "OTP resend successfully!";
                }else{
                    $status = false;
                    $smessage = "Invalid Phone no.";
                }
            }else{
                $status = false;
                $smessage = "Phone no not found!";
            }
        }
        $response = array(
            'status' => $status,
            'message' => $smessage
        );

        return json_encode($response);
    }




}
