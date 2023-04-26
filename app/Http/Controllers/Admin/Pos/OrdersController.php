<?php

/**
 * @Author: abhi
 * @Date:   2021-09-21 00:16:59
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-05-31 00:07:57
 */
namespace App\Http\Controllers\Admin\Pos;

use PDF;
use App\City;
use App\Category;
use App\CountryPhoneCode;
use App\DeliveryDay;
use App\DeliveryLocation;
use App\DeliveryTime;
use App\Helpers\Helper;
use App\Notifications\OrderStatus;
use App\ProductOrderItem;
use App\Scopes\StatusScope;
use App\MeasurementClass;
use App\ProductOrder;
use App\Traits\ResponceTrait;
use App\User;
use App\VendorProduct;
use App\Product;
use App\Zone;
use App\SlotTime;
use App\CoinSettings;
use App\UserWallet;
use App\UserBalance;
use App\PaymentModeTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use App\AppSetting;

class OrdersController extends Controller
{
    use ResponceTrait;
    protected $user;
    protected $zone;
    protected $productOrder;
    protected $countryPhoneCode;
    protected $method;
    function __construct(Request $request,User $user, Zone $zone,CountryPhoneCode $countryPhoneCode,ProductOrder $productOrder,MeasurementClass $measurementClass)
    {
        parent::__construct();
        $this->user=$user;
        $this->productOrder=$productOrder;
        $this->zone=$zone;
        $this->measurementclass=$measurementClass;
        $this->countryPhoneCode=$countryPhoneCode;
        $this->method=$request->method();
    }

    public function index(Request $request) {
        if ($this->user->can('view', PosOrders::class)) {
            return abort(403,'not able to access');
        }
        $zones = Zone::get()->pluck('name','id');
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        $shoper=$this->user->where(['user_type'=>'shoper','role'=>'user'])->get()->pluck('full_name','id');
        $driver=$this->user->where(['user_type'=>'driver','role'=>'user'])->get()->pluck('full_name','id');
        $order_type = 'all';
        if (isset($request->order_type)) {
            $order_type = $request->order_type;
        }
        return view('admin/pages/pos/orders/index',compact(['zones','vandors','driver','shoper','order_type']));  
    }

    public function create()
    {
        if ($this->user->can('create', PosOrders::class)) {
            return abort(403,'not able to access');
        }
        $zones=$this->zone->get()->pluck('name','id');
        $countryPhoneCode=$this->countryPhoneCode->get()->pluck('phonecode','phonecode');
        $zones=$this->zone->get()->pluck('name','id');
        $shoppers=$this->user->where(['user_type'=>'shoper','role'=>'user'])->get()->pluck('full_name','id');
        $drivers=$this->user->where(['user_type'=>'driver','role'=>'user'])->get()->pluck('full_name','id');
        $vendors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        $AppSetting =AppSetting::select('mim_amount_for_order','mim_amount_for_free_delivery')->firstOrfail();
        $mim_amount_for_free_delivery = $AppSetting->mim_amount_for_free_delivery;
        $paymentModes = PaymentModeTranslation::get()->pluck('name','payment_mode_id');
        $customers = $this->user->select('*')->with(['deliveryLocation'])->get()->pluck('full_name','id');
        return view('admin/pages/pos/orders/add',compact(['zones','shoppers','drivers','vendors','countryPhoneCode','mim_amount_for_free_delivery','paymentModes','customers']));
    }

    public function store(Request $request) {
        /*echo '<pre>';
        print_r($request);
        echo '</pre>';*/
        //echo $request->payment_mode_id;
        //exit();
        $vendor_id = $request->vendor_id;
        $order_items = $request->order_items;
        $order_sub_total = $request->order_sub_total;
        $order_total = $request->order_total;
        $delivery_charge = $request->delivery_charge;
        $total_gst = $request->total_gst;
        $total_payment = $request->total_payment;
        $total_changes = $request->total_changes;
        $customer_phone = $request->customer_phone;
        $delivery_address_id = $request->delivery_address_id;
        $is_print = $request->is_print;
        $allDiscount = $request->allDiscount;
        $add_to_wallet = $request->add_to_wallet;
        $payment_mode_id = $request->payment_mode_id;
        $delivery_address_id = $request->delivery_address_id;
        $user_id = $request->user_id;
        $zone_id = $request->zone_id;
        $shopper_id = $request->shopper_id;
        $driver_id = (isset($request->driver_id) && $request->driver_id!='')?$request->driver_id:0;
        $delivery_date = (isset($request->delivery_date) && $request->delivery_date!='')?$request->delivery_date:date('Y-m-d');
        $delivery_time_id = (isset($request->delivery_time_id) && $request->delivery_time_id!='')?$request->delivery_time_id:0;
        $sodexo_charges = $request->sodexo_charges; 

        $request->request->remove('_token');
        $user = $this->user->select('*');
        $user->where(['id'=>$request->user_id]);
        $user = $user->first();
        /*echo '<pre>';
        print_r($user);
        echo '</pre>';
        exit();*/
        
        $product_ids = [];
        if(isset($order_items) && !empty($order_items)) {
            foreach($order_items as $key => $value) {
                $product_ids[] = $value['id'];
            }
        }
        $cartRec = VendorProduct::with(['product','Product.image'])->whereIn('id',$product_ids)->get();
        $result =[];
        $error=0;
        try {
            if(isset($cartRec) && !empty($cartRec)) {
                foreach ($cartRec as $Rec) {
                    $trmpArray = [];
                    $qty = $this->getProductData($order_items,$Rec['id'],'qty');
                    $price = $this->getProductData($order_items,$Rec['id'],'price');
                    $total_price = $this->getProductData($order_items,$Rec['id'],'total_price');
                    $trmpArray['price'] = $total_price;
                    $trmpArray['qty'] = $qty;
                    $trmpArray['vendor_product_id'] = $Rec['id'];
                    $trmpArray['offer_total'] = $price * $qty;
                    $trmpArray['message'] = $Rec['Product']['name'] . trans('site.out_of_stock').' Max quantity is '.$Rec['qty'];
                    $trmpArray['is_offer']='no';
                    $trmpArray['offer_data'] = json_encode(array());
                    if($Rec['is_offer']){
                        $trmpArray['offer_value']=$Rec['offer']['offer_value'];
                        $trmpArray['offer_type']=$Rec['offer']['offer_type'];
                        $trmpArray['offer_data']=json_encode($Rec['offer']);
                        $trmpArray['is_offer']='yes';
                        if($trmpArray['offer_type']=='amount'){
                                $trmpArray['offer_price'] = $price - $trmpArray['offer_value'];
                        }else{
                                $trmpArray['offer_price'] =  $price - (( $price * $trmpArray['offer_value'] ) / 100 );
                        }
                        $trmpArray['offer_price'] = $Rec['offer_price'] * $qty;
                        
                    }
                    
                    $newstock=$this->measurementclass->where(['id'=>$Rec['Product']['measurement_class']])->get()->toArray();
                  
                    $Rec['measurementclass'] =$newstock[0]['name'];
                   
                    $trmpArray['data']=json_encode(['vendor_product'=>$Rec]);
                    $trmpArray['status'] = 1;
                    /*echo '<pre>';
                    print_r($trmpArray);
                    echo '</pre>';*/
                    $result[] = $trmpArray;
                }
            }
        } catch (\Exception $e) {
            return $this->notFoundResponse($e);
        }
        if($error){

            return $this->outOfStockResponse(collect($result)->where('status','=',0)->first());
        }else{
            DB::beginTransaction();
            try {
                $delivery_charge = $delivery_charge;
                $tax = 0;
                $sub_total = collect($result)->sum('price');
                $offer_total = collect($result)->sum('offer_total');
                
                $AppSetting =AppSetting::select('mim_amount_for_order','mim_amount_for_free_delivery')->firstOrfail();
    
                // comment for remove minimum order amount //
                //if($sub_total<$AppSetting->mim_amount_for_order){
                /*$data  = [];
                $data['error']  = true;
                $data['code']  = 0;
                $data['message']  = 'You are not reach minimum amount  i.e. '.$AppSetting->mim_amount_for_order;
                return  response()->json($data);
                //die;
                }*/

                $input = [];
                $input['user_id'] = $user_id;
                $input['zone_id'] = $zone_id;
                $input['vendor_id'] = $vendor_id;
                $input['shopper_id'] = $shopper_id;
                $input['driver_id'] = $driver_id;
                $input['order_status'] = 'N';
                $input['cart_id'] = json_encode(array());
                $order_code =  Helper::orderCode($delivery_date,$zone_id,$delivery_time_id);
                $input['order_code'] = str_replace(" ", "",$order_code);
                if(isset($delivery_address_id) && $delivery_address_id!=0) {
                    $shipping_location = DeliveryLocation::with(['region'])->findOrFail($delivery_address_id);
                    if(!empty($shipping_location)){
                        $input['shipping_location'] = $shipping_location->toJson();
                    }else{
                        $input['shipping_location'] ="";
                    }
                } else {
                    $input['shipping_location'] ="";
                }
                
                $to_day = Carbon::createFromFormat('Y-m-d',$delivery_date)->format('l');
                /*$today_data = $this->zone->find($zone_id)->weekPackage->$to_day->getSlotTimes()->first(function ($today_data) use($request,$to_day) {
                    $today_data['name']=$to_day;
                    return $today_data->id==$delivery_time_id;
                });
                print_r($today_data);*/
                $today_data = '';
                $input['is_membership'] = 'N';  
                $user=User::where('id',$user_id)->first();
                 if(!empty($user->membership) && ($user->membership_to>=date('Y-m-d H:i:s')) ){
                   $input['is_membership'] = 'Y';  
                   }

                $input['delivery_time'] = json_encode($today_data,true);
                $input['delivery_charge'] = $delivery_charge;
               // $input['admin_discount'] = $request->admin_discount;
                //$input['coupon_code'] = (!empty($request->promo_discount))?"manual discount":'';
               // $input['coupon_amount'] = $request->promo_discount;
                $input['tax'] = $tax;
                $input['total_amount'] = $sub_total + $delivery_charge;;
                if($sodexo_charges>0) {
                    $sodex_amount = ($input['total_amount']/100)*$sodexo_charges;
                    $input['total_amount'] = $input['total_amount'] + $sodex_amount;
                }
                $input['offer_total'] = $offer_total + $delivery_charge;;
                $input['delivery_time_id'] = $delivery_time_id;
                $input['delivery_date'] = $delivery_date;
                $input['payment_mode_id'] = '1';
                $input['transaction_id'] = null;
                $input['transaction_status'] = '0';
                $input['order_type_id'] = 2;
                //echo json_encode($result);die;

//echo "<pre>"; print_r($input); die;
                $order = $this->productOrder->create($input);
                $order->ProductOrderItem()->createMany($result);
                $user_id_array1 = User::whereIn('id', [$user_id, $driver_id, $shopper_id])->select('id','device_type','device_token')->get();
                $shopper_id_array = $user_id_array1->where('id', $shopper_id)->pluck('device_token');
                $driver_id_array = $user_id_array1->where('id', $driver_id)->pluck('device_token');
                $user_id_array = $user_id_array1->where('id', $user_id)->pluck('device_token');
                $shopper_device_type_array =  $user_id_array1->where('id', $shopper_id)->pluck('device_type');
                $shopper_device_type = $shopper_device_type_array[0];
                
                if(isset($driver_id) && $driver_id!=0) {
                    $driver_device_type_array = $user_id_array1->where('id', $driver_id)->pluck('device_type');
                    $driver_device_type = $driver_device_type_array[0];
                }
                
                
                $user_device_type_array = $user_id_array1->where('id', $user_id)->pluck('device_type');
                $user_device_type=$user_device_type_array[0];
                $shopperArray = [];
                $shopperArray['type'] = 'Order';
                $shopperArray['product_type'] = 'new order';
                $shopperArray['title'] = 'New order placed';
                $shopperArray['body'] = trans('order.create_success_ordercode').$order->order_code;
                $dataArray = [];
                $dataArray['type'] = 'Order';
                $dataArray['product_type'] = 'New';
                $dataArray['title'] = 'New Order';
                $dataArray['body'] = trans('order.order_confirmed').$order->order_code;
               
                $data  = [];
                $data['order_code']  = $order->order_code;
                $data['order_id']  = $order->id;
                $data['msg']  = 'The Order placed successfully';
                
                /*$order->with('ProductOrderItem')->findOrFail($order->id);*/
                //echo json_encode($result);die;
                foreach ($result as $res){
                    VendorProduct::where(['id'=>$res['vendor_product_id']])->decrement('qty',$qty);
                }
                $coin = $this->addDarbaarCoin($input['total_amount']);
                $type_text = $this->bonusDescription($input['total_amount']);
                if($coin>0) {
                   $userWalletData = [
                        'user_id'=>$user_id,
                        'transaction_type'=>'CREDIT',
                        'transaction_id'=>$order->id,
                        'type'=>'Order Total Bonus',
                        'amount'=>$coin,
                        'description'=>$type_text,
                        'status'=>1,
                        'data'=>json_encode(['order_id'=>$order->id,'order_code'=>$order->order_code]),
                        'order_id'=>$order->id
                   ];
                   UserWallet::create($userWalletData);
                }
                if($add_to_wallet=='1' || $add_to_wallet==1) {
                    $userBalanceData = [
                        'user_id'=>$user_id,
                        'transaction_type'=>'CREDIT',
                        'transaction_id'=>$order->id,
                        'type'=>'Order Balance Credit',
                        'amount'=>$total_changes,
                        'description'=>'This is balance for order code - '.$order->order_code,
                        'status'=>1,
                        'data'=>json_encode(['order_id'=>$order->id,'order_code'=>$order->order_code]),
                        'order_id'=>$order->id
                   ];
                   UserBalance::create($userBalanceData);
                }
                DB::commit();
                    //customer notifiction
                Helper::sendNotification($user_id_array ,$dataArray, $user_device_type);
                    //shopper notifiction
                Helper::sendNotification($shopper_id_array ,$shopperArray, $shopper_device_type);
                   //driver notifiction
                if(isset($driver_id) && $driver_id!=0) {
                    Helper::sendNotification($driver_id_array ,$shopperArray, $driver_device_type);
                }
                

                return $this->listResponse($data);
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e->getMessage());
            }
        }
    }

    public function anyData(Request $request)
    {   //die('asd');

        $auth_user = Auth::guard('admin')->user();
        $auth_zone_ids = $auth_user->zone_id;
        $auth_zone_ids = explode(',',$auth_zone_ids);
        $auth_user_role = $auth_user->role;
        $orders = $this->productOrder->where(['order_type_id'=>'2'])->with(['User','zone','shopper','driver'])->select('*');
        
        if($auth_user_role!='admin') {
            if(isset($auth_zone_ids) && !empty($auth_zone_ids)) {
                for ($i=0; $i < count($auth_zone_ids); $i++) { 
                    $orders->where('zone_id', '=', $auth_zone_ids[$i]);
                    if($i>0) {
                        $orders->orWhere('zone_id', '=', $auth_zone_ids[$i]);
                    }
                }
            }
        }
        if($request->has('order_status') and !empty($request->order_status)){

            $orders->where(['order_status'=>$request->order_status]);
        }
        if($request->has('order_type') and $request->order_type=='today'){

            $orders->whereDate('created_at','=',date('Y-m-d'));
        }
        if($request->has('zone_id') and !empty($request->zone_id)){

            $orders->where(['zone_id'=>$request->zone_id]);
        }
        if($request->has('vendor_id') and !empty($request->vendor_id)){

            $orders->where(['vendor_id'=>$request->vendor_id]);
        }
        if($request->has('shopper_id') and !empty($request->shopper_id)){

            $orders->where(['shopper_id'=>$request->shopper_id]);
        }
        if($request->has('driver_id') and !empty($request->driver_id)){

            $orders->where(['driver_id'=>$request->driver_id]);
        }
        if ($request->has('delivery_from_date') and !empty($request->delivery_from_date)) {
            $orders->whereDate('delivery_date','>=',$request->delivery_from_date);
        }
        if ($request->has('delivery_to_date') and !empty($request->delivery_to_date)) {
            $orders->whereDate('delivery_date','<=',$request->delivery_to_date);
        }
        if ($request->has('created_from_date') and !empty($request->created_from_date)) {
            $orders->whereDate('created_at','>=',$request->created_from_date." 00:00:00");
        }
        if ($request->has('created_to_date') and !empty($request->created_to_date)) {
            $orders->whereDate('created_at','<=',$request->created_to_date." 23:59:59");
        }
        if ($request->has('total_amount_from') and !empty($request->total_amount_from)) {
            $orders->where('total_amount','>=',$request->total_amount_from);
        }
        if ($request->has('total_amount_to') and !empty($request->total_amount_to)) {
            $orders->where('total_amount','<=',$request->total_amount_to);
        }
        if($request->has('cust_type') and $request->cust_type=='today'){
              $orders->whereDate('created_at','=',date('Y-m-d'));
        }
        $orders->get();

        //$order =contact::query();
        return Datatables::of($orders)
             ->addColumn('order_status',function ($orders){
              if(array_key_exists($orders->order_status, Helper::$order_status)){
                return Helper::$order_status[$orders->order_status];
              }else{
                return "";
              }
                 
            })
           ->addColumn('delivery_date',function ($user){
                return date('d/m/Y',strtotime($user->delivery_date));
            })
            ->addColumn('time_slot',function ($orders){
                      if(!empty( $orders->delivery_time)){
                return $orders->delivery_time->from_time.'-'.$orders->delivery_time->to_time;
                    }else{
                        
                    return "Store Pick"; 
                    }
            })

            ->editColumn('total_amount',function ($orders){
              return isset($orders->offer_total) ? $orders->offer_total : '';

            })
            ->editColumn('shopper',function ($orders){
              return isset($orders->shopper) ? $orders->shopper->name : '';

            })
            ->editColumn('driver',function ($orders){
              return isset($orders->driver) ? $orders->driver->name : '';

            })

            ->addColumn('address',function ($orders){
              return isset($orders->shipping_location->address) ? $orders->shipping_location->address : '';
                //return $orders->shipping_location->address;

            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($orders){
               if($orders->order_status == 'S'){
                return '<a href="'.route('order.show',$orders->id).'"  class="btn btn-success">Show</a><a href="'.route('order.invoice',$orders->id).'"  class="btn btn-success">Invoice</a><a href="'.route('order.track',$orders->id).'"  class="btn btn-success">Track Order</a><a    onclick="makeAcall('.$orders->id.')"      class="btn btn-success">Call</a><a href="'.route('order.statuslist',$orders->id).'"  class="btn btn-success">Check Status</a>';
              }

              if($orders->order_status == 'N' || $orders->order_status == 'CF' || $orders->order_status == 'UP'){
               return '<a href="'.route('order.show',$orders->id).'"  class="btn btn-success">Show</a><a href="'.route('orders.show',$orders->id).'"  class="btn btn-success">Edit</a><a href="'.route('order.invoice',$orders->id).'"  class="btn btn-success">Invoice</a><a    onclick="makeAcall('.$orders->id.')"      class="btn btn-success">Call</a><a  onclick="popupchangestatus('.$orders->id.',\''.$orders->order_status.'\')" class="btn btn-success">Change Status</a><a href="'.route('order.statuslist',$orders->id).'"  class="btn btn-success">Check Status</a>';
              }else{
                  return '<a href="'.route('order.show',$orders->id).'"  class="btn btn-success">Show</a><a href="'.route('order.invoice',$orders->id).'"  class="btn btn-success">Invoice</a><a    onclick="makeAcall('.$orders->id.')"      class="btn btn-success">Call</a><a href="'.route('order.statuslist',$orders->id).'"  class="btn btn-success">Check Status</a>';
              }
            })
            ->filterColumn('user_phone', function($query, $keyword) {
                $sql = "exists (select * from `users` where `product_orders`.`user_id` = `users`.`id` and CONCAT(users.phone_code,'-',users.phone_number)  like ? and `users`.`phone_number` is not null order by `updated_at` desc) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('users.name', function($query, $keyword) {
               $sql = "exists (select * from `users` where `product_orders`.`user_id` = `users`.`id` and `users`.`name` like ? ) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('shopper', function($query, $keyword) {
               $sql = "exists (select * from `users` where `product_orders`.`shopper_id` = `users`.`id` and `users`.`name` like ? ) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('driver', function($query, $keyword) {
               $sql = "exists (select * from `users` where `product_orders`.`driver_id` = `users`.`id` and `users`.`name` like ? ) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('zone.name', function($query, $keyword) {
              $sql = "exists (select * from `zone_translations` where `product_orders`.`zone_id` = `zone_translations`.`zone_id` and `zone_translations`.`name` like ?  and `zone_translations`.`locale` = 'en'  order by `updated_at` desc) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            /*->filterColumn('order_status', function($query, $keyword) {
              $sql = "exists (select * from `product_orders` where `product_orders`.`order_status`  like ? ) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })*/
             /*->filterColumn('user_name', function($query, $keyword) {
            $query->havingRaw('LOWER(user.full_name) LIKE ?', ["%{$keyword}%"]);
            })*/
            
            ->rawColumns(['image','action'])
            ->make(true);
            
           // $category = $this->offer->with('User')->get();
          
        //$start = $request->start;
        
    }

    public function show($id)
    {
        $orders_details = $this->productOrder->with(['ProductOrderItem','zone'])->findOrFail($id);
    // echo "<pre>"; print_r($orders_details->toArray());die;
      //  print_r($orders_details->toArray());
        return view('admin/pages/pos/orders/show')->with('orders_details',$orders_details);
    }

    public function getProductData($products=array(),$id=null,$filterType=null) {
        $rerurnValue = 0;
        if(isset($products) && !empty($products)) {
            foreach($products as $key => $value) {
                if($value['id']==$id) {
                    if($filterType=='qty') {
                        $rerurnValue = $value['qty'];
                    } elseif($filterType=='price') {
                        $rerurnValue = $value['price'];
                    } elseif($filterType=='total_price') {
                        $rerurnValue = $value['total_price'];
                    } 
                    
                }
            }
        }
        return $rerurnValue;

    }

    public function getVendorProduct(Request $request){
        $vendor_id = $request->vendor_id;
        $user = $this->user->find($request->vendor_id);
        $product_ids = $request->product_ids;
        /*$vendorProduct = VendorProduct::leftJoin('product_translations','product_translations.product_id','vendor_products.product_id')->where('vendor_products.user_id','=',$vendor_id)->where('vendor_products.status','=',1)->where('vendor_products.qty','!=',0)->pluck('product_translations.name','vendor_products.id');*/
        $vendorProduct = VendorProduct::leftJoin('product_translations','product_translations.product_id','vendor_products.product_id')->where('vendor_products.status','1')->where('vendor_products.qty','!=',0)->where('vendor_products.user_id',$vendor_id)->pluck('product_translations.name','vendor_products.product_id');
        //$vendorProduct = $user->vendorProduct()->where('product_id', '!=',1108)->with(['Product','Product.image','Product.MeasurementClass'])->get();
        //echo "<pre>"; print_r($vendorProduct); die;
        $response = [
            'code' => 0,
            'error' => false,
            'message'=>trans('site.success'),
            'data' => $vendorProduct,
        ];
        return response()->json($response, 200);
    }
    public function getVendorProductDetail(Request $request){
        $vendor_id = $request->vendor_id;
        $user = $this->user->find($request->vendor_id);
        $product_id = $request->product_id;
        $vendorProduct = $user->vendorProduct()->where('product_id', '=',$product_id)->with(['Product','Product.image','Product.MeasurementClass'])->first();
        //echo "<pre>"; print_r($vendorProduct); die;
        $response = [
            'code' => 0,
            'error' => false,
            'message'=>trans('site.success'),
            'data' => $vendorProduct,
        ];
        return response()->json($response, 200);
    }

    public function addDarbaarCoin($order_total=0) {
        $coin = 0;
        if($order_total>0) {
            $coinSettings = CoinSettings::latest()->first();
            if($order_total>=$coinSettings->to_amount) {
                $coin = $coinSettings->coin;
            } else {
                $coinSettings = CoinSettings::where('status','1')->get();
                if(isset($coinSettings) && !empty($coinSettings)) {
                    foreach ($coinSettings as $key => $value) {
                        if($order_total>=$value['from_amount'] && $order_total<=$value['to_amount']) {
                            $coin = $value['coin'];
                        }
                    }
                }
            }
        //    echo $coin;
        }
        return $coin;
    }

    public function bonusDescription($order_total=0) {
        $type_text = '';
        if($order_total>0) {
            $coinSettings = CoinSettings::latest()->first();
            if($order_total>=$coinSettings->to_amount) {
                $coin = $coinSettings->coin;
            } else {
                $coinSettings = CoinSettings::where('status','1')->get();
                if(isset($coinSettings) && !empty($coinSettings)) {
                    foreach ($coinSettings as $key => $value) {
                        if($order_total>=$value['from_amount'] && $order_total<=$value['to_amount']) {
                            $type_text = 'Order total between '.$value['from_amount'].' '.$value['to_amount'];
                        }
                    }
                }
            }
        //    echo $type_text;
        }
        return $type_text;
    }

    public function  pdfdownload($id){
    // $orders_details = $this->productOrder->with(['ProductOrderItem','ProductOrderItem.Product','ProductOrderItem.Product.MeasurementClass','vendor','driver','shopper','User','zone','PaymentMode'])->findOrFail($id);
    //     if(isset($orders_details)){
    //        $user = User::where('id',$orders_details->user_id)->first();
    //      }
    //     return view('pages.pdfdownload')->with('orders_details',$orders_details)->with('id',$id)->with('user',$user); 
      

 // Fetch all customers from database
    $orders_details = $this->productOrder->with(['ProductOrderItem','ProductOrderItem.Product','ProductOrderItem.Product.MeasurementClass','vendor','driver','shopper','User','zone','PaymentMode'])->findOrFail($id);
        if(isset($orders_details)){
           $orders_details->user = User::where('id',$orders_details->user_id)->first();
         }
         $orders_details->id = $id;
    // Send data to the view using loadView function of PDF facade
    $pdf = PDF::loadView('admin.pages.pos.orders.pdfdownload',['orders_details'=>$orders_details,'id'=>$id]);
    // If you want to store the generated pdf to the server then you can use the store function
    //echo storage_path(); die;
    $pdf->save('/var/www/html/darbar_mart/public/invoices/order_'.$id.'_filename.pdf');
    // Finally, you can download the file using download function
   return  $pdf->download('order-'.$orders_details->order_code.'.pdf');

//    return redirect('/invoice/'.$id);
    }

    public function print($id) {
        $orders_details = $this->productOrder->with(['ProductOrderItem','ProductOrderItem.Product','ProductOrderItem.Product.MeasurementClass','vendor','driver','shopper','User','zone','PaymentMode'])->findOrFail($id);
        if(isset($orders_details)){
            $orders_details->user = User::where('id',$orders_details->user_id)->first();
        }
        $orders_details->id = $id;
        /*echo '<pre>';
        print_r($orders_details);
        echo '</pre>';
        exit();*/
        return view('admin.pages.pos.orders.print')->with('orders_details',$orders_details)->with('id',$id)->with('is_print',true);
    }

    public function getUserByParam(Request $request) {
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

    public function getBarcodeProduct(Request $request) {
        $barcode = $request->barcode;
        $vendor_id = $request->vendor_id;
        $user = $this->user->find($request->vendor_id);
        
        $product = Product::where('barcode','=',$barcode)->first();
        $product_id = $product->id;
        $vendorProduct = $user->vendorProduct()->select('id')->where('product_id', '=',$product_id)->with(['Product'])->first();
        $response = [
            'code' => 0,
            'error' => false,
            'message'=>trans('site.success'),
            'data' => $vendorProduct,
        ];
        return response()->json($response, 200);

    }
}