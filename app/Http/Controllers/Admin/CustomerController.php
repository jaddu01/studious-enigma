<?php

namespace App\Http\Controllers\Admin;

use App\CountryPhoneCode;
use App\Zone;
use App\AccessLevel;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\User;
use App\UserWallet;
use App\Membership;
use App\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use DB;
use Auth;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    protected $user;
    protected $method;
    protected $zone;
    function __construct(Request $request, User $user, Zone $zone,Membership $membership,UserWallet $user_wallet)
    {
        parent::__construct();
        $this->user=$user;
        $this->zone=$zone;
        $this->membership=$membership;
        $this->user_wallet=$user_wallet;
        $this->method=$request->method();
        //DB::enableQueryLog();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        $type = 'all';
        if (isset($request->type)) {
            $type = $request->type;
        }

        return view('admin/pages/customer/index')->with('type',$type);
    }

    public function create(){
		//echo $this->user->can('create', User::class); die;
		 //die('asfd');
        if ($this->user->can('create', User::class)) {
            return abort(403,'not able to access');
        }
        $countryPhoneCode = CountryPhoneCode::pluck('phonecode','phonecode');
       
        $validator = JsValidatorFacade::make($this->user->rules('POST'));

        $zone=$this->zone->select('id')->get('name');
               
        $already_taken_vender_zones = array_collapse($this->user->select('zone_id')->where('user_type','=','vendor')->pluck('zone_id')->toArray());


        $accessLevels = AccessLevel::listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/user/add')->with('zone',$zone)->with('validator',$validator)->with('accessLevels',$accessLevels)->with('already_taken_vender_zones',$already_taken_vender_zones)->with('countryPhoneCode',$countryPhoneCode);
    }

    public function store(Request $request)
    {
        if ($this->user->can('create', User::class)) {
            return abort(403,'not able to access');
        }
        if(!$request->has('password')){
            $request->request->add(['password'=>'123456']);
            $request->request->add(['password_confirmation'=>'123456']);
        }
        $input = $request->all();


        $validator = Validator::make($request->all(),$this->user->rules($this->method),$this->user->messages($this->method));

        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            if($request->ajax()){
                return response()->json([
                    'status' => true,
                    'message' => $validator->errors()->first(),
                ],200);
            }else{
                return redirect('admin/user/create')->withErrors($validator)->withInput();
            }

        }else{

            try {
            $input['password'] = bcrypt($input['password']);

                 $this->user->create($input);

                Session::flash('success','User create successful');
                $message = 'User create successful';
                $type='success';
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                $message = $e->getMessage();
                $type='error';
            }
            if($request->ajax()){
                return response()->json([
                    'status' => true,
                    'message' => $message,
                    'type' => $type,
                ],200);
            }else{
                return back();
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($this->user->can('view', User::class)) {
            return abort(403,'not able to access');
        }
        $user=$this->user->findOrFail($id);

        return view('admin/pages/customer/show')->with('user',$user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        $phone_code = '';
        $user=$this->user->findOrFail($id);
        if($user){
              $phone_code = str_replace("+", "", $user->phone_code);
        }
        
      
        $countryPhoneCode = CountryPhoneCode::pluck('phonecode','phonecode');

        $zone=$this->zone->select('id')->get('name');

        $already_taken_vender_zones = array_collapse($this->user->select('zone_id')->where('user_type','=','vendor')->where('id','!=',$id)->pluck('zone_id')->toArray());

        $accessLevels = AccessLevel::listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/customer/edit')->with('zone',$zone)->with('user',$user)->with('already_taken_vender_zones',$already_taken_vender_zones)->with('countryPhoneCode',$countryPhoneCode)->with('accessLevels',$accessLevels)->with('phone_code', $phone_code);
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }

        $input = $request->all();
        // dd($this->method);
        
        // $validator = Validator::make(
        //     $request->all(), $this->user->rules($this->method,$id),
        // $this->user->messages($this->method));
        $validator = Validator::make($request->all(),[
            'user_type' => 'sometimes|required',
            'image' => 'image|mimes:jpg,png,jpeg',
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,'.$id.',id,deleted_at,NULL',
            'phone_number' => 'sometimes|numeric|required|digits:10|unique:users,phone_number,'.$id.',id,deleted_at,NULL',
            'password' => 'sometimes|required|string|min:6|confirmed',
            'address' => 'sometimes|required',
            'phone_code' => 'sometimes|required',
        ]
        );

        if ($validator->fails()) {
            
            // return redirect()->route('customer.index')
            //     ->withErrors($validator)
            //     ->withInput();
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
           $user= $this->user->FindOrFail($id)->fill($input)->save();
            return redirect()->route('customer.index')->with('success','User update successful');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->user->can('delete', User::class)) {
            return abort(403,'not able to access');
        }
       /*print_r((new Helper())->delete_cat($this->user->all(),$id,'',''));*/
       $cat_id=Helper::delete_cat($this->user->all(),$id,'','');

        $flight = $this->user->whereIn('id',$cat_id)->delete();
        if($flight){
            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ],400);
        }
    }


    public function anyData(Request $request)
    {  
        $auth_zone_ids = Auth::guard('admin')->user()->zone_id;
        $auth_zone_ids = explode(',',$auth_zone_ids);
        $user =$this->user->where('user_type', '=', 'user');
        // if(isset($auth_zone_ids) && !empty($auth_zone_ids)) {
        //     for ($i=0; $i < count($auth_zone_ids); $i++) { 
        //         $user->where('zone_id', 'like', '%' . $auth_zone_ids[$i] . '%');
        //         if($i>0) {
        //             $user->orWhere('zone_id', 'like', '%' . $auth_zone_ids[$i] . '%');
        //         }
        //     }
        // }
        if($request->has('cust_type') and $request->cust_type=='today'){
              $user->whereDate('created_at','=',date('Y-m-d'));
        }
        $user->get();
        
        return Datatables::of($user)
            ->addColumn('referred_by',function ($user){
                if(!empty($user->referred_by)){
               $referred_by  = $this->user->where('id',$user->referred_by)->first();
                return $referred_by->name."( ".$user->referred_by." )";
               }else{
                 return '--';
               }
            })
              ->addColumn('no_of_order',function ($user){
                return $user->totalOrder();
            })
            ->editColumn('phone_number',function ($user){
                return $user->phone_code.'-'.$user->phone_number;
            })
            /*->editColumn('membership_name',function ($user){                
                if(isset($user->membership) && !empty($user->membership)){ 
                $membership = $this->membership->find($user->membership);
                if(!empty($membership)){ return $membership->name;}else{  return "--"; }
                }else{  return "--"; }
            })*/
            ->editColumn('membership_name',function ($user){                
                if(isset($user->membership) && !empty($user->membership)){
                 $date_now = date('Y-m-d');
                 $date2    = date('Y-m-d',strtotime($user->membership_to)); 
                    if($date_now <= $date2) {
                        return 'Active';
                    } else {
                        return 'Expired';
                    }
                }else{  return "--"; }
            })
            ->editColumn('membership_to',function ($user){
                if(!empty($user->membership_to)){ 
               return date('d-M-Y',strtotime($user->membership_to));
                }else{  return "--"; }
            })
            ->addColumn('delivered_order',function ($user){
                return $user->deliveredOrder();
            })
            ->addColumn('total_amount',function ($user){
                return $user->totalAmount();
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($user){
                return '<a href="'.route("customer.edit",$user->id).'" class="btn btn-success btn-xs">Edit</a><a href="'.route("customer.show",$user->id).'" class="btn btn-info btn-xs">Details</a><a href="'.route("customer.mapview",$user->id).'" class="btn btn-info btn-xs">Address map</a><input class="data-toggle-coustom " data-size="mini"  data-toggle="toggle" type="checkbox" user-id="'.$user->id.'" '.(($user->status==1) ? "checked" : "") . ' value="'.$user->status.'" ><a href="'.url("admin/order").'?phone_number='.$user->phone.'" class="btn btn-info btn-xs">Order</a><a href="'.url("admin/customer/wallet").'/'.$user->id.'" class="btn btn-info btn-xs">Wallet History</a><a href="'.url("admin/customer/darbaar-coin").'/'.$user->id.'" class="btn btn-info btn-xs">Darbaar Coin History</a><a href="'.url("admin/customer/viewcart").'/'.$user->id.'" class="btn btn-info btn-xs">View Cart</a>';
                //<button type="button" onclick="deleteRow('.$user->id.')" class="btn btn-danger btn-xs">Delete</button>
            })
            ->orderColumn('id','desc')
            ->rawColumns(['image','action'])
            ->make(true);

    }
    public function viewcart($userId='')
    {
       
        $data= Cart::where(['user_id'=>$userId])           
        ->with(['vendorProduct','vendorProduct.User'])
        ->whereHas('vendorProduct.Product',function($q){ $q->where('status','1');  })
        ->with(['vendorProduct.Product.image'])
        ->with(['vendorProduct.Product.MeasurementClass']);
        $dataAll = $data;
        $data = $data->paginate('10');
       
        
        
        //$response = [
          //  'error'=>false,
            //'code' => 0,
           // 'cart_list' => $data,
            //'cart_count' => count($data),
            //'message'=>trans('site.success'), 
        //];

        //dd($data);
        //$data = DB::table('carts')->where('user_id',$userId)->paginate('10');
        return view('admin.pages.customer.viewcart', compact('data'));
    }
  
   public function wallethistory($id){
          if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        $user_id = $id;

      return view('admin/pages/customer/wallethistory',compact('user_id'));

    }

    public function wallethistorydata(Request $request){
 
      $wallet_histories = $this->user_wallet->where('user_id',$request->user_id)->where('wallet_type','amount')->orderBy('created_at','DESC')->get();
       return Datatables::of($wallet_histories)
              ->addColumn('id',function ($wallet_histories){
                return $wallet_histories->id;
             })->addColumn('created_at',function ($wallet_histories){
                return date('d/m/Y', strtotime($wallet_histories->created_at));
             })
              ->editColumn('customer_id',function($wallet_histories){
               $cust_data = $this->user->where('id',$wallet_histories->user_id)->first();
               if(!empty($cust_data)){
                return $cust_data->name;
               }
                return "--";
              })
            ->rawColumns(['id'])
            ->make(true);

    }

    public function darbaarCoinHistory($id){
          if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        $user_id = $id;

      return view('admin/pages/customer/darbaarCoinHistory',compact('user_id'));

    }

    public function darbaarCoinHistoryData(Request $request){
 
      $wallet_histories = $this->user_wallet->where('user_id',$request->user_id)->where('wallet_type','coin')->orderBy('created_at','DESC')->get();
       return Datatables::of($wallet_histories)
              ->addColumn('id',function ($wallet_histories){
                return $wallet_histories->id;
             })->addColumn('created_at',function ($wallet_histories){
                return date('d/m/Y', strtotime($wallet_histories->created_at));
             })
              ->editColumn('customer_id',function($wallet_histories){
               $cust_data = $this->user->where('id',$wallet_histories->user_id)->first();
               if(!empty($cust_data)){
                return $cust_data->name;
               }
                return "--";
              })
            ->rawColumns(['id'])
            ->make(true);

    }

    public function changeStatus(Request $request){
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        if($request->status==1){
            $status='0';
        }else{
            $status='1';
        }

        $user= $this->user->findOrFail($request->id)->update(['status'=>$status]);
        if($status == 0){
            $userData= $this->user->findOrFail($request->id);
            $user_id_array = [0=>$userData->device_token];
            $user_device_type = $userData->device_type;
            $dataArray = [];
            $dataArray['type'] = 'Deactivated';
            $dataArray['title'] = trans('user.invalid_user');
            $dataArray['body'] = trans('user.invalid_user');
            Helper::sendNotification($user_id_array ,$dataArray, $user_device_type);
        }
        if($request->ajax()){
            if($user){
                return response()->json([
                    'status' => true,
                    'message' => 'successfully updated'
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'some thing is wrong'
                ],400);
            }
        }
    }

    public function getUserByPhone(Request $request){
        $request->request->remove('_token');
        $user = $this->user->select('*')->with(['deliveryLocation']);
        foreach ($request->all() as $key=>$item){
            $user->where([$key=>$item]);
        }
        $user = $user->first();
        if ($user){
            $user->deliveryLocation = $user->deliveryLocation->keyBy('id');
        }
        if($user){
            return response()->json([
                'status' => true,
                'message' => 'successfully',
                'data'=>$user
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no record found'
            ],400);
        }
    }
    public function mapview($id){
       

        $zones = DeliveryLocation::where('user_id', $id)->select('lat', 'lng', 'name', 'id', 'address')->get();
        //return $zones;
        return view('admin/pages/customer/view-map')->with('zones', $zones);
      
    }
    /*heatmap of all addresses of customsers*/
     public function customerAddressHeatmap()
      {
        $heatMapArray = [];
        $usersIdArray = [];
        $usersList = $this->user->where('user_type','user')->select(['id'])->get();
        if(isset($usersList)){
             $usersIdArray = $usersList->pluck('id','id');
        }
        $zones = DeliveryLocation::whereIn('user_id',$usersIdArray)->select('lat', 'lng', 'name', 'user_id', 'address')->get();
       
        $i=1;
        foreach ($zones as $key => $value) {
          if( $value->lat != '' && $value->lng != '' && is_numeric($value->lat)  && is_numeric($value->lng)  ){
            $heatMapArray[$i]['lat'] = $value->lat;
            $heatMapArray[$i]['lng'] = $value->lng;
            $heatMapArray[$i]['user_id'] = $value->user_id;
            $heatMapArray[$i]['address'] = $value->address;
          }
          $i++;
        }
        //reindexed array
        $heatMapArray = array_combine(range(1, count($heatMapArray)), array_values($heatMapArray));
        //return  $heatMapArray;
        return view('admin/pages/tracking/customerAddessHeatmap')->with('heatMapArray',$heatMapArray);
      }
}
