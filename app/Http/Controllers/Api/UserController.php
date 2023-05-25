<?php

namespace App\Http\Controllers\Api;

use App\AdminNotification;
use App\Http\Resources\UserResource;
use App\Notifications\ProductStatus;
use App\Notifications\AllOrderStatus;
use App\Notifications\ProductUpdate;
use App\Notifications\AddressUpdate;
use App\Notifications\ProductOutStockStatus;
use App\Notifications\ManageProductUpdate;
use App\Notifications\ManageOutStock;
use App\Notifications\NewProduct;
use App\Notifications\OrderStatus;
use App\Http\Controllers\Controller;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\User;
use App\Zone;
use App\MeasurementClassTranslation;
use App\ZoneTranslation;
use App\ProductOrder;
use App\Product;
use App\SiteSetting;
use App\DeliveryLocation;
use App\ProductOrderItem;
use App\OrderStatusNew;
use App\VendorProduct;
use App\Offer;
use App\CategoryTranslation;
use App\UserWallet;
use Carbon\Carbon;
use Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Helpers\Helper;
use App\Helpers\ResponseBuilder;
use App\Http\Resources\UserAddressResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use RestControllerTrait,ResponceTrait, SendsPasswordResetEmails;
   // const MODEL = 'App\User';
  // const MODEL = 'App\ProductOrder';
    private $user;
    private $productorder;
    private $orderstatus;
    private $productOrderItem;
    private $deliverylocation;
    private $category;
    private $zone;
    

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user,Product $product,ProductOrder $productorder,OrderStatusNew $orderstatus,ProductOrderItem $productOrderItem,DeliveryLocation $deliverylocation,Zone $zone,CategoryTranslation $category, UserWallet $user_wallet,SiteSetting $site_setting)
    {
        parent::__construct();
        $this->user = $user;
        $this->order = $productorder;
        $this->orderstatus = $orderstatus;
        $this->productOrderItem = $productOrderItem;
        $this->deliverylocation = $deliverylocation;
        $this->zone=$zone;
        $this->site_setting = $site_setting;
        $this->category=$category;
        $this->user_wallet=$user_wallet;
        $this->product = $product;
    }
 
    public function getUserNotificationByUserId($id){
        $user = User::find($id);

        return response($user->unreadNotifications, 200);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function notification(request $request)
    {
        $a = [];
        $validator = Validator::make($request->all(), [
            'type'=>'required|in:notifications,alerts',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }
        $userId = Auth::guard('api')->user()->id;
        //$notifications = Auth::guard('api')->user()->notifications(['data'])->paginate(10);
       
        if($request->type == 'notifications'){
             $adminNotifications = AdminNotification::select(['id','message_heading','image', 'message_url', 'message','created_at','link_type','link_url_type','cat_id','sub_cat_id','vendor_product_id'])->whereRaw('FIND_IN_SET('.$userId.', user_ids)')->get();
            /* $n = $notifications->collect();
            $an = $adminNotifications->collect();*/
            //$merged = $adminNotifications->merge($notifications);

            $adminNotifications = $adminNotifications->transform(function($item, $key){
                
                $adata['title']= $item->message_heading;
                $adata['decription']=  $item->message;
                $adata['type']=  "Promotional Notifications";
                $adata['link_type']=  $item->link_type;
                $adata['link_url_type']=  $item->link_url_type;
                $adata['cat_id']=  $item->cat_id;
                $adata['sub_cat_id']=  $item->sub_cat_id;
                $adata['vendor_product_id']=  $item->vendor_product_id;
                $adata['message_url']=  $item->message_url;
                $adata['date']= $item['created_at']->format('d-m-Y');
                $adata['from_timestamp'] = strtotime($item['created_at']);
                $adata['image']= $item->image;
                $adata['order_code']= '';
                $adata['order_id']= '';
                $adata['status']= '';
                return $adata;
            });
            $adminNotifications = $adminNotifications->toArray();
            usort($adminNotifications, array($this,'sortByNamea'));
            $merged = collect($adminNotifications);
           //$merged =  usort($merged, array($this,'sortByName')) ;
        }
        if($request->type == 'alerts'){
            $notifications = Auth::guard('api')->user()->notifications(['data'])->get();
            $notifications->transform(function($item, $key)
            {
                $data = $item->data;
                $data['title']= "Notification";
                $data['type']= "Order Notifications";
                $data['decription']= $data['message'];
                $data['date']= $item['created_at']->format('d-m-Y');
               $data['from_timestamp'] = strtotime($item['created_at']);
                $item->data = $data;
                return $item->data;
            });
            $notifications = $notifications->toArray();
            usort($notifications, array($this,'sortByNamea'));
            $merged = collect($notifications);
        }

         //return $notifications;

        //$merged = collect($adminNotifications)->merge(collect($notifications));
        //usort($merged, array($this,'sortByName'));
        //return $merged->sortBy('from_timestamp');
        //$items = $this->paginate($merged);
        //return collect($merged)->paginate(10);
        //return array_merge(array($notifications), array($adminNotifications));
       // return collect($adminNotifications)->sortBy('id','');
        return $this->showResponse($merged);
    }

    public function deleteNotification(Request $request)
    {
        $notification = Auth::guard('api')->user()->notifications();
        if($request->filled('id')){
            $notification->where(['id'=>$request->id]);
        }
        $notification->delete();
        return $this->deletedResponse(trans('site.delete'));
    }

    public function adminNotification(Request $request){
        $notifications = AdminNotification::select(['message_heading','image', 'message_url', 'message','created_at'])->whereRaw('FIND_IN_SET('.Auth::guard('api')->user()->id.', user_ids) ')->orWhere('selection','=','all')->paginate(10);
        return $this->showResponse($notifications);
    }

    public function markAsReadNotification(Request $request)
    {
        $notification = Auth::guard('api')->user()->unreadNotifications();
        if($request->filled('id')){
            $notification->where(['id'=>$request->id]);
        }

        $notification->update(['read_at' => now()]);
        return $this->deletedResponse("mark-as-read-notification");
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'device_type'=>'required|in:A,I',
            /*'language' => 'required|in:en,ar',*/
            'device_id' => 'required',
            'device_token' => 'required',
            'phone_code' => 'required',
            'phone_number' => 'required|unique:users',
           /* 'phone_number' => 'required|unique:users,phone_number',*/
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email,NULL,id,deleted_at,NULL',
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->validationStatus);

        }
        if(isset($request->language)){

            App::setLocale($request->language);

        }else{

            $input['language'] = 'en';
            App::setLocale('en');
        }

        $input = $request->all();
        $input['password'] = bcrypt('');
        //$otp =   $input['otp'] =  rand(100000,999999);
        $otp1 = rand(100,999);
        $otp2 = rand(100,999);
        $otp = $otp1.''.$otp2;
        $input['otp'] = "123456"; //$otp1.$otp2;

        $last_user =  $this->user->select('id')->orderBy('id','desc')->first();
        $last_userid = $last_user->id;
        $refid = $last_userid + 1;
        $SiteSetting = $this->site_setting->first();
        $check_user  = $this->user->withTrashed()->where(['phone_number'=>$request->phone_number])->first();
           // if($check_user){
           //      $check_user->restore();
           //      $user = $this->user->with(['deliveryLocation'])->findOrFail($check_user->id);
           $user = $this->user->fill($input)->save($input);
           //  }else{
            if(!empty($request->referral_code)){
               
               $check_user_reffer  = $this->user->withTrashed()->where(['referral_code'=>$request->referral_code])->first();
               $refferalCound = $this->user->withTrashed()->where(['referred_by'=>$check_user_reffer->id])->count();
               if(!empty($refferalCound) && $refferalCound >= 3){
                    return ResponseBuilder::error('Refferal code limit used', $this->validationStatus);
               }

              if(!empty($check_user_reffer)){
                    //$check_user_reffer->wallet_amount=  $check_user_reffer->wallet_amount + $SiteSetting->referred_by_amount;
                    //$check_user_reffer->save();

                    $input['referred_by'] = $check_user_reffer->id;
                    //$input['wallet_amount'] = $SiteSetting->referral_amount;
                  
                  
                    /*===This is for old user which is reffering==*/
                    $amount =  $SiteSetting->referred_by_amount;
                    $transaction_type = "CREDIT";
                    $type ="Referral Amount";
                    $transaction_id = rand(100000,999999);
                    $description ="Your referral amount Wallet Recharge";
                    $json_data = json_encode(['refuser'=>$check_user_reffer->id]); 
                    $user_wallet = Helper::updateCustomerWallet($check_user_reffer->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data);
                    /*===This is for old user which is reffering==*/

                //echo "<pre>"; print_r($user_wallet); die;
              }else{
                    return ResponseBuilder::error('Worng Refferal code used', $this->validationStatus);
              }
            }
            $client = new Client();
            $authkey = env('AUTHKEY');
            $phone_number = $request->phone_code.$request->phone_number;
            $senderid = env('SENDERID');
            $hash = env('SMSHASH');
            //$message="Your OTP for Darbaar Mart is ".$otp;
            $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar $hash");

           // $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=1207162028126071690");
            //$statusCode = $response->getStatusCode();

            
            //$user = $this->user->with(['deliveryLocation'])->findOrFail($this->user->create($input)->id);
            if(!empty($request->referral_code)){

                 /*===This is for new user which is reffered==*/
                  $amount =  $SiteSetting->referral_amount;
                  $transaction_type = "CREDIT";
                  $type ="Referral Amount";
                  $transaction_id = rand(100000,999999);
                  $description ="Your referral amount Wallet Recharge";
                  $json_data = json_encode(['refuser'=>$user->id]); 
                  $user_wallet = Helper::updateCustomerWallet($user->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data);
                /*===This is for new user which is reffered==*/
            }

            if(!empty($user->id)){
                $referral_code = trim("DAR".strtoupper(substr($user->name, 0, 3)).$user->id);
                $user->update(['referral_code' => $referral_code]);
                //$this->user->update()
            }
        //}
        //     echo "<pre>";
        //     print_r($user);
        //     die("h");
        //$token =  $user->createToken('MyApp')->accessToken;
        //$this->response->user = new UserResource($user);
        return ResponseBuilder::success(null,'User register successfully', $this->successStatus);

    }

    public function addressUpdate(Request $request)
    {

        $user = Auth::guard('api')->user();
        if($user)
        {
            $useraddress = DeliveryLocation::create($request->all());
            $this->response->address = UserAddressResource::collection($useraddress);
            return ResponseBuilder::success($this->response,'User address updated successfully', $this->successStatus);
            // return $this->showResponse($useraddress);
        }
        else
        {
            return ResponseBuilder::error('User not found', $this->validationStatus);
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        $user = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [
            'name' =>  'sometimes|required',
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg',
          //  'email' => 'sometimes|required|email|unique:users,email,'.$user->id,
            //'dob' =>   'sometimes|required|date_format:"d-m-Y',
            'gender' =>'sometimes|required|in:male,female',

        ]);
        if ($validator->fails()) {

            return $this->validationErrorResponse($validator);

        }
        $userData  = $this->user->FindOrFail($user->id);

            $input = $request->all();
            if($request->hasFile('image')){
                $image = $request->file('image');

                $imageName = time().rand(0,99).'.'.$image->getClientOriginalExtension();

                $request->file('image')->storeAs(
                    'public/upload', $imageName
                );
                $input['image']= $imageName;
            }


        $userData->update($input);
        // return $this->showResponse($userData);
        $this->response->user = new UserResource($userData);
        return ResponseBuilder::success($this->response,'User updated successfully', $this->successStatus);

    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    /** common api to update current location for users(driver, shopper,customer)
    *@param Request $request
    *
    **/
    public function updateCurrentLocation(Request $request)
    {
        if(isset($request->user_id)){
            $userData  = $this->user->FindOrFail($request->user_id);
            try {
                $userData->current_lat = $request->current_lat;
                $userData->current_lng = $request->current_lng;
                $userData->save();
                return $this->showResponse("Updated");
            } catch (\Exception $e) {
                echo $e->getMessage();die;
            }

        }else{
             return $this->userNotExistResponse();
        }

        
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [

            'device_type'=>'required|in:A,I',
            'language' => 'required|in:en,ar',
            'device_id' => 'required',
            'device_token' => 'required',
            'phone_code' => 'required',
            'phone_number' => 'required',

        ]);
        
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        }else{
            App::setLocale($request->language);
            $user =  $this->user->withTrashed()->with(['deliveryLocation'])->where(['phone_number'=>$request->phone_number,'phone_code'=>$request->phone_code])->first();

            if (empty($user)) {
                $otp1 = rand(100, 999);
                $otp2 = rand(100, 999);
                $otp = $otp1.''.$otp2;
                $this->user->phone_number= $request->phone_number;
                $this->user->phone_code= $request->phone_code;
                $this->user->device_token= $request->device_token;
                $this->user->device_id= $request->device_id;
                $this->user->language= $request->language;
                $this->user->device_type= $request->device_type;
                $this->user->otp= $otp;
                $this->user->save();
                $client = new Client();
                $authkey = env('AUTHKEY');
                $phone_number = $request->phone_number;
                $senderid = env('SENDERID');
                $hash = env('SMSHASH');
                //$message="Your OTP for Darbaar Mart is ".$otp;
                
                $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar $hash");

                $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=1207162028126071690");
                $statusCode = $response->getStatusCode();
                 $last_user = DB::table('users')
                    ->select(array('id','phone_number','otp'))
                    ->where('id',$this->user->id)
                    ->get();
                return $this->showResponse($last_user);
            }  
            if ($user) {
                if($user->user_type != 'user'){
                    $response = [
                        'code' => 4,
                        'error' => true,
                        'message' => 'Please login with user details in user app'
                    ];
                    return response()->json($response, 200);
                }
                if(!empty($user->deleted_at)){
                    $response = [
                        'code' => 2,
                        'error' => true,
                        'message' => 'Your account has deleted by admin'
                    ];
                    return response()->json($response, 200);
                }
                if($user->status == 0){
                    $response = [
                        'code' => 3,
                        'error' => true,
                        'message' => (trans('user.invalid_user'))
                    ];
                    $user_id_array = [0=>$user->device_token];
                    $user_device_type = $user->device_type;
                    $dataArray = [];
                    $dataArray['type'] = 'Deactivated';
                    $dataArray['title'] = trans('user.invalid_user');
                    $dataArray['body'] = trans('user.invalid_user');
                    //Helper::sendNotification($user_id_array ,$dataArray, $user_device_type);
                    return response()->json($response, 200);
                }
                //$otp = rand(100000, 999999);
                $otp1 = rand(100, 999);
                $otp2 = rand(100, 999);
                $otp = $otp1.''.$otp2;

                //$otp = "1234";
                // if($request->phone_number == '9999999999'){
                //     $user->otp = '123456';
                // }else{
                    $user->otp = $otp1.$otp2;   
                //}
                
                $user->fill($request->only(['device_type','language','device_id','device_token','phone_code','phone_number','lat','lng']))->save();
    
                $client = new Client();
                $authkey = env('AUTHKEY');
                $phone_number = $request->phone_number;
                $senderid = env('SENDERID');
                $hash = env('SMSHASH');
                //$message="Your OTP for Darbaar Mart is ".$otp;
                
                $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar $hash");

                $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=1207162028126071690");
                $statusCode = $response->getStatusCode();
                $response_data['code'] = 1;
                $response_data['error'] = false;
                $response_data['statusCode'] = $statusCode;
                $response_data['phone_number'] = $phone_number;
                $response_data['otp'] = $otp; 
                $response_data['user_id'] = $user->id;
                $response_data['message'] = 'successfully sent';
                return response()->json($response_data, 201);
            }else{
                return $this->userNotExistResponse();
            }
        }
    }
    public function user_delivery_addresses($user_id = null) {
        $delivery_locations = DeliveryLocation::where('user_id', $user_id)->get();
        $record_count = count($delivery_locations);
        
        if ($record_count > 0) {
            $data_address = array();
            foreach ($delivery_locations as $delivery_location) {
                $return_address = array();
                $return_address['id'] = $delivery_location->id;
                $return_address['user_id'] = $delivery_location->user_id;
                $return_address['name'] = $delivery_location->name;
                $return_address['address'] = $delivery_location->address;
                $return_address['lat'] = $delivery_location->lat;
                $return_address['lng'] = $delivery_location->lng;
                $return_address['region_id'] = $delivery_location->region_id;
                
                $data_address[] = $return_address;
            }
        } else {
            $data_address = null;
        }
        
        return $data_address;
    }

    public function login_otp_verify(Request $request) {

        $response_data = array();
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'otp' => 'required'
        ]);
        if ($validator->fails()){
            $response = [
                'code' => 0,
                'error' => true,
                'message' => $validator->errors()->first(),
            ];
            
            return response()->json($response, 200);
        }
        
        $user_details = User::where('id', $request->user_id)->where('otp', $request->otp)->first();
        if (empty($user_details)) {
            return response()->json(['code' => 2, 'error' => true, 'message' => 'Wrong OTP'], 200);
        }
        
        DB::table('users')->where('id', $user_details->id)->update([
            'otp' => null
        ]);
        $refreshed =  $user_details->createToken('grocery')->accessToken;
        $data_address = $this->user_delivery_addresses($request->user_id);
        $response_data['code'] = 1;
        $response_data['error'] = false;
        $response_data['data'] = $user_details;
        $response_data['data']['token'] = $refreshed;
        $response_data['data']['address_list'] = $data_address;
        $response_data['message'] = 'successful login';
        return response()->json($response_data, 201);
    }

    //resend otp
    public function resend_otp(Request $request){
        try{
            $validator = Validator::make($request->all(), [     
                'phone_code' => 'required|max:4',
                'phone_number' => ['required','max:10',Rule::exists('users')->where(function ($query) use($request) {
                    $query->where('phone_code', $request->phone_code)->where('phone_number', $request->phone_number);
                })],
            ],[
                'phone_number.exists' => 'This mobile no is not registered with us.',
            ]);
    
            if ($validator->fails()){
                return ResponseBuilder::error($validator->errors()->first(), $this->validationStatus);
            }
            $user = $this->user->where('phone_code', $request->phone_code)->where('phone_number', $request->phone_number)->first();
            if(empty($user)){
                return ResponseBuilder::error('User not found', $this->notFoundStatus);
            }
            $otp = "123456"; //rand(100000, 999999);
            $user->otp = $otp;
            $user->save();

            return ResponseBuilder::success(null, 'OTP sent successfully', $this->successStatus);
        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }
    public function resend_otp_old(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'resend_type' => 'required'
        ]);

        if ($validator->fails()){
            return ResponseBuilder::error($validator->errors()->first(), $this->validationStatus);
        }
        
        /* 1=Register OTP, 2=Login OTP */
        if ($request->resend_type == 1) {
            $otp1 = rand(100, 999);
            $otp2 = rand(100, 999);
            $otp = "123456"; //$otp1.'-'.$otp2;

            $otp_details = $this->user->where('id', $request->user_id)->first();
           
            if (isset($otp_details) && !empty($otp_details)) {
                $otp_details->otp = $otp1.$otp2;
                $otp_details->save();   

                $client = new Client();
                $authkey = env('AUTHKEY');
                $phone_number = $otp_details->phone_number;
                $senderid = env('SENDERID');
                $hash = env('SMSHASH');
                //$message="Your OTP for Darbaar Mart is ".$otp_details->otp;

                $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar $hash");

              // echo "http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message='".$message."'&sender=".$senderid."&route=1&country=0"; die;
                //$response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=1207162028126071690");
                //$statusCode = $response->getStatusCode();
                $response_data['message'] = 'OTP send successfully.';
                 
            } else {
                $response_data['message'] = 'Invalid selection.';
            }
        } else if ($request->resend_type == 2) {
            $otp1 = rand(100, 999);
            $otp2 = rand(100, 999);
            $otp = $otp1.'-'.$otp2;

            $otp_details = $this->user->where('id', $request->user_id)->first();
            
            if(isset($otp_details) && !empty($otp_details)) {
                $otp_details->otp = $otp1.$otp2;
                $otp_details->save();   

                $otp_details->otp = $otp1.$otp2;
                $otp_details->save();

                $client = new Client();
                $authkey = env('AUTHKEY');
                $phone_number = $otp_details->phone_number;
                $senderid = env('SENDERID');
                $hash = env('SMSHASH');
                //$message="Your OTP for Darbaar Mart is ".$otp_details->otp;
                
                $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar $hash");

                  // echo "http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message='".$message."'&sender=".$senderid."&route=1&country=0"; die;
                //$response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=1207162028126071690");
                //$statusCode = $response->getStatusCode();
                

                $response_data['message'] = 'OTP send successfully.';
                 
            } else {
                $response_data['message'] = 'Invalid selection.';
            }
        } else {
            $response_data['message'] = 'Invalid selection.';
        }
        $user_details = DB::table('users')
                    ->select(array('id','phone_number','otp'))
                    ->where('id',$request->user_id)
                    ->get();
        $response_data['code'] = 1;
        $response_data['error'] = false;
        $response_data['data'] =$user_details;
        return response()->json($response_data, 201);
    }
    
    public function getAddressBylatlong(Request $request)
    {
        $lat = $request->lat;
        $long = $request->long;
        $APIKEY = "AIzaSyC63C7UGlNd9s0QaZbzPNrVD5NiwpKj2nA"; // Replace this with your google maps api key 
        $latlong = $lat.','.$long;
        $googleMapsUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latlong . "&language=ar&key=" . $APIKEY;

        $response = file_get_contents($googleMapsUrl);

        $response = json_decode($response, true);
        $results = $response["results"];
        $addressComponents = $results[0]["address_components"];
        $cityName = "";
        $stateName = "";
        $areaName = "";
        $codeName = "";

        foreach ($addressComponents as $component) {
            // echo $component;
            $types = $component["types"];
            if (in_array("locality", $types) && in_array("political", $types)) {
                $cityName = $component["long_name"];
            }
            if (in_array("postal_code", $types)) {
                $codeName = $component["long_name"];
            }
            if (in_array("administrative_area_level_1", $types) && in_array("political", $types)) {
                $stateName = $component["long_name"];
            }
            if (in_array("sublocality", $types) && in_array("political", $types)) {
                $areaName = $component["long_name"];
            }
        }
        $addressl = array('city'=> $cityName, 'area'=> $areaName, 'postal_code'=> $codeName, 'state'=> $stateName);
        $response_data['code'] = 1;
        $response_data['error'] = false;
        $response_data['data'] =$addressl;
        return response()->json($response_data, 201);
    }

    public function changeLanguage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'language' => 'required|in:en,ar',

        ]);
        if ($validator->fails()) {

            return $this->validationErrorResponse($validator);

        }
        App::setLocale($request->language);



        $user = $this->user->findOrFail(Auth::guard('api')->user()->id);
        $user->fill($request->only('language'))->save();

        return $this->showResponse($user);

    }
     public function driverChangeLanguage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'language' => 'required|in:en,ar',
            'user_id' => 'required|integer',

        ]);
        if ($validator->fails()) {

            return $this->validationErrorResponse($validator);

        }
        App::setLocale($request->language);
        $user = $this->user->findOrFail($request->user_id);
        $user->fill($request->only('language'))->save();

        return $this->showResponse($user);

    }

    /**
     * details api
     * @return \Illuminate\Http\Response

     */

    public function details()

    {

         try {
            $user = Auth::guard('api')->user();
            if(!empty($user->membership)){
                $user->is_membership =true;
            }else{
                $user->is_membership =false;
            }
             $tomorrow_date = now()->addDay(2);
             $membership_to = date('Y-m-d',strtotime($user->membership_to));
            if($membership_to==$tomorrow_date){
                $user->near_expire =true;
            }else{
                $user->near_expire =false;
            }
              
            $address = $this->deliverylocation->where(["user_id"=>$user->id])->first();
      if(!empty($address)){
        $user->address = $address->address;
      }else{  $user->address = "";   }
            return $this->showResponse($user);
        } catch (\Exception $e) {
            return $this->unauthenticatedResponse();
        }


    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        try {
            $user  = $this->user->findOrFail(Auth::guard('api')->user()->id);
            $user->fill(['device_token'=>''])->save();
            $response_data['code'] = 1;
            $response_data['error'] = false;
            $response_data['data'] = '';
            $response_data['token'] = ''; 
            $response_data['message'] = 'successful logout';
            return response()->json($response_data, 201);
        } catch (\Exception $e) {
            return $this->notFoundResponse($e);
        }
    }
/****************************Driver Register*********************************************/

 public function driverRegister(Request $request)
    {
    $fields=$request->all();
    $user =  $this->user->where(['phone_number'=>$request->phone])->first();
    //print_r($user) ;
    if(!empty($user)){

        return $this->showResponse($data="",$message='you are already registered with this number');


    }else{

        $dataopt = $this->generateOTP();

        $fields['otp']=$dataopt;
        //$fields['phone_code']=91;
        $fields['user_type']="driver";
        //print_r($fields);die;
        $this->user->fill($fields)->save($fields);


        return $this->showResponse($data="",$message='Please enter otp');


    }
    }
    /****************************Driver Profile Update*********************************************/
 public function driverUpdate(Request $request)
    {
            $fields=$request->all();
            $user =  $this->user->where(['id'=>$request->user_id])->first();
            //print_r($user) ;die;
        if(empty($user)){

            return $this->showResponse($data="",$message=trans('user.check_details'));


        }else{
            $input = $request->all();
            $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        }else{
            $user= $this->user->FindOrFail($request->user_id)->fill($input)->save();
            return $this->showResponse($user);
        }

        }
    }
    /****************************Driver Profile Update*********************************************/
public function driverUpdateUserLocation(Request $request)
    {
        
        //return 'hi';
        
        $fields=$request->all();
         //return $fields;
        $order =  $this->order->with(['User.deliveryLocation','User','driver'])->find($request->order_id);
        //return $order;

        if(empty($order)){

            return $this->showResponse($data="",$message=trans('user.check_details'));


        }else{
            try {
                $UserName = $order->User->name;
                $DriverName = $order->driver->name;
                $locations= $this->deliverylocation->findOrFail($order->shipping_location->id);
                $shipping_location['id'] = $order->shipping_location->id;
                $shipping_location['name'] = $request->name;
                $shipping_location['address'] = $request->address;
                $shipping_location['lat'] = $request->lat;
                $shipping_location['lng'] = $request->lng;
                $shipping_location['description'] = $request->description;
                $shipping_location['customer_id'] = $order->user_id;
                
                $this->deliverylocation->where(["id"=>$order->shipping_location->id])->update(['actual_address' => json_encode($shipping_location)]);
                $toUser = User::find(1);
                $toUser->notify(new AddressUpdate($UserName,$DriverName,$shipping_location));
                return $this->showResponse(trans('site.update'));

            } catch (\Exception $e) {
               echo $e->getMessage();die;
                //return $this->validationErrorResponse($e->getMessage());

        }

        
    }
}

/*






    /****************************Driver Profile *********************************************/
 public function driverProfile(Request $request)
    {
            $fields=$request->all();
            $user =  $this->user->where(['id'=>$request->user_id])->first();
            //print_r($user) ;die;
        if(empty($user)){

            return $this->showResponse($data="",$message=trans('user.check_details'));


        }else{
            
            
            
            return $this->showResponse($user);
        }

        }
  

/************************************* login with  driver*****************/



public function driverLogin(Request $request){
    
        $validator = Validator::make($request->all(), [

            'device_type'=>'required|in:A,I',
            'device_id' => 'required',
            'device_token' => 'required',
            'phone_code' => 'required',
            'phone_number' => 'required',
            'password' => 'required',

        ]);
        $dataInput =$request->all();
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        }else{
            //echo $request->password = Hash::make($request->password);die;
        
            if(Auth::attempt(['phone_number' => $dataInput['phone_number'], 'password' => $request->password,'user_type' => 'driver'])){

            $user = Auth::user();

            if($user->status == 0){
                        $response = [
                        'code' => 1,
                        'error' => true,
                        'message' => (trans('user.invalid_user')),
                    ];
                    $driver_id_array = [0=>$user->device_token];
                    $driver_device_type = $user->device_type;
                    $dataArray = [];
                    $dataArray['type'] = 'Deactivated';
                    $dataArray['title'] = trans('user.invalid_user');
                    $dataArray['body'] = trans('user.invalid_user');
                    //Helper::sendNotification($driver_id_array ,$dataArray, $driver_device_type);
                    return response()->json($response, 200);
            }

            $user['token'] =  $user->createToken('grocery')->accessToken;
            $this->user->where('id', $user->id)->update(['remember_token' => $user['token'],'device_id' =>$request->device_id,'device_token' => $request->device_token,'device_type' => $request->device_type]);

            return $this->showResponse($user);

        }else{
            $response = [
                'code' => 1,
                'error' => true,
                'message' => (trans('user.check_details')),
            ];
            return response()->json($response, 200);
               //return $this->userNotExistResponse();
        }


        }


    }

    public function locationTracker(Request $request){
         $listAddress = [];
        if(isset($request->order_id)){
            $orders = $this->order->with(['ProductOrderItem', 'vendor', 'driver', 'shopper', 'User'])->where("id", $request->order_id)->first();
                foreach ($orders->ProductOrderItem as $order) {
                    $minus_amt = 0;
                    $related = json_decode($order->data);
                    $mClass = '';
                    $mClass = MeasurementClassTranslation::where('measurement_class_id',2)->where('locale',App::getLocale())->first();
                    //isset($mClass->name) ? $mClass->name: '';
                    $arraylist['product_id'] = $related->vendor_product->product_id;
                    $arraylist['price'] = $related->vendor_product->price;
                     if($order->offer_type == 'percentages'){
                        $minus_amt = ($related->vendor_product->price * $order->offer_value) /100;
                    }
                     if($order->offer_type == 'amount'){
                        $minus_amt = $order->offer_value;
                    }
                    $arraylist['discounted_price'] = $related->vendor_product->price - $minus_amt;
                    $arraylist['offer_type'] =  $order->offer_type;
                    $arraylist['offer_value'] =  $order->offer_value;
                    $arraylist['name'] = isset($related->vendor_product->product->name) ? $related->vendor_product->product->name : '';
                    $arraylist['image'] = isset($related->vendor_product->product->image->name) ? $related->vendor_product->product->image->name : '';
                    $arraylist['qty'] = $order->qty;
                    if(isset($related->vendor_product->measurementclass) && isset($related->vendor_product->measurement_value)){
                        // $arraylist['measurement_class'] = $related->vendor_product->measurementclass ;
                        $arraylist['measurement_class'] = isset($mClass->name) ? $mClass->name: '';
                        
                        $arraylist['measurement_value'] =  $related->vendor_product->measurement_value ;
                    }else{
                         $arraylist['measurement_class'] = '';
                        $arraylist['measurement_value'] =  '';
                    }
                   
                    $arraylist['order_id'] = $order->order_id;
                    $arraylist['id'] = $order->id; /*unique id of product order item table*/
                    $arraylist['status'] = Helper::$product_status[$order->status];
                    $arraylist['data'] = $related;
                   
                   
                    $listAddress[] = $arraylist;
                }
                $orders->productOrderItem = $listAddress;
                $collection = new Collection($orders);
                $collection->forget('product_order_item');
                
                
                 
                //collect($orders)->forget('product_order_item');
                
                // return $filtered;
                
                    //return $ordersFinal;
                  /* $orders =  $this->order->where('id',$request->order_id)->with('ProductOrderItem')->with('User')->with('vendor')->with('driver')->with('shopper');
                    $orders =  $orders->get(); */
            return $this->showResponse($collection); 
        }else{
            return $this->userNotExistResponse();
        }
        
    }
    
     /************ calculate distance between two coordinates*****************/
    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        /*units- "M" for Miles, "K" for km,"N" for Nautical Miles*/

          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper($unit);

          if ($unit == "K") {
              return ($miles * 1.609344);
          } else if ($unit == "N") {
              return ($miles * 0.8684);
          } else {
              return $miles;
          }
    }
    
    /************************************* driver order List*****************/

private static function sortByDistance($a, $b)
        {
            $a = $a['distance_km'];
            $b = $b['distance_km'];

            if ($a == $b) return 0;
            return ($b < $a) ? -1 : 1;
        }
        private static function sortByDistanceb($a, $b)
        {
            $a = $a['distance_km'];
            $b = $b['distance_km'];

            if ($a == $b) return 0;
            return ($b > $a) ? -1 : 1;
        }


public function driverOrderList(Request $request){


    //type 1 for upcoming
    $driverLat = '';
    $driverLng = '';
    $user = User::findOrFail($request->user_id);
    if(isset($request->current_lat)){ $driverLat = $request->current_lat; }else{
        
        $driverLat = isset($user) ? $user->current_lat : '';
    }
    if(isset($request->current_lng)){ $driverLng = $request->current_lng; }else{
        $driverLng = isset($user) ? $user->current_lng : '';
    }
   
    if($request->type==1){
        $orders =  $this->order->where(['driver_id'=>$request->user_id])->where('delivery_date', '>', date('Y-m-d'))->with('ProductOrderItem')->get();
        $orders = $orders->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R');
        //return count($orders);
        $neworderList=array();
        $neworder=array();
          foreach($orders as $order){
              $neworder[]= $order->delivery_date;
          }
          if(!empty($neworder)){
          $adate_array=array_unique($neworder);
         
          $arraylist=array();
        foreach($adate_array as $dataarray){
             
            $neworderList1['date']= $dataarray;
            $ordersnew =  $this->order->where(['driver_id'=>$request->user_id])->where('delivery_date', '=', $dataarray)->with('ProductOrderItem','User','shopper','driver')->get();  
            $ordersnew = $ordersnew->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R');
             
                $neworderList1['dilevery_date']=array();
                $arraylist=array();
                $arraylistA=[];
                $refrenceArray=[];
               
               foreach($ordersnew as $key=>$newdata){
                    $deliveryLocLat = isset($newdata->shipping_location->lat) ? $newdata->shipping_location->lat : '';
                    $deliveryLocLng = isset($newdata->shipping_location->lng) ? $newdata->shipping_location->lng : '';    
                    $timeslot = $newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;                    
                    $arraylist['timeslot']=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;

                    $arraylist['id']=$newdata->id;
                    $arraylist['user_name']= $newdata->shipping_location->name;
                    $arraylist['user_id']=$newdata->user_id;
                    $arraylist['date']= $newdata->delivery_date;
                    $arraylist['order_id']= $newdata->order_code;
                    $arraylist['status']= Helper::$order_status[$newdata->order_status];
                    $arraylist['items']= count($newdata->ProductOrderItem);
                    $arraylist['sub_total'] = number_format($newdata->total_amount + $newdata->coupon_amount,2, '.', ''); 
                    $arraylist['shipping_price'] = $newdata->delivery_charge;
                    $arraylist['price'] = number_format((float)$newdata->offer_total + $newdata->delivery_charge,2, '.', ''); 
                    $arraylist['from_time']= $newdata->delivery_time->from_time;
                    $arraylist['to_time']= $newdata->delivery_time->to_time;   
                    $arraylist['driver_name']= $newdata->driver->name; 
                    $arraylist['shoper_name']= $newdata->shopper->name; 
                    $arraylist['customer_name']= $newdata->User->name;  
                    $arraylist['distance_km']= number_format((float)$this->distance($driverLat, $driverLng, $deliveryLocLat , $deliveryLocLng, "K"), 4, '.', '');

                     if(is_array($arraylistA) && !empty($arraylistA)){
                           
                            if(in_array($newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time, $refrenceArray)){
                                $found_key=array_search($newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time, $refrenceArray);
                                $arraylistA[$found_key]['time_array'][]=$arraylist;
                                $arraylistA[$found_key]['time_name']   = $newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                                $arraylistA[$found_key]['timeslot']   = $newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                                $arraylistA[$found_key]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);
                            }else{
                                $arraylistA[$newdata->id]['time_array'][]=$arraylist;
                                $arraylistA[$newdata->id]['time_name']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                                $arraylistA[$newdata->id]['timeslot']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                                $arraylistA[$newdata->id]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);

                                $refrenceArray[$newdata->id]=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                            }
                           
                        }else{
                            $refrenceArray[$newdata->id]=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                            $arraylistA[$newdata->id]['time_array'][] =$arraylist;
                            $arraylistA[$newdata->id]['time_name']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                            $arraylistA[$newdata->id]['timeslot']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                             $arraylistA[$newdata->id]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);
                        }
                }
                $i=0;
                foreach ($arraylistA as $key => $value) {
                    $completeArrat[$i]=$value;
                    usort($completeArrat[$i]['time_array'], array($this,'sortByDistanceb')) ;
                    $i++;

                }
                //print_r($completeArrat);die;
                usort($completeArrat, array($this,'sortByName')) ;
                $neworderList1['dilevery_date']   = $completeArrat;
                $neworderLists[]= $neworderList1;
              
        }
      }else{
      }
    }elseif($request->type==2){ //type 2 for today's order list
            $orders =  $this->order->where(['driver_id'=>$request->user_id])->where('delivery_date', '=', date('Y-m-d'))->with('ProductOrderItem')->get();
            $orders = $orders->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R');
             $neworderList=array();

             $neworder=array();
             
             //print_r($orders->toArray());die;
             
          
          if(!empty($orders)){
            foreach($orders as $order){
              
              $neworder[]= $order->delivery_time_id;
              
          }
          $adate_array=array_unique($neworder);
          
           $arraylist=array();
          foreach($adate_array as $dataarray){
             
            
            
        $ordersnew =  $this->order->where(['driver_id'=>$request->user_id])->where('delivery_date', '=', date('Y-m-d'))->where('delivery_time_id', '=', $dataarray)->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R')->with('ProductOrderItem','User','shopper','driver')->get();   
        
    
              $neworderList1['dilevery_date']=array();
               
               foreach($ordersnew as $key=>$newdata){
                $deliveryLocLat = isset($newdata->shipping_location->lat) ? $newdata->shipping_location->lat : '';
                    $deliveryLocLng = isset($newdata->shipping_location->lng) ? $newdata->shipping_location->lng : ''; 
                    if(!empty($newdata->delivery_time)){ 
                        $neworderList1['date']= $newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;

                        $neworderList1['from_timestamp']= strtotime($newdata->delivery_time->from_time);

                        $arraylist['timeslot']=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                    }else{
                        $neworderList1['date']= "Fast Delivery";
                        $neworderList1['from_timestamp']= "";
                         $arraylist['timeslot']= "Fast Delivery";
                    }

                    $arraylist['id']=$newdata->id;
                    $arraylist['user_id']=$newdata->user_id;
                    $arraylist['user_name']= $newdata->shipping_location->name;
                    $arraylist['date']= $newdata->delivery_date;
                    $arraylist['order_id']= $newdata->order_code;
                    $arraylist['status']= Helper::$order_status[$newdata->order_status];
                    $arraylist['items']= count($newdata->ProductOrderItem);
                    $arraylist['sub_total'] = number_format($newdata->total_amount + $newdata->coupon_amount,2, '.', ''); 
                    $arraylist['shipping_price'] = $newdata->delivery_charge;
                    $arraylist['price'] = number_format((float)$newdata->offer_total + $newdata->delivery_charge,2, '.', ''); 
                    $arraylist['driver_name']= $newdata->driver->name; 
                    $arraylist['shoper_name']= $newdata->shopper->name; 
                    $arraylist['customer_name']= $newdata->User->name;  

                    $arraylist['distance_km']= number_format((float)$this->distance($driverLat, $driverLng, $deliveryLocLat , $deliveryLocLng, "K"), 4, '.', '');  
                   $neworderList1['dilevery_date'][]=$arraylist;
                   }
                   //usort($neworderList1, array($this,'sortByName')) ;
               usort($neworderList1['dilevery_date'], array($this,'sortByDistanceb'));
                
                //$neworderLists[]= array_reverse($neworderList1);
                $neworderLists[]= $neworderList1;
                usort($neworderLists, array($this,'sortByName')) ;
          }
      }else{
           return $this->userNotExistResponse('No order');
      }
        }else{  //type 3 for all order list
            $orders =  $this->order->where(['driver_id'=>$request->user_id])->with('ProductOrderItem')->get();
            $neworder=array();
            foreach($orders as $order){
              
              $neworder[]= $order->delivery_date;
                  
            }
            if(!empty($neworder)){
            $adate_array=array_unique($neworder);          
            $arraylist=array();
            foreach($adate_array as $dataarray){
             
                $neworderList1['date']= $dataarray;
                $ordersnewSql =  $this->order->where(['driver_id'=>$request->user_id])->where('delivery_date', '=', $dataarray)->with('ProductOrderItem','User','shopper','driver');  
                $ordersnew =$ordersnewSql->where('deleted_at',null)->get();
                $neworderList1['dilevery_date']=array();
                $arraylistA=[];
                $refrenceArray=[];
                $index=rand(999,2);                
                // $arraylist=[];
                
                foreach($ordersnew as $key=>$newdata){
                    if(isset($newdata->delivery_time)){
                        $timeslot=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                    }else{
                        //$timeslot=$newdata->id.'---hiiii';
                        $timeslot='';
                    }
                    
                        //echo "<pre>"; print_r($timeslot);  
                     if(isset($newdata->delivery_time)){
                         $arraylist['timeslot']=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                    }else{
                        $arraylist['timeslot']= '';
                    }
                   

                    $arraylist['id']=$newdata->id;
                    $arraylist['user_name']= $newdata->shipping_location->name;
                    $arraylist['user_id']=$newdata->user_id;
                    $arraylist['date']= $newdata->delivery_date;
                    $arraylist['order_id']= $newdata->order_code;
                    $arraylist['status']= Helper::$order_status[$newdata->order_status];
                    $arraylist['items']= count($newdata->ProductOrderItem);
                    $arraylist['sub_total'] = number_format($newdata->total_amount + $newdata->coupon_amount,2, '.', '');
                    $arraylist['shipping_price'] = $newdata->delivery_charge;
                    $arraylist['price'] = number_format((float)$newdata->offer_total + $newdata->coupon_amount +  $newdata->delivery_charge,2, '.', ''); 
                    $arraylist['to_date']= isset($newdata->delivery_time->to_time) ? $newdata->delivery_time->to_time: '';   
                    $arraylist['from_date']= isset($newdata->delivery_time->from_time) ? $newdata->delivery_time->from_time: '';
                    $arraylist['driver_name']= $newdata->driver->name; 
                    $arraylist['shoper_name']= $newdata->shopper->name; 
                    $arraylist['customer_name']= $newdata->User->name; 
                    
                    if(is_array($arraylistA) && !empty($arraylistA)){
                       if(!empty($newdata->delivery_time)){
                        if(in_array($newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time, $refrenceArray)){
                            $found_key=array_search($newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time, $refrenceArray);
                            $arraylistA[$found_key]['time_array'][]=$arraylist;
                            $arraylistA[$found_key]['time_name']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                            $arraylistA[$found_key]['timeslot']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                            $arraylistA[$found_key]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);

                        }else{
                            $arraylistA[$newdata->id]['time_array'][]=$arraylist;
                            $arraylistA[$newdata->id]['time_name']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                            $arraylistA[$newdata->id]['timeslot']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                             $arraylistA[$newdata->id]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);


                            $refrenceArray[$newdata->id]=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                        }
                       }
                    }else{
                        $refrenceArray[$newdata->id]=(!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                        $arraylistA[$newdata->id]['time_array'][]   =$arraylist;
                        $arraylistA[$newdata->id]['time_name']   = (!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                        $arraylistA[$newdata->id]['timeslot']   = (!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                         $arraylistA[$newdata->id]['from_timestamp'] =  (!empty($newdata->delivery_time))?strtotime($newdata->delivery_time->from_time):"";

                    }
                }
                $i=0;
                foreach ($arraylistA as $key => $value) {
                    $completeArrat[$i]=$value;
                    $i++;
                }
                // print_r(reset($arraylistA));
                usort($completeArrat, array($this,'sortByName')) ;
                $neworderList1['dilevery_date']=$completeArrat;
                $neworderLists[]= $neworderList1;
            }
      }
      else{
          return $this->userNotExistResponse('No order');
          
      }
          }  
            if (!empty($neworderLists)) {
                return $this->showResponse($neworderLists);
            }else{
                return $this->userNotExistResponse('No order');
            
        }


}
    
    
/*group by key in an associative array*/
function group_by($key, $data) {
    $result = array();

    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        }else{
            $result[""][] = $val;
        }
    }

    return $result;
}
    
    
    
    /************************************* driver order details*****************/



public function driverOrderDetail(Request $request){

    $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'type' => 'required',
            ]);
        
    if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
    }
    $paymentmodes = array('1'=>"COD","2"=>"Wallet","3"=>"Online Payment");
    if(isset($request->order_id) &&  !empty($request->order_id)){ 
    $orders = $this->order->with(['ProductOrderItem','zone','vendor','driver','shopper','User'])->where("id","=",$request->order_id)->first();
  
   }else{
       $orders ="";
   }
       if(!empty($orders->toArray())){
      if($request->type==1){
        $locations= $this->deliverylocation->where(['id'=>$orders->shipping_location->id])->select("actual_address")->get()->toArray();
       
            $arraylist['address_id']=$orders->shipping_location->id;
            $arraylist['name']= $orders->shipping_location->name;
            $arraylist['address']= $orders->shipping_location->address;
            $arraylist['description']= isset($orders->shipping_location->description) ? $orders->shipping_location->description : '';
            $arraylist['lat']= $orders->shipping_location->lat;
            $arraylist['lng']= $orders->shipping_location->lng;
            $arraylist['user_id']= $orders->User->id;
            $arraylist['order_id']= $orders->id;
            $arraylist['driver_id']= $orders->driver->id;

          
            $listAddress[]=$arraylist;
           
        }elseif($request->type==2){

            $minus_amt =0;
            foreach($orders->ProductOrderItem as $order){
                $related =json_decode($order->data);
                 $productData =  $this->product->with(['image'])->find($related->vendor_product->product_id);
                $arraylist['product_id'] = isset($related->vendor_product->product_id) ? $related->vendor_product->product_id: '';
                    $arraylist['price'] = isset($related->vendor_product->price) ? number_format($related->vendor_product->price,2,'.',''): '';
                    $arraylist['name'] = isset($related->vendor_product->product->name) ? $related->vendor_product->product->name: '';
                    $arraylist['image'] =isset($related->vendor_product->product->image) ? $related->vendor_product->product->image->name:(isset($productData->image))?$productData->image->name:"";
                    $arraylist['qty'] = $order->qty;
                    $arraylist['order_id']= $order->order_id;
                    $arraylist['measurement_class'] = isset($related->vendor_product->measurementclass) ? $related->vendor_product->measurementclass: '';
                    $arraylist['measurement_value'] = isset($related->vendor_product->product->measurement_value) ? $related->vendor_product->product->measurement_value: '';
        
            /*    $arraylist['product_id']=$related->vendor_product->product_id;
                $arraylist['price']=$related->vendor_product->price;
                $arraylist['name']=$related->vendor_product->product->name;
                $arraylist['image']=$related->vendor_product->product->image->name;
                $arraylist['qty']=$order->qty;
                $arraylist['order_id']= $order->order_id;
                $arraylist['measurement_class'] = $related->vendor_product->measurementclass;
                $arraylist['measurement_value'] = $related->vendor_product->product->measurement_value;
               */
                        if($order->is_offer=='yes'){
                           
                            $offerData =json_decode($order->offer_data);
                            if($offerData->offer_type == 'percentages'){
                                $minus_amt = number_format( (($related->vendor_product->price * $order->offer_value) /100),2,'.','');
                            }
                            if($offerData->offer_type == 'amount'){
                                $minus_amt = $order->offer_value;
                            }
                            $arraylist['offer_type']=$offerData->offer_type;
                            $arraylist['offer_value']=$offerData->offer_value;
                            $arraylist['to_time']=$offerData->to_time;
                            $arraylist['from_time']=$offerData->from_time;
                            $arraylist['minus_amt']=$minus_amt;
                            $arraylist['discounted_price']=number_format( ($related->vendor_product->price) - $minus_amt ,2,'.','');

                        }else{
                             $arraylist['offer_type']=null;
                            $arraylist['offer_value']=null;
                            $arraylist['to_time']=null;
                            $arraylist['from_time']=null;
                            $arraylist['discounted_price']=number_format($related->vendor_product->price,2,'.','');

                        }
                
                $listAddress[]=$arraylist;  
                
            
                }
               // die;
        }else{

            // print_r($orders->toArray());die;
            $arraylist['order_code']=$orders->order_code;
            if(isset($orders->order_status) && !empty($orders->order_status)){
            $arraylist['status']=Helper::$order_status[$orders->order_status];
            }
            $arraylist['total_amount']=($orders->offer_total);
            $arraylist['transaction_id']=($orders->transaction_id);
            $arraylist['payment_mode']= $paymentmodes[$orders->payment_mode_id];
            $arraylist['transaction_status']=Helper::$transaction_status[$orders->transaction_status];
            $arraylist['coustomer']=$orders->User->name;
            $arraylist['delivery_address']=$orders->shipping_location->name;
            $arraylist['shipping_address']=$orders->shipping_location->address;
            $arraylist['phone_number']=$orders->user->phone_code."-".$orders->user->phone_number;
            $arraylist['lat']=$orders->shipping_location->lat;
            $arraylist['lng']=$orders->shipping_location->lng;
            if(!empty($orders->zone)){
            $arraylist['zone']=$orders->zone->name;
            }else{
            $arraylist['zone']="";
            }
            $arraylist['shopper']=$orders->shopper->full_name;
            $arraylist['driver']=$orders->driver->full_name;
            $arraylist['customer']=$orders->user->full_name;
            $arraylist['sub_total']=number_format($orders->total_amount + $orders->coupon_amount  - $orders->delivery_charge,2,'.','');
            $arraylist['delivery_charge']=$orders->delivery_charge;
            $arraylist['delivery_date']=$orders->delivery_date;
            $arraylist['delivery_time']= $orders->delivery_time->from_time."-".$orders->delivery_time->to_time;
            $arraylist['promo_code_disc']= number_format($orders->coupon_amount,2,'.','');
            $arraylist['product_discount']= number_format($orders->total_amount - $orders->offer_total,2,'.','');
            $arraylist['vendor']=$orders->vendor->full_name;
            $arraylist['delivery_charge']= number_format($orders->delivery_charge,2,'.','');
            $arraylist['number_of_items']=count($orders->ProductOrderItem);
            $listAddress[]=$arraylist;
            
        }
    }else{
         return $this->userNotExistResponse('No order');
        
    }
        
         if (!empty($listAddress)) {
                return $this->showResponse($listAddress);
            }else{
                return $this->userNotExistResponse('No order');
            


        }

        
        

}
    
    /************************************* driver order Completd list*****************/




    /************************************* driver order Notification*****************/



public function driverOrderNotificationList(Request $request){


            $orders =  $this->orderstatus->where(['user_id'=>$request->user_id])->with(["ProductOrder",'User'])->get();
      //  print_r($orders);die;
            if (!empty($orders->toArray())) {
                return $this->showResponse($orders);
            }else{
                return $this->userNotExistResponse('No order');
            


        }


    }
    
public function driverDiliveryConfirm(Request $request){


            $orders =  $this->order->where(['user_id'=>$request->user_id,'id'=>$request->order_id])->get();
     
            $input=$request->all();
        //  print_r($input);
        //  print_r($orders);die;
            $order= $this->order->FindOrFail($request->order_id)->fill($input)->save();
            return $this->showResponse($orders);
      
      
      
      
            if (!empty($orders->toArray())) {
                return $this->showResponse($orders);
            }else{
                return $this->userNotExistResponse('No order');
            


        }


    }
public function driverOrderReturn(Request $request){


            $orders =  $this->order->where(['user_id'=>$request->user_id,'id'=>$request->order_id])->get();

            $input=$request->all();
            $input['order_status']='R';
            //print_r($input);
            //  print_r($input);die;
            
            $order= $this->order->FindOrFail($request->order_id)->fill($input)->save();
            return $this->showResponse($orders);
      
      
      
      
            if (!empty($orders->toArray())) {
                return $this->showResponse($orders);
            }else{
                return $this->userNotExistResponse('No order');
            


        }


    }
    
    
        /************************************* driver Assignment*****************/



public function driverAssignment(Request $request){


            $lists =  $this->user->where(['id'=>$request->driver_id,'user_type'=>'driver'])->first();
            if($lists){
            $zones=$lists->toArray();
            
    //  print_r($lists);
        //print_r($zones["zone_id"]);die;
        if(!empty($zones["zone_id"])){
            if(is_array($zones['zone_id']) && !empty($zones['zone_id'])){
            foreach($zones['zone_id'] as $zone){
                //echo $zone;
                if(!empty($zone)){
            $zonedata=  $this->zone->where("id","=", $zone)->first();
                $zone_list['id']=$zonedata['id'];
                $zone_list['name']=$zonedata['name'];
                $lists_zone[]=$zone_list;
                //echo "<pre>";print_r($zonedata);die;
            }else{
                return $this->userNotExistResponse('No assignment');
                
            }
            }
            
        }else{
            
            return $this->userNotExistResponse('No assignment');
        }
    }else{
    return $this->userNotExistResponse('No assignment');
        
    }
            
       
        
            if (!empty($lists_zone)) {
                return $this->showResponse($lists_zone);
            }else{
                return $this->userNotExistResponse('No assignment');
            


        }


    }else{
        return $this->userNotExistResponse('No assignment');
        
    }
}
        /*************************************Order Status Management*****************/



public function orderStatusChange(Request $request){

    $order =  $this->order->where(['id'=>$request->order_id])->first();

    if(!empty($order)){
        $this->order->where('id',$request->order_id)->update(['order_status' => $request->order_status]);
        /*send notification to customer*/
        if($request->order_status == 'S' || $request->order_status == 'A' || $request->order_status == 'D' || $request->order_status == 'R'){
            $user_id = $order->user_id;
            $shopper_id = $order->shopper_id;
            $userData = User::whereIn('id', [$shopper_id, $user_id])->select('id','device_type','device_token','name')->get();
            $user_id_array = collect($userData)->where('id', $user_id)->pluck('device_token');
            $shopper_id_array = collect($userData)->where('id', $shopper_id)->pluck('device_token');
            $dataArray = [];
            $dataArray['type'] = 'Order';
            $dataArray['product_type'] = Helper::$order_status[$request->order_status];
            $dataArray['title'] = 'Order '.Helper::$order_status[$request->order_status];
            $dataArray['body'] = 'Your order is '.Helper::$order_status[$request->order_status]. ' '.$order->order_code;
            $device_type_array = collect($userData)->where('id', $user_id)->pluck('device_type');
            $device_type = $device_type_array[0];
            $shopper_device_type_array = collect($userData)->where('id', $shopper_id)->pluck('device_type');
            $shopper_device_type = $shopper_device_type_array[0];
            
            Helper::sendNotification($user_id_array ,$dataArray, $device_type);
              /*send notification to shopper*/
              /*overwrite  message body for shopper*/
            $dataArray['body'] = 'Order '.$order->order_code.' is '.Helper::$order_status[$request->order_status];
            Helper::sendNotification($shopper_id_array ,$dataArray, $shopper_device_type);

        /*admin notifications*/
            $sendor = $userData[0]->name;
            $order->user->notify(new OrderStatus($order,$sendor));
        }

        return $this->showResponse("Status update");   
    }else{
        return $this->userNotExistResponse("Not updated");
    }
            
         
      
}
    
    public function shopperLogin(Request $request) {
        $validator = Validator::make($request->all(), [
            'device_type'=>'required|in:A,I',
            'device_id' => 'required',
            'device_token' => 'required',
            'phone_code' => 'required',
            'phone_number' => 'required',
            'password' => 'required'
        ]);
        
        $dataInput = $request->all();
      
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        } else {
         
            if (Auth::attempt(['phone_number' => $dataInput['phone_number'], 'password' => $request->password,'user_type' => 'shoper'])) {
                $user = Auth::user();
             
                if($user->status == '0'){
                        $response = [
                        'code' => 1,
                        'error' => true,
                        'message' => (trans('user.invalid_user')),
                    ];
                    $shopper_id_array = [0=>$user->device_token];
                    $shopper_device_type = $user->device_type;
                    $dataArray = [];
                    $dataArray['type'] = 'Deactivated';
                    $dataArray['title'] = trans('user.invalid_user');
                    $dataArray['body'] = trans('user.invalid_user');
                    //Helper::sendNotification($shopper_id_array ,$dataArray, $shopper_device_type);
                    return response()->json($response, 200);
                }
                //print_r($user);die;
                $user['token'] =  $user->createToken('grocery')->accessToken;
                $this->user->where('id', $user->id)->update([
                    'remember_token' => $user['token'],
                    'device_id' => $request->device_id,
                    'device_token' => $request->device_token,
                    'device_type' => $request->device_type]
                );
                return $this->showResponse($user);
            } else {
                $response = [
                    'code' => 1,
                    'error' => true,
                    'message' => (trans('user.check_details')),
                ];
                return response()->json($response, 200);
                //return $this->userNotExistResponse();
            }
        }
    }
    
    private static function sortByName($a, $b)
        {
            $a = $a['from_timestamp'];
            $b = $b['from_timestamp'];

            if ($a == $b) return 0;
            return ($a < $b) ? -1 : 1;
        }

         private static function sortByNamea($a, $b)
        {
            $a = $a['from_timestamp'];
            $b = $b['from_timestamp'];

            if ($a == $b) return 0;
            return ($b < $a) ? -1 : 1;
        }
        



    public function shopperOrderList(Request $request) {

        
       
        if ($request->type == 1) {
            $completeArrat=array();
            $orders =  $this->order->where(['shopper_id' => $request->user_id])->where('delivery_date', '>', date('Y-m-d'))->with('ProductOrderItem')->orderBy('delivery_date','DESC')->get();
           
            $orders =  $orders->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R');
             //print_r($orders);die;
            $neworderList = array();
            $neworder = array();
            foreach ($orders as $order) {
              $neworder[] = $order->delivery_date;
            }

            if (!empty($neworder)) {
                $adate_array = array_unique($neworder);
                $arraylist=array();
                $arraylistA=[];
                $refrenceArray=[];
                
                foreach ($adate_array as $dataarray) {
                    $neworderList1['date'] = $dataarray;
                    $ordersnew =  $this->order->where(['shopper_id' => $request->user_id])->where('delivery_date', '=', $dataarray)->with(['ProductOrderItem','User','shopper','driver'])->get();
                    $ordersnew = $ordersnew->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R');
                    $neworderList1['dilevery_date'] = array();
                   //return $ordersnew;
                    foreach ($ordersnew as $key => $newdata) {
                        $timeslot=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                        $arraylist['timeslot'] = $newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;

                        
                        $arraylist['id'] = $newdata->id;
                        $arraylist['user_id'] = $newdata->user_id;
                        $arraylist['user_name'] = $newdata->shipping_location->name;
                        $arraylist['driver_id'] = $newdata->driver_id;
                        $arraylist['driver_name']= $newdata->driver->name; 
                        $arraylist['shoper_name']= $newdata->shopper->name; 
                        $arraylist['customer_name']= $newdata->User->name;  
                        $arraylist['date'] = $newdata->delivery_date;
                        $arraylist['order_id'] = $newdata->order_code;
                        $arraylist['status'] = Helper::$order_status[$newdata->order_status];
                        $arraylist['items'] = count($newdata->ProductOrderItem);
                        $arraylist['sub_total'] = number_format($newdata->total_amount + $newdata->coupon_amount,2,'.','');
                        $arraylist['shipping_price'] = $newdata->delivery_charge;
                        $arraylist['price'] = $newdata->offer_total;  
                        //$arraylist['price'] = $newdata->offer_total + $newdata->delivery_charge;  
                        $neworderList1['dilevery_date'][] = $arraylist;

                         if(is_array($arraylistA) && !empty($arraylistA)){
                           if(!empty($newdata->delivery_time)){
                            if(in_array($newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time, $refrenceArray)){
                                $found_key=array_search($newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time, $refrenceArray);
                                $arraylistA[$found_key]['time_array'][]=$arraylist;
                                $arraylistA[$found_key]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);
                                // $arraylistA[$found_key]['time_name']   =$newdata->delivery_time->from_time."-".$order->delivery_time->to_time;
                                // $arraylistA[$found_key]['timeslot']   =$newdata->delivery_time->from_time."-".$order->delivery_time->to_time;
                            }else{
                                $arraylistA[$newdata->id]['time_array'][]=$arraylist;
                                $arraylistA[$newdata->id]['time_name']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                                $arraylistA[$newdata->id]['timeslot']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                                $arraylistA[$newdata->id]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);
                                $refrenceArray[$newdata->id]=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                            }
                           }
                        }else{
                                $refrenceArray[$newdata->id]=(!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                                $arraylistA[$newdata->id]['time_array'][] =$arraylist;
                                $arraylistA[$newdata->id]['time_name']   =(!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                                $arraylistA[$newdata->id]['from_timestamp'] = (!empty($newdata->delivery_time))?strtotime($newdata->delivery_time->from_time):"";
                                $arraylistA[$newdata->id]['timeslot']   =(!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                        }
                   }

                    $i=0;
                    foreach ($arraylistA as $key => $value) {
                        $completeArrat[$i]=$value;
                        $i++;
                    }
                    usort($completeArrat, array($this,'sortByName')) ;
                   
                    
                   // print_r($completeArrat);
                   $neworderList1['dilevery_date']   =$completeArrat;
                    $neworderLists[]= $neworderList1;
                }
            } else {
            }
        } else if ($request->type == 2) { //type 2 for today's order list
            $orders =  $this->order->where(['shopper_id' => $request->user_id])->where('delivery_date', '=', date('Y-m-d'))->with('ProductOrderItem')->orderBy('delivery_date','DESC')->get();
            $orders =  $orders->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R');
            $neworderList = array();
            $neworder = array();
            //return $orders;
            foreach ($orders as $order) {
                $neworder[] = (!empty($order->delivery_time))?$order->delivery_time->id:0;
            }
            
            if (!empty($orders)) {
                $adate_array = array_unique($neworder);
                $arraylist = array();
                
                foreach ($adate_array as $dataarray) {
                    $ordersnew =  $this->order->where(['shopper_id' => $request->user_id])->where('delivery_date', '=', date('Y-m-d'))->where('delivery_time_id', '=', $dataarray)->with('ProductOrderItem','User','shopper','driver')->get();
                     $ordersnew =  $ordersnew->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R');
                    $neworderList1['dilevery_date'] = array();
                    foreach ($ordersnew as $key => $newdata) {
                        $neworderList1['date'] = $newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                        $neworderList1['from_timestamp']= strtotime($newdata->delivery_time->from_time);
                        $arraylist['timeslot'] = $newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                        $arraylist['id'] = $newdata->id;
                        $arraylist['user_id'] = $newdata->user_id;
                        $arraylist['user_name'] = $newdata->shipping_location->name;
                        $arraylist['driver_id'] = $newdata->driver_id;
                        $arraylist['driver_name']= $newdata->driver->name; 
                        $arraylist['shoper_name']= $newdata->shopper->name; 
                        $arraylist['customer_name']= $newdata->User->name;  
                        $arraylist['date'] = $newdata->delivery_date;
                        $arraylist['order_id'] = $newdata->order_code;
                        $arraylist['status'] = Helper::$order_status[$newdata->order_status];
                        $arraylist['items'] = count($newdata->ProductOrderItem);
                        $arraylist['sub_total'] = number_format($newdata->total_amount  + $newdata->coupon_amount,2,'.','') ;
                        $arraylist['shipping_price'] = $newdata->delivery_charge;
                        $arraylist['price'] = $newdata->offer_total;
                        //$arraylist['price'] = $newdata->offer_total + $newdata->delivery_charge;
                        $neworderList1['dilevery_date'][] = $arraylist;
                    }
                    
                    $neworderLists[] = array_reverse($neworderList1);
                    usort($neworderLists, array($this,'sortByName')) ;
                }
            } else {
                return $this->userNotExistResponse('No order');
            }
        } else { //type 3 for all order list
            $orders =  $this->order->where(['shopper_id' => $request->user_id])->with('ProductOrderItem','User','shopper','driver')->orderBy('delivery_date','DESC')->get();
            
            $neworder = array();
            foreach ($orders as $order) {
              $neworder[] = $order->delivery_date;
            }
           
            
            if (!empty($neworder)) {
                $adate_array = array_unique($neworder);
                 $arraylist=array();
                $arraylistA=[];
                $refrenceArray=[];

                foreach ($adate_array as $dataarray) {
                    $neworderList1['date'] = $dataarray;
                    $ordersnew =  $this->order->where(['shopper_id' => $request->user_id])->where('delivery_date', '=', $dataarray)->with('ProductOrderItem')->orderBy('created_at','DESC')->get();
                    $neworderList1['dilevery_date'] = array();
                    
                    foreach ($ordersnew as $key => $newdata) {
                        $arraylist['timeslot'] = (!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$order->delivery_time->to_time:"";
                        $arraylist['id'] = $newdata->id;
                        $arraylist['user_name'] = $newdata->User->name;
                        $arraylist['driver_id'] = $newdata->driver_id;
                        $arraylist['driver_name']= (isset($newdata->driver))?$newdata->driver->name:""; 
                        $arraylist['shoper_name']= (isset($newdata->shopper))?$newdata->shopper->name:""; 
                        $arraylist['customer_name']= $newdata->User->name;  
                        $arraylist['user_id'] = $newdata->user_id;
                        $arraylist['date'] = $newdata->delivery_date;
                        $arraylist['order_id'] = $newdata->order_code;
                        $arraylist['status'] = Helper::$order_status[$newdata->order_status];
                        $arraylist['items'] = count($newdata->ProductOrderItem);
                        $arraylist['sub_total'] = number_format($newdata->total_amount + $newdata->coupon_amount,2,'.','');
                        $arraylist['shipping_price'] = $newdata->delivery_charge;
                        $arraylist['price'] = $newdata->offer_total; 
                        //$arraylist['price'] = $newdata->offer_total + $newdata->delivery_charge; 
                        $arraylist['from_time'] = (!empty($newdata->delivery_time))?$newdata->delivery_time->from_time:"";
                        $arraylist['to_time'] = (!empty($newdata->delivery_time))?$newdata->delivery_time->to_time:"";  
                        
                        //$neworderList1['dilevery_date'][] = $arraylist;
                     if(is_array($arraylistA) && !empty($arraylistA)){
                           if(!empty($newdata->delivery_time)){
                            if(in_array($newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time, $refrenceArray)){
                                $found_key=array_search($newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time, $refrenceArray);
                                $arraylistA[$found_key]['time_array'][]=$arraylist;
                                $arraylistA[$found_key]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);
                                // $arraylistA[$found_key]['time_name']   =$newdata->delivery_time->from_time."-".$order->delivery_time->to_time;
                                // $arraylistA[$found_key]['timeslot']   =$newdata->delivery_time->from_time."-".$order->delivery_time->to_time;
                            }else{
                                $arraylistA[$newdata->id]['time_array'][]=$arraylist;
                                $arraylistA[$newdata->id]['time_name']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                                $arraylistA[$newdata->id]['timeslot']   =$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                                 $arraylistA[$newdata->id]['from_timestamp'] = strtotime($newdata->delivery_time->from_time);
                                $refrenceArray[$newdata->id]=$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time;
                            }
                           }
                        }else{
                                $refrenceArray[$newdata->id]= (!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                                $arraylistA[$newdata->id]['time_array'][] =$arraylist;
                                $arraylistA[$newdata->id]['time_name']   = (!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                                $arraylistA[$newdata->id]['timeslot']   =  (!empty($newdata->delivery_time))?$newdata->delivery_time->from_time."-".$newdata->delivery_time->to_time:"";
                                $arraylistA[$newdata->id]['from_timestamp'] = (!empty($newdata->delivery_time))?strtotime($newdata->delivery_time->from_time):"";
                        }
                   }
                    //usort($arraylistA, array($this, "sortByTime")); 
                  // print_r($arraylistA);
                    $i=0;
                    foreach ($arraylistA as $key => $value) {
                        $completeArrat[$i]=$value;
                        //usort($completeArrat, array($this, "sortByTime")); 

                        $i++;
                    }
                     usort($completeArrat, array($this,'sortByName')) ;
                    $neworderList1['dilevery_date']   = $completeArrat;
                    $neworderLists[]= $neworderList1;
                }
            } else {
                return $this->userNotExistResponse('No order');
            }
        }  
            
        if (!empty($neworderLists)) {
            return $this->showResponse($neworderLists);
        } else {
            return $this->userNotExistResponse('No order');
        }
    }
    
    
    public function shopperOrderDetail(Request $request) {
       
        if (isset($request->order_id) &&  !empty($request->order_id)) { 
            $orders = $this->order->with(['ProductOrderItem', 'zone', 'vendor', 'driver', 'shopper', 'User'])->where("id", "=", $request->order_id)->first();

            //echo "<pre>"; print_r($orders);die;
        } else {
            $orders ="";
        }

        $paymentmodes = array('1'=>"COD","2"=>"Wallet","3"=>"Online Payment");
        if(isset($orders)){
        if (!empty($orders->toArray())) {
             $listAddress=[];
            if ($request->type == 1) {
                $locations= $this->deliverylocation->where(['id' => $orders->shipping_location->id])->select("actual_address")->get()->toArray();

                
                 /*as per vishnu sir commenting this code
                if(empty($locations[0]['actual_address'])){*/
                    $arraylist['address_id'] = $orders->shipping_location->id;
                    $arraylist['name'] = $orders->shipping_location->name;
                    $arraylist['address'] = $orders->shipping_location->address;
                    $arraylist['description']= isset($orders->shipping_location->description) ? $orders->shipping_location->description : '';
                    $arraylist['lat'] = $orders->shipping_location->lat;
                    $arraylist['lng'] = $orders->shipping_location->lng;
                    $arraylist['user_id'] = $orders->User->id;
                    $arraylist['order_id'] = $orders->id;
                    $arraylist['driver_id'] = $orders->driver->id;
                 /*as per vishnu sir commenting this code
                } else {
                    $newjson=json_decode($locations[0]['actual_address']);
                    $arraylist['address_id'] = $orders->shipping_location->id;
                    $arraylist['name'] = $newjson->name;
                    $arraylist['address'] = $newjson->address;
                    $arraylist['description']= isset($newjson->description) ? $newjson->description : '';
                    $arraylist['lat'] = $newjson->lat;
                    $arraylist['lng'] = $newjson->lng;
                    $arraylist['user_id'] = $orders->User->id;
                    $arraylist['order_id'] = $orders->id;
                    $arraylist['driver_id'] = $orders->driver->id;
                } */
                
                $listAddress[] = $arraylist;
            } else if ($request->type == 2) {

                foreach ($orders->ProductOrderItem as $index=>$order) {
                    $arraylist=[];
                    $minus_amt = 0;
                    $related = json_decode($order->data);
                    // if($index==13){
                    //     print_r($related);die;    
                    // }
                    
                    $productData =  $this->product->with(['image'])->find($related->vendor_product->product_id);
                    
                    if(is_object($related->vendor_product->product->measurement_class)){
                        $measurement_class_id=$related->vendor_product->product->measurement_class->id;
                    }else{
                        $measurement_class_id=$related->vendor_product->product->measurement_class;
                    }
                  
                    $mClass = '';
                    $mClass = MeasurementClassTranslation::where('measurement_class_id',$measurement_class_id)->where('locale',App::getLocale())->first();

                    //isset($mClass->name) ? $mClass->name: '';
                    $arraylist['product_id'] = isset($related->vendor_product->product_id) ? $related->vendor_product->product_id: '';
                    $arraylist['price'] = isset($related->vendor_product->price) ? $related->vendor_product->price: '';
                    $arraylist['name'] = isset($related->vendor_product->product->name) ? $related->vendor_product->product->name: '';
                    $arraylist['image'] =isset($related->vendor_product->product->image->name) ? $related->vendor_product->product->image->name:(isset($productData->image))?$productData->image->name:"";
                    $arraylist['qty'] = $order->qty;
                    $arraylist['measurement_class'] = isset($mClass->name) ? $mClass->name: '';
                    $arraylist['measurement_value'] = isset($related->vendor_product->product->measurement_value) ? $related->vendor_product->product->measurement_value: '';

                    //isset($related->vendor_product->measurementclass) ? $related->vendor_product->measurementclass: '';
                    $arraylist['order_id'] = $order->order_id;
                    $arraylist['id'] = $order->id; /*unique id of product order item table*/
                    $arraylist['status'] = Helper::$product_status[$order->status];

                    if($order->is_offer=='yes'){                           
                            $offerData =json_decode($order->offer_data);
                            if($offerData->offer_type == 'percentages'){
                               $minus_amt = (($related->vendor_product->price) * $order->offer_value) /100;
                            }
                            if($offerData->offer_type == 'amount'){
                                $minus_amt = $order->offer_value;
                            }
                            $arraylist['offer_type']=$offerData->offer_type;
                            $arraylist['offer_value']=$offerData->offer_value;
                            $arraylist['to_time']=$offerData->to_time;
                            $arraylist['from_time']=$offerData->from_time;
                            $arraylist['minus_amt']=$minus_amt;
                            $arraylist['discounted_price']=number_format( ($related->vendor_product->price) - $minus_amt  ,2,'.','');
                    }else{
                         $arraylist['offer_type']=null;
                        $arraylist['offer_value']=null;
                        $arraylist['to_time']=null;
                        $arraylist['from_time']=null;
                        $arraylist['discounted_price']=number_format($related->vendor_product->price,2,'.','');

                    }
                  // die("wqwq");

                    $listAddress[] = $arraylist;

                }
               // print_r($listAddress); die();

            } else {
                $arraylist['order_code'] = $orders->order_code;
                $arraylist['transaction_id']=($orders->transaction_id);
                $arraylist['payment_mode']= $paymentmodes[$orders->payment_mode_id];
                $arraylist['transaction_status']=Helper::$transaction_status[$orders->transaction_status];
                $arraylist['status'] = Helper::$order_status[$orders->order_status];
                $arraylist['promo_code_disc'] = $orders->coupon_amount;
                $arraylist['total_amount'] = ($orders->offer_total);
                $arraylist['coustomer'] = $orders->User->name;
                $arraylist['delivery_address'] = $orders->shipping_location->name;
                $arraylist['shipping_address'] = $orders->shipping_location->address;
                $arraylist['phone_number'] = $orders->user->phone_code."-".$orders->user->phone_number;
                //$arraylist['measurement_class'] = $related->vendor_product->measurementclass;
                //$arraylist['measurement_value'] = $related->vendor_product->product->measurement_value;
                $arraylist['lat'] = $orders->shipping_location->lat;
                $arraylist['lng'] = $orders->shipping_location->lng;
                
                if (!empty($orders->zone)) {
                    $arraylist['zone'] = $orders->zone->name;
                } else {
                    $arraylist['zone'] = "";
                }
                
                $arraylist['shopper'] = $orders->shopper->full_name;
                $arraylist['driver'] = $orders->driver->full_name;
                $arraylist['customer'] = $orders->user->full_name;
                $arraylist['sub_total'] = number_format($orders->total_amount + $orders->coupon_amount - $orders->delivery_charge,2,'.','');
                $arraylist['delivery_charge'] = $orders->delivery_charge;
                $arraylist['delivery_date'] = $orders->delivery_date;
                $arraylist['delivery_time'] = $orders->delivery_time->from_time."-".$orders->delivery_time->to_time;
                $arraylist['product_discount'] = number_format($orders->total_amount - $orders->offer_total,2,'.','');
                $arraylist['vendor'] = $orders->vendor->full_name;
                $arraylist['delivery_charge'] = $orders->delivery_charge;
                $arraylist['number_of_items'] = count($orders->ProductOrderItem);
                $listAddress[] = $arraylist;
            }
        } else {
            return $this->userNotExistResponse('No order');
        }
         
            if (!empty($listAddress)) {

                return $this->showResponse($listAddress);
            } else {
                return $this->userNotExistResponse('No order');
            }
        }else {
            return $this->userNotExistResponse('No order');
        }
    }


    public function shopperProfile(Request $request) {
        $fields = $request->all();
        $user = $this->user->where(['id' => $request->user_id])->first();
        
        if (empty($user)) {
            return $this->showResponse($data = "", $message = trans('user.check_details'));
        } else {
            return $this->showResponse($user);
        }
    }
    
    public function shopperAssignment(Request $request) {
        $lists =  $this->user->where(['id' => $request->shopper_id, 'user_type' => 'shoper'])->first();
        if ($lists) {
            $zones = $lists->toArray();
            if (!empty($zones["zone_id"])) {
                if (is_array($zones['zone_id']) && !empty($zones['zone_id'])) {
                    foreach ($zones['zone_id'] as $zone) {
                        if (!empty($zone)) {
                            $zonedata = $this->zone->where("id", "=", $zone)->first();
                            $zone_list['id'] = $zonedata['id'];
                            $zone_list['name'] = $zonedata['name'];
                            $lists_zone[] = $zone_list;
                        } else {
                            return $this->userNotExistResponse('No assignment');
                        }
                    }
                } else {
                    return $this->userNotExistResponse('No assignment');
                }
            } else {
                return $this->userNotExistResponse('No assignment');
            }
            
            if (!empty($lists_zone)) {
                return $this->showResponse($lists_zone);
            } else {
                return $this->userNotExistResponse('No assignment');
            }
        } else {
            return $this->userNotExistResponse('No assignment');
        }
    }
    
    public function shopperUpdate(Request $request) {
        $fields = $request->all();
        $user = $this->user->where(['id' => $request->user_id])->first();
        
        if (empty($user)) {
            return $this->showResponse($data = "", $message = trans('user.check_details'));
        } else {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required',
                'email' => 'required|email|unique:users,email',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator);
            } else {
                $user = $this->user->FindOrFail($request->user_id)->fill($input)->save();
                return $this->showResponse($user);
            }
        }
    }
    public function updatePrice(Request $request) {

        if ((isset($request->id) && !empty($request->id))){
            $product_details = ProductOrderItem::where('id', $request->id)->first();

            if (isset($product_details) && !empty($product_details)) {
                if (isset($request->price) && !empty($request->price)){
                    if (isset($request->offer_price) && !empty($request->offer_price)){
                        $order = $this->order->findOrFail($product_details->order_id);
                        $productData = VendorProduct::with('Product')->where('id',$product_details->vendor_product_id)->first();
                        //return $productData->product->name;
                        $shopper= $this->user->whereIn('id',[$order->shopper_id,$order->vendor_id])->select('name','id')->get();
                        $shopperVendor = $shopper->pluck('name','id');
                        $message = 'Change in price or offer';
                        $type = 'update';
                        if (isset($shopperVendor[$order->shopper_id]) || array_key_exists($order->shopper_id, $shopperVendor)) { 
                            $shopperName = $shopperVendor[$order->shopper_id];
                        }
                        if (isset($shopperVendor[$order->vendor_id]) || array_key_exists($order->vendor_id, $shopperVendor)) { 
                            $vendorName = $shopperVendor[$order->vendor_id];
                        }
                        //return $order->user;
                        $order->user->notify(new ProductUpdate($order,$productData,$shopperName,$vendorName,$request->price,$request->offer_price,$message,$type));
                    }
                    return $this->showResponse("Price update notification sent to admin");

                }else {
                    return $this->showResponse($data = "", $message = trans('user.check_details'));
                }
                
            } else {
                return $this->showResponse($data = "", $message = 'No record found');
            }
             
        } else {
                return $this->showResponse($data = "", $message = trans('user.check_details'));
        }
    }
    public function manageUpdatePrice(Request $request) {
        //print_r($request->product_id);die;
        /*here $request->product_id is vendor products table id*/
        $productName = '';
        if ((isset($request->vendor_id) && !empty($request->vendor_id)) && (isset($request->vendor_name) && !empty($request->vendor_name)) && (isset($request->shopper_id) && !empty(
            $request->shopper_id)) && (isset($request->product_id) && !empty($request->product_id)) && (isset($request->offer_price) && !empty($request->offer_price)) && (isset($request->price) && !empty($request->price))){
                $product_details =  
                DB::table('vendor_products AS vp')
                ->join('products AS p','vp.product_id', '=', 'p.id')
                ->join('product_translations AS pt','pt.product_id', '=', 'p.id')
                ->where('vp.id',$request->product_id)
                ->where('pt.locale',App::getLocale())
                ->select('pt.name')->first();
                //print_r($product_details);die;
                //$product_details = Product::where('id', $request->product_id)->select('sku_code')->first();
                if(isset($product_details) && !empty($product_details)){
                    $productName =  $product_details->name;
                }

                $shopper= User::whereIn('id',[$request->shopper_id,$request->vendor_id])->select('name','id')->get();

                $shopperVendor = $shopper->pluck('name','id');
                $message = 'Change in price or offer';
                $type = 'update';
                $vendorId = $request->vendor_id;
                if (isset($shopperVendor[$request->shopper_id]) || array_key_exists($request->shopper_id, $shopperVendor)) { 
                    $shopperName = $shopperVendor[$request->shopper_id];
                }
                if (isset($shopperVendor[$request->vendor_id]) || array_key_exists($request->vendor_id, $shopperVendor)) { 
                    $vendorName = $shopperVendor[$request->vendor_id];
                }

                $toUser = User::find(1);
                // send notification using the "user" model, when the user receives new message
                $toUser->notify(new ManageProductUpdate($shopperName,$vendorId,$vendorName,$productName,$request->product_id,$request->price,$request->offer_price,$message,$type));     

                return $this->showResponse(trans('order.price_update_notify'));
             
        } else {
                return $this->showResponse($data = "", $message = trans('user.check_details'));
        }
    }
    public function ManageOutStock(Request $request) {
        $productName = '';
        if ((isset($request->vendor_id) && !empty($request->vendor_id)) && 
            (isset($request->vendor_name) && !empty($request->vendor_name)) && 
            (isset($request->shopper_id) && !empty($request->shopper_id)) && 
            (isset($request->product_id) && !empty($request->product_id))){
                $product_details =  DB::table('products AS p')
                            ->join('product_translations AS pt','pt.product_id', '=', 'p.id')
                            ->where('p.id',$request->product_id)
                            ->where('pt.locale','en')
                            ->select('pt.name')
                            ->first();
                            //return  $product_details ;
                
                if(isset($product_details) && !empty($product_details)){
                    $productName =  $product_details->name;
                    //return $productName;
                }
                $shopper= User::whereIn('id',[$request->shopper_id,$request->vendor_id])->select('name','id')->get();

                $shopperVendor = $shopper->pluck('name','id');
                $message = 'Product out of stock';
                $type = 'out of stock';
                $vendorId = $request->vendor_id;
                $vendorProductData = VendorProduct::where('user_id',$request->vendor_id)->where('product_id',$request->product_id)->first();
                if(isset($vendorProductData) && !empty($vendorProductData)){
                    $productId =  $vendorProductData->id;
                }
                if (isset($shopperVendor[$request->shopper_id]) || array_key_exists($request->shopper_id, $shopperVendor)) { 
                    $shopperName = $shopperVendor[$request->shopper_id];
                }
                if (isset($shopperVendor[$request->vendor_id]) || array_key_exists($request->vendor_id, $shopperVendor)) { 
                    $vendorName = $shopperVendor[$request->vendor_id];
                }

                $toUser = User::find(1);
                // send notification using the "user" model, when the user receives new message
                $toUser->notify(new ManageOutStock($shopperName,$vendorId,$vendorName,$productName,$request->product_id,$message,$type));     

                return $this->showResponse(trans('order.outstock_product_notify'));
             
        } else {
                return $this->showResponse($data = "", $message = trans('user.check_details'));
        }
    }
    
    public function productStatus(Request $request) {
         if ((isset($request->id) && !empty($request->id))){
            $product_details = ProductOrderItem::where('id', $request->id)->first();

            if (isset($product_details) && !empty($product_details)) {
                  
                        $order = $this->order->findOrFail($product_details->order_id);
                        $shopper= $this->user->whereIn('id',[$order->shopper_id,$order->vendor_id])->select('name','id')->get();

                        $shopperVendor = $shopper->pluck('name','id');
                        $message = 'Product out of stock';
                        $type = 'out of stock';
                        if (isset($shopperVendor[$order->shopper_id]) || array_key_exists($order->shopper_id, $shopperVendor)) { 
                            $shopperName = $shopperVendor[$order->shopper_id];
                        }
                        if (isset($shopperVendor[$order->vendor_id]) || array_key_exists($order->vendor_id, $shopperVendor)) { 
                            $vendorName = $shopperVendor[$order->vendor_id];
                        }
                        //return $order->user;
                        $order->user->notify(new ProductOutStockStatus($order,$shopperName,$vendorName,$message,$type));
                    
                    return $this->showResponse(trans('order.outstock_product_notify'));

                
            } else {
                return $this->showResponse($data = "", $message = trans('user.no_record'));
            }
             
        } else {
                return $this->showResponse($data = "", $message = trans('user.check_details'));
        }
    }
    public function newProduct(Request $request) {
         if ((isset($request->vendor_id) && !empty($request->vendor_id)) && (isset($request->shopper_id) && !empty($request->shopper_id)) && (isset($request->price) && !empty(
            $request->price)) && (isset($request->offer_price) && !empty($request->offer_price)) && (isset($request->product_name) && !empty($request->product_name)) && (isset($request->measurement) && !empty($request->measurement))){
                  
                        $shopper= $this->user->whereIn('id',[$request->shopper_id,$request->vendor_id])->select('name','id')->get();

                        $shopperVendor = $shopper->pluck('name','id');
                        $message = 'New Product';
                        $type = 'new';
                        $offerPrice =$request->offer_price;
                        $price = $request->price;
                        $measurement = $request->measurement;
                        if (isset($request->shopper_id) || array_key_exists($request->shopper_id, $shopperVendor)) { 
                            $shopperName = $shopperVendor[$request->shopper_id];
                        }
                        if (isset($request->vendor_id) || array_key_exists($request->vendor_id, $shopperVendor)) { 
                            $vendorName = $shopperVendor[$request->vendor_id];
                        }
                        //return $order->user;
                        $toUser = User::find(1);
                        // send notification using the "user" model, when the user receives new message
                        $toUser->notify(new NewProduct($shopperName,$vendorName,$request->product_name,$price,$offerPrice,$measurement,$message,$type));
                    
                    return $this->showResponse(trans('order.new_product_notify'));

        } else {
                return $this->showResponse($data = "", $message = trans('user.check_details'));
        }
    }
    public function categoryList(Request $request) {
        if ((isset($request->language) && !empty($request->language))){
            /*$category = DB::table('category_translations')
                        ->join('categories','categories.id', '=', 'category_translations.category_id')
                        ->select('categories.id','category_translations.name')
                        ->where('category_translations.locale',$request->language)
                        ->orderBy('categories.sort_no','ASC')
                        ->get()->toArray();*/

           $category = $this->category->where(['locale' => $request->language])->select('category_id AS id','name')->with('category')->get()->toArray();
            //return $category;
            if(!empty($category)){
                return $this->showResponse($data = $category, $message = 'category details');
            }else{
                return $this->userNotExistResponse(trans('user.no_record'));
            }
        }else{
            return $this->userNotExistResponse('Please select language');
        }
        
    }

    public function categoryProductList(Request $request) {
        if ((isset($request->language) && !empty($request->language)) && (isset($request->zone_id) && !empty($request->zone_id))){
            $vendorData = DB::table('users')->where('user_type','vendor')->whereRaw("find_in_set($request->zone_id,zone_id)")->select('id')->first();
            if((isset($vendorData) && !empty($vendorData))){
                $vendor_id = $vendorData->id;
                //return $vendor_id;
                 $current_time = date("Y-m-d");
                $query = DB::table('vendor_products AS vp')
                            ->join('products AS p','vp.product_id', '=', 'p.id')
                            ->join('measurement_class_translations AS mt','p.measurement_class', '=', 'mt.measurement_class_id')
                            ->join('product_translations AS pt','pt.product_id', '=', 'p.id')
                            ->join('images','p.id', '=', 'images.image_id')
                            ->leftJoin('offers AS off', function($join)
                             {
                                $join->on('off.id', '=', 'vp.offer_id')
                                ->whereRaw('from_time <= CAST( "'.date("Y-m-d").'" AS DATE ) and to_time >= CAST( "'.date("Y-m-d").'" AS DATE ) ');
                                
                             })
                            ->where('vp.user_id',$vendorData->id)
                            ->where('vp.deleted_at',NULL)
                            ->where('pt.locale',$request->language);

                    if (isset($request->category_id) && !empty($request->category_id)) {
                         $query->whereRaw("find_in_set($request->category_id,p.category_id)");
                    }
        
                    $base_url = url('/');
                    $products = $query->select(DB::raw("CONCAT('".$base_url."/storage/app/public/upload/', images.name) AS image_name"),'vp.product_id','vp.price','vp.qty','vp.offer_id','p.category_id','mt.name AS measurement_class','p.measurement_value','pt.name','vp.id','off.offer_type','off.offer_value','off.to_time','off.from_time',DB::raw("
                            CASE WHEN (off.offer_type = 'percentages') THEN 
                                vp.price - ((vp.price * off.offer_value) / 100)
                            ELSE
                                (vp.price - off.offer_value)
                            END AS discounted_price
                        
                    "
                    ))->groupBy('p.id')->get();


                    $productFinal = [];
                    foreach ($products as $pkey => $pvalue) {
                        if($pvalue->offer_type == null){
                            $pvalue->discounted_price = $pvalue->price;
                        }
                        $productFinal[] = $pvalue;

                       
                    }
                    //print_r($productFinal);die;
                    //return $products;
                    //dd($products);
                   
               
                if(!empty($products)){
                    return $this->showResponse($data = $productFinal, $message = 'product details');
                }else{
                    return $this->userNotExistResponse(trans('user.no_record'));
                }
            }else{
                return $this->userNotExistResponse(trans('user.invalid_zone'));
            }
        }else{
            return $this->userNotExistResponse(trans('user.check_details'));
        }
        
    }

   
    /*api to change status of individual product of specific order */
    public function updateUnavailability(Request $request) {
        $fields = $request->all();
            if ((isset($request->id) && !empty($request->id))  && (isset($request->status) && !empty($request->status))){
                $caseInsensitiveStatus=strcasecmp($request->status, 'u'); 
                $collectedStatus=strcasecmp($request->status, 'o');   
                if ($caseInsensitiveStatus == 0 || $collectedStatus == 0){
                   
                    $product_details = ProductOrderItem::where('id', $request->id)->first();

                        if (isset($product_details) && !empty($product_details)) {
                            if($product_details->update(['status'=>$request->status])){
                                $order = $this->order->findOrFail($product_details->order_id);
                                $productData = VendorProduct::with('Product')->where('id',$product_details->vendor_product_id)->first();
                                $shopper= $this->user->where('id',$order->shopper_id)->select('name')->first();
                                //return $shopper;
                                $shopperName = $shopper->name;
                                $message = trans('order.unavailable_product');
                                $type = 'unavailable';
                                if($caseInsensitiveStatus == 0){
                                    $order->user->notify(new ProductStatus($order,$productData,$shopperName,$message,$type)); 
                                }
                                return $this->showResponse(trans('order.product_status_updated'));
                            }else{
                                return $this->userNotExistResponse(trans('order.not_updated'));
                            }

                        } else {
                           return $this->userNotExistResponse(trans('user.no_record'));
                        }
                }else{
                    return $this->userNotExistResponse(trans('user.check_details'));
                }
        }else{
             return $this->userNotExistResponse(trans('user.check_details'));
        }
    }

    public function updateOrderStatus(Request $request) {

        $fields = $request->all();
        $var1 = 0;
        if($request->order_status == 'o'){$var1 = 1;}
        if($request->order_status == 'O'){$var1 = 1;}
        if ((isset($request->order_id) && !empty($request->order_id)) && (isset($request->order_status) && !empty($request->order_status)) && $var1 == 1){
            $orderDetails = ProductOrder::where('id',$request->order_id)->first();

                //return $orderDetails->order_code;
            if (isset($orderDetails) && !empty($orderDetails)) {
                $orderDetails->order_status =$request->order_status;
                if($orderDetails->save()){
                    $driver_id = $orderDetails->driver_id;
                    $driverData = User::whereIn('id', [$driver_id])->select('id','device_type','device_token')->get();
                    $driver_id_array = collect($driverData)->where('id', $driver_id)->pluck('device_token');
                    $driver_device_type_array = $driverData->where('id', $driver_id)->pluck('device_type');
                    $driver_device_type=$driver_device_type_array[0];

                    /*push notification to customer*/
                    $user_id = $orderDetails->user_id;
                    $userData = User::where('id', '=',  $user_id )->select('device_type','device_token','name')->get();
                    /*admin notification*/
                    $sendor = $userData[0]->name;
                    $orderDetails->user->notify(new OrderStatus($orderDetails,$sendor));
                    $user_id_array = collect($userData)->pluck('device_token');
                    $dataArray = [];
                    $dataArray['type'] = 'Order';
                    $dataArray['product_type'] = 'Collected';
                    $dataArray['title'] = trans('order.order_collected_title');
                    $dataArray['body'] = trans('order.order_collected').$orderDetails->order_code;
                    $device_type_array = collect($userData)->pluck('device_type');
                    $device_type = $device_type_array[0];
                    
                     //driver notifiction
                    Helper::sendNotification($driver_id_array ,$dataArray, $driver_device_type);
                    Helper::sendNotification($user_id_array ,$dataArray, $device_type);
                    //return $orderDetails;
                   if(ProductOrderItem::where('order_id', $request->order_id)->where('status','!=','U')->update(['status'=>$request->order_status])){
                        return $this->showResponse('Order status update');
                    }else{
                        return $this->userNotExistResponse(trans('order.not_updated_item'));

                    }
                     
                    
                     //$orderDetails->user->notify(new OrderStatus($orderDetails,$sendor));
                   
                   

                    return $this->showResponse('Order status update');
                }else{

                    return $this->userNotExistResponse(trans('user.not_updated'));
                }

            }else{
                return $this->userNotExistResponse(trans('user.no_record'));
            }  
            
        }else{
            return $this->userNotExistResponse(trans('user.check_details'));
        }
    }

     public function getVendorDetail(Request $request) {
        if ((isset($request->zone_id) && !empty($request->zone_id))){
            $vendorData = DB::table('users')->where('user_type','vendor')->whereRaw("find_in_set($request->zone_id,zone_id)")->select('id','name')->first();
            if((isset($vendorData) && !empty($vendorData))){
                $data['vendor_id'] = $vendorData->id;
                $data['vendor_name'] = $vendorData->name;
                    return $this->showResponse($data , $message = 'Vendor details');
                }else{
                    return $this->userNotExistResponse(trans('user.no_record'));
                }
            }else{
                return $this->userNotExistResponse(trans('user.invalid_zone'));
            }
       
        
    }

      public function userTokenUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id'=>'required',
            'device_token'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }
      
            $user = User::find($request->user_id);

            if(isset($user)){
                    $user->device_token = $request->device_token;
                    $user->save();
                    $response = [
                        'error'=>false,
                        'code' => 0,
                        'message'=>trans('site.token_update'),
                    ];
                    return response()->json($response, 200);
            }else{
                 return $this->userNotExistResponse(trans('user.no_record'));
            }
        
      }

    public function getwalletHistories(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id'=>'required',
        ]);
        $user_walletdata =[];
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }else{
            $user_wallet = $this->user_wallet->where(['user_id' => $request->user_id])->paginate(100)->toArray();
        if(!empty($user_wallet)){
            $wamount = Helper::getUpdatedWalletData($request->user_id);

            $user_walletdata['error']  = false;
            $user_walletdata['code']  = 0;
            $user_walletdata['wallet_amount']  = $wamount['wallet_amount'];
            $user_walletdata['data']  = $user_wallet['data'];
            $user_walletdata['current_page']  = $user_wallet['current_page'];
            $user_walletdata['first_page_url']  = $user_wallet['first_page_url'];
            $user_walletdata['from']  = $user_wallet['from'];
            $user_walletdata['last_page']  = $user_wallet['last_page'];
            $user_walletdata['last_page_url']  = $user_wallet['last_page_url'];
            $user_walletdata['next_page_url']  = $user_wallet['next_page_url'];
            $user_walletdata['per_page']  = $user_wallet['per_page'];
            $user_walletdata['prev_page_url']  = $user_wallet['prev_page_url'];
            $user_walletdata['to']  = $user_wallet['to'];
            $user_walletdata['total']  = $user_wallet['total'];

               return response()->json($user_walletdata);
                // return $this->showResponse($data = $user_walletdata, $message = 'Wallet Histories');
            }else{
                return $this->userNotExistResponse(trans('user.no_record'));
          }
        }
  }



    public function updateWallet(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id'=>'required',
        ]);
        if ($validator->fails()) { 
            return $this->validationErrorResponse($validator);
        }else{
            $customer_id = $request->user_id;
            $transaction_type = $request->transaction_type;
            $transaction_id = $request->transaction_id;
            $type = $request->type;
            $amount = $request->amount;
            $description = $request->description;
            $json_data = !empty($request->json_data) ? $request->json_data: "";
            


            Helper::updateCustomerWallet($customer_id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data);
            return $this->showResponse($data = "", $message = 'Wallet updated');
        }
    }

    public function testSMS(){
        //$otp = rand(100000,999999);
        $otp1 = rand(100,999);
        $otp2 = rand(100,999);
        $otp = $otp1.'-'.$otp2;

        $phone_number = '919024162637';
        $client = new Client();
        $authkey = env('AUTHKEY');
        $phone_number = $phone_number;
        $senderid = env('SENDERID');
        $hash = env('SMSHASH');
        $tmp_id = '1207162028126071690';
        $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar $hash");
        $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=".$tmp_id);
        $statusCode = $response->getStatusCode();
        echo $statusCode;
    }


    //updateDeviceToken
    public function updateDeviceToken(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'device_token'=>'required',
                'device_id'=>'required',
                'device_type'=> 'required|in:A,I',
            ]);
            if ($validator->fails()) { 
                return $this->validationErrorResponse($validator);
            }
            //logged in user
            $user = Auth::guard('api')->user();
            $user->device_token = $request->device_token;
            $user->device_id = $request->device_id;
            $user->device_type = $request->device_type;
            $user->save();

            $this->response->user = new UserResource($user);
            return ResponseBuilder::success($this->response, "Device updated successfully", $this->successStatus);

        }catch(\Exception $e){
            Log::error($e);
            return ResponseBuilder::error('Something went wrong', $this->errorStatus);

        }
    }

}
