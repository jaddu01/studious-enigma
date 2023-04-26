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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Traits\RestControllerTrait;
use App\Traits\ResponceTrait;
use Illuminate\Support\Facades\DB;



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
     protected $redirectTo = '/sendOtp';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct(Request $request, Tempcustomers $user,User $customer, Zone $zone)
    {
        parent::__construct();
        $this->user=$user;
        $this->customer=$customer;
        $this->zone=$zone;
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
         //   'name' => 'required|string|max:255',
         //   'email' => 'required|string|email|max:255|unique:users',
          //  'password' => 'required|string|min:6|confirmed',
            'phone_code' => 'required|string|max:6',
            'phone_number' => 'required|string|max:10',
          //  'dob' => 'required|string|max:255',
          //  'address' => 'required|string|max:255',
        ]);
    }



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
     public function store(Request $request){
        $input = $request->all();

        $validator = Validator::make($request->all(),$this->customer->rules($this->method),$this->user->messages($this->method));
        if ($validator->fails()) {
            Session::flash('danger',$validator->errors()->first());
                return redirect('/register')->withErrors($validator)->withInput();
        }else{
            try {
                
                $temuuser_update_data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'gender' => $input['gender'],
                'address' => $input['address'],
                'dob' => $input['dob'],
                ]; 

       $dataopt = rand(1000,9999);
       try {

            $customer =  $this->user->find($input['id']);
            $last_user =  User::select('id')->orderBy('id','desc')->first();
            $last_userid = $last_user->id;
            $refid = $last_userid + 1;
     
          User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $customer->password,
            'phone_code' => $customer->phone_code,
            'phone_number' => $customer->phone_number,
            'gender' => $input['gender'],
            'dob' => $input['dob'],
            'address' => $input['address'],
            'access_user_id' => '0',
            'user_type' => 'user',
            'otp' => $dataopt,
             $input['referral_code'] = "ZAD".strtoupper(substr($input['name'], 0, 3)).$refid,
             ]);
            $flight = $this->user->where('id',$input['id'])->delete();
                Session::flash('success','Category create successful');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
                $customer = $customer->withoutGlobalScope(StatusScope::class)->update($temuuser_update_data);
   
                Session::flash('success','User create successful');
                $message = 'User create successful';
                $type='success';
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                $message = $e->getMessage();
                $type='error';
            }
        
                return redirect('/')->with('success','User create successful');
            

        }
    }

    public function showRegistrationForm(){
        $countryPhoneCode  = CountryPhoneCode::orderBy('phonecode')->pluck('phonecode','phonecode');     
        $validator = JsValidatorFacade::make($this->user->rules('POST'));
        return view('auth.aregister')->with('validator',$validator)->with('countryPhoneCode',$countryPhoneCode);
    }


    public function afterRegister(){
        $data = $this->user->orderBy('id', 'DESC')->first();
        return view('pages.after_register')->with('data',$data);
    }

    public function verifyOtp(Request $request){
    $userData =  $this->user->orderBy('id', 'DESC')->first();
    return view('pages.verifyotp')->with('otp_value',$userData->otp);
    }

    public function verifedOtp(Request $request){
        $input = $request->all();
        $userData =  $this->user->orderBy('id', 'DESC')->first();
             
        $validator = Validator::make($request->all(),
            array( 'otp' => 'required|numeric|digits:4|confirmed' ),
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
                   return redirect('verifyOtp')->withErrors($validator)->withInput();
                }
        }
       
       return view('pages.verifyotp')->with('otp_value',$userData->otp)->with('validator',$validator);
    }
     /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data){
      $dataopt = rand(1000,9999);
         return Tempcustomers::create([
            'name' => 'Guest',
            'email' => 'guest@gmail.com',
            'password' => bcrypt('123456'),
            'phone_code' => $data['phone_code'],
            'phone_number' => $data['phone_number'],
            'gender' => 'Male',
            'dob' => '',
            'address' => 'Address',
            'access_user_id' => '6',
            'user_type' => 'user',
            'otp' => $dataopt,
            'isverified'=>'NO',
        ]);
    }




}
