<?php

namespace App\Http\Controllers;

use App\Membership;
use App\User;
use App\Zone;
use App\UserMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;

use App\Http\Controllers\Controller;
use App\Scopes\StatusScope;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;


use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

use Razorpay\Api\Api;
use Session;
use Redirect;


class MembershipController extends Controller
{    
    use RestControllerTrait,ResponceTrait;
    private $user;
    private $membership;
    private $zone;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user,Membership $membership,Zone $zone, UserMembership $usermembership)
    {
        parent::__construct();
        $this->user = $user;
        $this->membership = $membership;
        $this->usermembership = $usermembership;
        $this->zone=$zone;
    }
 
   public function index(){
    $membership = $this->membership->get();

 if(Auth::user()){
    $selected_membership = Auth::user()->membership;
  }else{
 $selected_membership = 0;
  }
    return view('pages.addmembership',compact('membership'));
  }
  public function addmembership(Request $request){
      $api = new Api(env('razor_key'), env('razor_secret'));

        $validator = Validator::make($request->all(), [
            'razorpay_payment_id' => 'required',
            'totalAmount' => 'required',
            'membership_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }else{
            $user_data = $this->user->where('id',Auth::user()->id)->first();
            $membership_data = $this->membership->where('id',$request->membership_id)->first();

              $duration = $membership_data->duration;
              $duration = date('Y-m-d H:i:s',strtotime("+".$duration."s"));
          
              if(empty($user_data->membership) && empty($user_data->membership_to)){
                $user_data->membership = $request->membership_id;
                $user_data->membership_to = $duration ;
                $user_data->update();

              }else{
                if($user_data->membership_to < date('Y-m-d H:i:s')){
                  $user_data->membership = $request->membership_id;
                  $user_data->membership_to = $duration ;
                  $user_data->update();
                }else{
                  /*$response = [
                      'error'=>true,
                      'code' => 1,
                      'message'=>"Sorry!! You already Have a membership active.",
                  ];
                  return response()->json($response, 201);*/
                }
            }

            try {
                $input['user_id'] = Auth::user()->id;
                $input['membership_id'] = $request->membership_id;
                $input['start_date'] = date('Y-m-d H:i:s');
                $input['end_date'] = $duration;


                $time = explode(' ',$membership_data->duration);
                $time = trim($time[0]);

                $amount =  ( $membership_data->price / $time );
                $transaction_type = "CREDIT";
                $type ="Membership Recharge";
                $transaction_id = time().rand(100000,999999);
                $description ="Your First Membership Wallet Recharge";
                $json_data = json_encode(['membership_id'=>$membership_data->id]);

                // comment for stop cashback on membership by Abhishek Bhatt//
                //$user_wallet = Helper::updateCustomerWallet(Auth::user()->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data);
                $user_wallet = [];

                if(!$user_wallet){
                  $response = [
                      'error'=>true,
                      'code' => 1,
                      'message'=>"Sorry!! there is a issue in wallet recharge.",
                  ];
                  return response()->json($response, 201);
                }/*else{
                  $user_data->wallet_amount = $user_data->wallet_amount + $amount;
                  $user_data->update();
                }*/
                $data = $this->usermembership->create($input);


                $response = [
                      'error'=>false,
                      'code' => 0,
                      'message'=>"Membership purchase successful.",
                  ];
                  return response()->json($response, 200);
              } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
            }
          }

    }


  public function check_membership(Request $request){
    $users = $this->user->where('membership_to','<=',date('Y-m-d H:i:s'))->get();
    foreach($users as $ukey=>$uval){
    $membership_data = $this->membership->where('id',$uval->membership)->first();
    
      $usermembership = $this->usermembership->where('user_id',$uval->id)->where('end_date','>',date('Y-m-d H:i:s'))->first();
      $user =   $this->user->findorfail($uval->id);
      $user->membership_to = $usermembership->end_date;
      $user->membership = $usermembership->membership_id;
      $user->update();
    }
    $data = "users updated successfully";
      return $this->showResponse($data);
  }


  public function getmembership(){

 $membership_data = $this->membership->inRandomOrder()->get();
    if(Auth::guard('api')->user()){
           foreach($membership_data as $mmk=>$mmv){
             $mmv->is_active=false;
            $userdata =  $this->user->where('id',Auth::guard('api')->user()->id)->first();
            if($mmv->id==$userdata->membership){
                $membership_to = date('Y-m-d',strtotime(Auth::guard('api')->user()->membership_to));
                if($membership_to>=date('Y-m-d')){
                    $mmv->is_active=true;
                }
            }
           }}
return $this->showResponse($membership_data);

  }
        


        public function wallet_recharge(Request $request){

  $users = $this->user->where('membership_to','>=',date('Y-m-d H:i:s'))->get();

   foreach($users as $ukey=>$uval){
    // comment for stop cashback on membership by Abhishek Bhatt//
//$usermembership = $this->usermembership->where('user_id',$uval->id)->where('end_date','>',date('Y-m-d H:i:s'))->orderBy('id','DESC')->first();
    $usermembership = [];
      if(!empty($usermembership)){

 $membership_data = $this->membership->where('id',$usermembership->membership_id)->first();
      $time = explode(' ',$membership_data->duration);
                $time = trim($time[0]);
      $amount =  ( $membership_data->price / $time );

$curr_date = strtotime($valuser['start_date']);

for($i=0;$i<=$time;$i++){
$date_after = date("Y-m-d", strtotime("+".$i." month", $curr_date));
 if($date_after==date('Y-m-d')){
         $user_id =  $user_data->id ;
         $user_data->wallet_amount = $user_data->wallet_amount + $amount;
         $user_data->update();
      //   return $this->showResponse(['data'=>$user_data,'message'=>"congratulation !!! Your Wallet recharge Successfully Done."]);

$user_id_array1 = User::where('id',$user_id)->select('id','device_type','device_token','name')->get();
$userData = User::where('id', '=', $user_id )->select('device_token')->get();
$user_id_array = collect($userData)->pluck('device_token');
//echo "<pre>"; print_r($shopperArray); die;
$dataArray = [];
$dataArray['type'] = 'Order';
$dataArray['product_type'] = 'New';
$dataArray['title'] = 'Wallet Recharge';
$dataArray['body'] = "Your Wallet is recharge for membership ";
$device_type = $user_id_array1[0]->device_type;

Helper::sendNotification($user_id_array ,$dataArray, $device_type);






 }else{   continue; }
}}}

  $data = "user's Wallets updated successfully";
      return $this->showResponse($data);
}






}
