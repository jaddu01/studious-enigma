<?php namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\ProductOrder;
use App\OrderStatusNew;
use App\ProductOrderItem;
use App\DeliveryLocation;
use App\Zone;
use App\ZoneTranslation;
use App\CategoryTranslation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Hash;use DB;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use App\Helpers\Helper;


use GuzzleHttp\Client;


class UserController extends Controller
{
    /**
     * Constructor method
     */
     public function __construct(User $user,ProductOrder $productorder,OrderStatusNew $orderstatus,ProductOrderItem $productOrderItem,DeliveryLocation $deliverylocation,Zone $zone,CategoryTranslation $category)
   
    {
         parent::__construct();
        $this->user = $user;
        $this->order = $productorder;
        $this->orderstatus = $orderstatus;
        $this->productOrderItem = $productOrderItem;
        $this->deliverylocation = $deliverylocation;
        $this->zone=$zone;
        $this->category=$category;
        $this->middleware('auth', ['only' => ['edit', 'update']]);
    }



    /**
     * Show User Registration Form
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Register User
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        User::create([
            'name'      => $request->get('name'),
            'email'     => $request->get('email'),
            'password'  => bcrypt($request->get('password'))
        ]);

        return redirect('login')
            ->with('flash_notification.message', 'User registered successfully')
            ->with('flash_notification.level', 'success');
    }

    /**
     * Show User Profile
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
       // $user->dob = date('m/d/Y',$user->dob) ;
        return view('users.profile', compact('user'));
    }

    /**
     * Update User Profile
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $input =$request->all();

        $validator = Validator::make($request->all(),[
            'name'      => 'required',
           // 'lname'      => 'required',
            'email'     => 'required|email',
            'gender'     => 'required',
            'dob'     => 'required',
            'image' => 'image|mimes:jpg,png,jpeg',
        ],[
          'image' =>"Profile Image must be only an Image"]);
        if ($validator->fails()) {

                Session::flash('danger',$validator->errors()->first());
            return back()->withErrors($validator)->withInput();
        }else{
          
        $user->name     = $request->get('name');
        $user->email    = $request->get('email');
        $user->gender    = $request->get('gender');
        $user->dob    = $request->get('dob');
        if(!empty($request->image)){  
                    $imageName = Helper::fileUpload($request->file('image'));
                    $user->image = $imageName;
            }
      
        $data = $user->save();

        return redirect('/profile')
            ->with('info', 'User details updated successfully');
      }
   }
     /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show()
    {
        $user = auth()->user();
        $total_order = $this->order->with(['ProductOrderItem'])->where('user_id',$user->id)->count();
       $validator  = JsValidatorFacade::make($this->user->rules('POST'));
        return view('pages.user.profile', ['user' => $user,'total_order'=>$total_order,'validator'=>$validator]);
    }
   public function changePassword()
    {
        $user = Auth::user();
        $total_order = $this->order->where('user_id',$user->id)->count();
         $validator  = JsValidatorFacade::make($this->user->rules('POST'));
        return view('/pages/user/change-password', ['user' => $user,'total_order'=>$total_order,'validator'=>$validator]);
    }

     public function updatePassword(request $request)
     {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
        'old_password' => 'required|string',
        'user_password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
             Session::flash('danger',$validator->errors()->first());
             return back()->withErrors($validator)->withInput();
        }else{
           if(Hash::check($request->old_password,Auth::user()->password)) {
            $id = Auth::user()->id;
            if($request->user_password==$request->old_password) {
                  Session::flash('danger','Old password and New password should not same');
                  return redirect()->back();
            }else{
                $input['password'] = bcrypt($request->input('user_password'));
                if($this->user->FindOrFail($id)->fill($input)->save()){
                    return redirect()->back()->with('success','User update successful');
                }else{
                    return redirect()->back()->with('error','Something went wrong');
                } 
            }
           }else{
                Session::flash('danger','Current password do not match');
                return redirect()->back();
            }
        }
    }
    public function sendOtp(){
        return redirect('/verifyOtp');
    }

    public function addnewaddress(){
         $user = auth()->user();
         $address = $user->address;
         $deliverylocations = DeliveryLocation::where('user_id',$user->id)->get();
         if(count($deliverylocations)==0){ 
            $first_address = DeliveryLocation::where('id',$address)->first(); 
            $first_address->user_id = $user->id;
            $first_address->save();
            $zonedata = $this->getZoneData($first_address->lat, $first_address->lng);
            $vendor_zone_id = $zone_id = $zonedata['zone_id'];
            $user->zone_id = $vendor_zone_id;
            $user->save();
           }else{
              $first_address =  DeliveryLocation::where('user_id',$user->id)->first(); 
              $zonedata = $this->getZoneData($first_address->lat, $first_address->lng);
              $vendor_zone_id = $zone_id = $zonedata['zone_id'];
           }
           $deliverylocations = DeliveryLocation::where('user_id',$user->id)->get();
           Session::put('zone_id',$vendor_zone_id);
        //   print_r($deliverylocations); die;
         $total_order = $this->order->where('user_id',$user->id)->count();
         return view('pages.user.addaddress', ['user' => $user,'deliverylocations'=>$deliverylocations,'total_order'=>$total_order]);
    }

    public function maplocation(){
        $user = auth()->user();
        $zones = $this->zone->get();
        return view('pages.user.maplocation', ['user' => $user,'zones' => $zones]);
    }

     public function isPointInPolygon($latitude, $longitude, $latitude_array, $longitude_array) {
    $size = count($longitude_array);
    $flag1 = false;
    $k = $size - 1;
    $j = 0;
    while ($j < $size) {
        $flag = false;
        $flag2 = false;
        $flag3 = false;
        if ($latitude_array[$j] > $latitude) {
            $flag2 = true;
        } else {
            $flag2 = false;
        }
        if ($latitude_array[$k] > $latitude) {
            $flag3 = true;
        } else {
            $flag3 = false;
        }
        $flag = $flag1;
        if ($flag2 != $flag3) {
            $flag = $flag1;
            if ($longitude < (($longitude_array[$k] - $longitude_array[$j]) * ($latitude - $latitude_array[$j])) / ($latitude_array[$k] - $latitude_array[$j]) +
                $longitude_array[$j]) {
                if (!$flag1) {
                    $flag = true;
                } else {
                    $flag = false;
                }
            }
        }
        $k = $j;
        $j++;
        $flag1 = $flag;
    }
    return $flag1;
}
   public function getZoneData($lat, $lng)
    {
        $zone_id = '';
        $zoneArray = [];
        $zArray = [];
        $fArray = [];
        $finalArray = [];
      
        $zonedata = DB::table('zones')->select('id',DB::raw("ST_AsGeoJSON(point) as json"),'delivery_charges' )->where('deleted_at',null)->where('status','=','1')->get();
      
            $json_arr = json_decode($zonedata, true);
            foreach ($json_arr as $zvalue) {
                $zone_id=$zvalue['id'];
                $delivery_charges=$zvalue['delivery_charges'];
                $json=json_decode($zvalue['json']);
                $coordinates=$json->coordinates;
                $new_coordinates=$coordinates[0];
                $lat_array=array();
                $lng_array=array();
                foreach($new_coordinates as $new_coordinates_value){
                    $lat_array[]=$new_coordinates_value[0];
                    $lng_array[]=$new_coordinates_value[1];


                }
           
            $is_exist = $this->isPointInPolygon($lat, $lng,$lat_array,$lng_array);
           
            if($is_exist){
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                $data['delivery_charges'] = $delivery_charges;
                return $data;
            }

            }
            
            $zone_id_default = 0;
            
            $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
            $data['match_in_zone'] = false;
            $data['zone_id'] = $zone_id_default;
            $data['delivery_charges'] = 0;
            return $data;
    }

    public function resendOtp(Request $request){
        $response = [];
        $status = false;
        $smessage = "";
        if ($request->isMethod('post')) {
            if(!empty($request->phone_number)){ 
                $data = User::where(['phone_number'=>$request->phone_number])->first();
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
                    $hash = env('SMSHASH');

                    //http://login.yourbulksms.com/api/sendhttp.php?authkey=15162A0GsnjQDanNj5e327760P15&mobiles=918386931767&message=message&sender=darbarmart&route=4&country=91
                    // $response = $client->request('GET',  "login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$mobiles."&message=".$smsText."&sender=darbarmart&route=".$route."&country=".$country);
                    
                    //$message = "Your OTP for Darbaar Mart is ".$otp;
                    $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar $hash");
                      
                  $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=1207162028126071690");

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