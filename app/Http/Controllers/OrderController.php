<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use PDF;
use Carbon\Carbon;
use App\Slider;
use App\SiteSetting;
use App\AppSetting;
use App\Ads;
use App\Category;
use App\CategoryTranslation;
use App\User;
use App\VendorProduct;
use App\CountryPhoneCode;
use App\WishLish;
use App\Product;
use App\ProductTranslation;
use App\ProductOrder;
use App\ProductOrderItem;
use App\PaymentMode;
use App\Offer;
use App\Zone;
use App\Cart;
use App\AccessLevel;
use App\MeasurementClass;
use App\DeliveryDay;
use App\DeliveryLocation;
use App\Membership;
use App\Helpers\Helper;
use App\Providers\RouteServiceProvider;
use App\ZoneTranslation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Contracts\Auth\Authenticatable;
use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\UrlGenerator;

use App\UserWallet;

use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\Http\Controllers\WhatsappController;


use GuzzleHttp\Client;

class OrderController  extends Controller
{
     use RestControllerTrait,ResponceTrait;

        /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(Request $request,CategoryTranslation $category,ProductOrderItem $ProductOrderItem,User $user,Product $product,Offer $offer,VendorProduct $vendorProduct,DeliveryLocation $deliveryLocation,Zone $zone,SiteSetting $site_setting,Cart $cart,MeasurementClass $measurementclass,ProductOrder $productOrder, UserWallet $user_wallet) 
    {
        parent::__construct();
        $this->category=$category;
        $this->user=$user; 
        $this->offer=$offer;
        $this->product=$product;
        $this->zone=$zone;
        $this->vendorProduct=$vendorProduct;
        $this->site_setting=$site_setting;
        $this->deliveryLocation=$deliveryLocation;
        $this->cart=$cart;
        $this->productOrder = $productOrder;
        $this->ProductOrderItem =$ProductOrderItem;
        $this->measurementclass = $measurementclass;
        $this->user_wallet = $user_wallet;
        $this->middleware('auth');
    }


    public function deliverytimes()
    {
        return view('pages.deliverytimes');
    }
    
    public function orderhistory(){
        $orders = $this->productOrder->where('user_id',Auth::user()->id)->get();
        $past_orders = $this->productOrder->where('user_id',Auth::user()->id)
        ->where(function($q){
          $q->where('order_status','D')->orwhere('order_status','C')->orwhere('order_status','R');
        })->paginate(10);
         $current_orders = $this->productOrder->where('user_id',Auth::user()->id)
         ->where(function($q){
            $q->where('order_status','!=','D')->where('order_status','!=','C')->where('order_status','!=','R');
          // $q->where('order_status','PO')->orwhere('order_status','N')->orwhere('order_status','O')->orwhere('order_status','A')->orwhere('order_status','S');
        })->OrderBy('id','DESC')->paginate(10);
        return view('pages.orderhistory')->with('orders',$orders)->with('past_orders',$past_orders)->with('current_orders',$current_orders);
    }

     public function trackorder(Request $request){
        $order_detail =$this->productOrder->find($request->id);
        if(empty($order_detail)){     
         return view('pages.NoProductpage')->with('message','No Order With this Details'); 
       }
        $data = $this->orderDetails($request->id);
        /*echo '<pre>';
        print_r($data);
        echo '</pre>';*/
        return view('pages.trackorder')->with('data',$data)->with('order_detail',$order_detail);
     }
     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request){
        $input =$request->all();
        $order_type = $request->order_type;
        $globalSetting= Helper::globalSetting();
          // echo $request->cart_total." and ";
           //echo $globalSetting->mim_amount_for_order; die;
        $min_order_amount = $globalSetting->mim_amount_for_order;
        if(Auth::guard()->user()->membership!='' && (Auth::guard()->user()->membership_to>=date('Y-m-d H:i:s')) ){
            $membership_id = Auth::guard()->user()->membership;
            $membership_details = Membership::select('min_order_price')->where('id','=',$membership_id)->first();
            $min_order_price = $membership_details->min_order_price;
            $min_order_amount = $min_order_price;
        }
        if($request->cart_total<$min_order_amount){
            $msg = "You have not reach the minimum amount of ₹ ".$min_order_amount." per order";
            return back()->with('message',$msg);
        }

        $request->session()->put('order_type', $request->order_type);
        if($order_type=='Schedule'){
            $deliveryDay = $this->deliveryDay();
            if(!empty(Auth::guard()->user()->membership) && (Auth::guard()->user()->membership_to>=date('Y-m-d H:i:s')) ){
              $is_membership = 'Y';  
            }else{
              $is_membership = 'N';  
            }
            if(!empty($deliveryDay)){
              return view('pages.deliverytimes',['deliveryDay'=>$deliveryDay,'is_membership' => $is_membership]);
            }else{
              return view('pages.NoProductpage')->with('message','Sorry! No week package assigned to this zone. Kindly try another zone.');
            } 
            
        }else if($order_type=='Standard' || $order_type=='Express'){
              $request->session()->put('delivery_day', date('Y-m-d'));
              $request->session()->put('delivery_time_id', 0);
           $deliveryLocation = $this->deliveryLocation->with(['user','region'])->where('user_id','=',Auth::user()->id)->orderBy('updated_at','desc')->get();
           $sessionZone = $request->session()->get('zone_id');
            $selectedid =0;
            foreach($deliveryLocation as $addrss){
              $zone_data = $this->getZoneData($addrss->lat,$addrss->lng);
              if($zone_data['zone_id']==$sessionZone){ 
                $selectedid = $addrss->id; 
                 }
            }  
            return view('pages.shippingaddress',['deliveryLocation'=>$deliveryLocation,'selectedid'=>$selectedid]);
        }
    }
    public function  getaddress(Request $request){
        $delivery_time_id = $request->delivery_time_id;
        $request->session()->put('delivery_time_id', $delivery_time_id);
        $delivery_day = $request->delivery_day;
        $request->session()->put('delivery_day', $delivery_day);
            $deliveryLocation = $this->deliveryLocation->with(['user','region'])->where('user_id','=',Auth::user()->id)->orderBy('updated_at','desc')->get();
           $sessionZone = $request->session()->get('zone_id');
         $selectedid = 0 ;
           foreach($deliveryLocation as $addrss){
              $zone_data = $this->getZoneData($addrss->lat,$addrss->lng);
              if($zone_data['zone_id']==$sessionZone){ $selectedid = $addrss->id; }
            } 
            $userdata = $this->user->find(Auth::user()->id) ;
        return view('pages.shippingaddress',['deliveryLocation'=>$deliveryLocation,'selectedid'=>$selectedid,'wallet_amount'=>$userdata]);
    }

    public function deliveryDay() {
        try
        {   
            $dataArray = [];
            $time = time();
            $today_date = now();
            $to_day = $today_date->format('l');
            $tomorrow_date = now()->addDay();
            $tomorrow_day = $tomorrow_date->format('l');
            $next_tomorrow_date = now()->addDays(2);
            $next_tomorrow_day = $next_tomorrow_date->format('l');
            try{
                $zone_id = Session::get('zone_id');
                if(empty($zone_id)){
                    $zone_id = Auth::user()->zone_id; 
                    if(empty($zone_id)){
                        $zonedata = $this->getZoneData(Auth::user()->lat, Auth::user()->lng);
                        $vendor_zone_id = $zone_id = $zonedata['zone_id'];
                        $zone_name = $zonedata['zone_name'];
                        $match_in_zone = $zonedata['match_in_zone'];
                      }
                }
                $userAddress  = $this->user->with(['DeliveryLocation'])->find(Auth::user()->id);
                $zone =  $this->zone->where('id',$zone_id)->first();
          
            }
            catch(Exception $e){  
                return $e; print_r($e->getMessage()); die; 
            }
         
            date_default_timezone_set('asia/Kolkata');
            //dd($zone->weekPackage);
            if(!empty($zone->weekPackage)){
                $today_data = $zone->weekPackage->$to_day->getSlotTimes()->map(function ($today_data)use($today_date) {
                        $today_data['no_of_order']=ProductOrder::where(['delivery_time_id'=>$today_data->id,'delivery_date'=>$today_date->format('Y-m-d')])->count();
                        $to_time = strtotime($today_data['to_time']);  //echo '---'.$today_data['to_time'].'---'; 
                        $from_time = strtotime($today_data['from_time']);//echo '---'.$today_data['from_time'].'---';
                      
                        $tt_time = time();  //echo '---'.date('H:i:s').'---';die;
                        if($tt_time >= strtotime($today_data['lock_time'])){
                            $today_data['is_clickable'] = 'N';
                        }else{
                            $today_data['is_clickable'] = 'Y';
                        }
                        $dateTime = now(); 
                        $today_data['current_time'] = $dateTime->format("d/m/y  H:i A");
                        return $today_data;
                    });
            }
        }
        catch(Exception $e){  return $e;  print_r($e->getMessage()); die;  }
        if(!empty($zone->weekPackage)){
            $tomorrow_data = $zone->weekPackage->$tomorrow_day->getSlotTimes()->map(function ($tomorrow_data)use($tomorrow_date) {
                $tomorrow_data['no_of_order']=ProductOrder::where(['delivery_time_id'=>$tomorrow_data->id,'delivery_date'=>$tomorrow_date->format('Y-m-d')])->count();
                 $tomorrow_data['is_clickable'] = 'Y';
                return $tomorrow_data;
            });
            $next_tomorrow_data = $zone->weekPackage->$next_tomorrow_day->getSlotTimes()->map(function ($next_tomorrow_data)use($next_tomorrow_date) {
                $next_tomorrow_data['no_of_order']=ProductOrder::where(['delivery_time_id'=>$next_tomorrow_data->id,'delivery_date'=>$next_tomorrow_date->format('Y-m-d')])->count();
                
                $next_tomorrow_data['is_clickable'] = 'Y';
                return $next_tomorrow_data;
            });
            if (date('H') >= 23){ /* .. */ 
              $dataArray=[
                  ['name'=> trans('site.'. lcfirst($to_day)),'date'=>$today_date->format('Y-m-d'),'delivery_time'=>$today_data],
                  ['name'=>trans('site.'. lcfirst($tomorrow_day)),'date'=>$tomorrow_date->format('Y-m-d'),'delivery_time'=>$tomorrow_data],
              //    ['name'=>trans('site.'. lcfirst($next_tomorrow_day)),'date'=>$next_tomorrow_date->format('Y-m-d'),'delivery_time'=>$next_tomorrow_data]
              ];
            }else {
                $dataArray=[
                      ['name'=> trans('site.'. lcfirst($to_day)),'date'=>$today_date->format('Y-m-d'),'delivery_time'=>$today_data],
                     ['name'=>trans('site.'. lcfirst($tomorrow_day)),'date'=>$tomorrow_date->format('Y-m-d'),'delivery_time'=>$tomorrow_data],
                     ['name'=>trans('site.'. lcfirst($next_tomorrow_day)),'date'=>$next_tomorrow_date->format('Y-m-d'),'delivery_time'=>$next_tomorrow_data]
                ];
            }
        }
        return $dataArray;
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){ 

        if($request->selectedid == 0){
             return redirect('/getaddress')->with('error','Out Of Zone Order Not Accept!');
        }
        if($request->payment_mode_id==1){
            $order_count = $this->productOrder->where('user_id',Auth::user()->id)->where('payment_mode_id',1)->whereIn('order_status',['N','O','S','A','U','UP'])->count();
            if($order_count > 2){
                $msg =  $this->outOfStockResponse($order_count);
                if($msg->getData()->error){
                    return view('pages.NoProductpage')->with('message', "Sorry! You have 3 or more order with COD. Try another payment option.");
                }
            }
        }

        $site_settings =  $this->site_setting->first();
        $order_type = $request->session()->get('order_type');
        $delivery_time_id = $request->session()->get('delivery_time_id');
        $delivery_date  =  $delivery_day = $request->session()->get('delivery_day');
        $zone_id =  $request->session()->get('zone_id');

        $input = [];    
        $shipping_location = DeliveryLocation::with(['region'])->findOrFail($request->shipping_location_id);
        if(empty($shipping_location)){
            $input['shipping_location'] =json_encode(array());
        }else{
            $input['shipping_location'] = $shipping_location->toJson();
            if(isset($shipping_location['lat']) && isset($shipping_location['lng'])){
                $zonedata = $this->getZoneData($shipping_location['lat'], $shipping_location['lng']);
                $zone_id =  $zonedata['zone_id'];
            }else{
                $zonedata = $this->zone->where('id',Auth::user()->zone_id)->first()->toArray();
                $zone_id =  $zonedata['zone_id'];
            }
        }
        try{
            $AppSetting =AppSetting::select('mim_amount_for_order','mim_amount_for_free_delivery')->firstOrfail();
            $cartRec = $this->cart->with(['vendorProduct','vendorProduct.Product'])->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id])->get();
        }catch(Exception $e){  
            return $e;   
            //print_r($e->getMessage()); 
            //die; 
        }

        $result =[];  
        $error=0;
        try {   
            if($cartRec->count()=='0'){
                return view('pages.NoProductpage')->with('message','No product on this address');
            }
             
            foreach ($cartRec as $Rec) {
                $trmpArray = [];
                $qty = Helper::outOfStock($Rec['vendor_product_id'],$zone_id);
                if($Rec['qty'] <= $qty) {
                    $trmpArray['offer_data'] = json_encode(array());
                    $trmpArray['is_offer']='no';
                    $trmpArray['price'] =  $Rec['vendorProduct']['price'];
                    $offer = $this->offer->where('id',$Rec['vendorProduct']['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
                    if(!empty($offer)){
                        if($offer->offer_type == 'amount'){
                            $trmpArray['offer_price'] = $Rec['vendorProduct']['price'] - $offer->offer_value;
                        }else if($offer->offer_type=='percentages'){
                            $trmpArray['offer_price'] =  $Rec['vendorProduct']['price'] - ( ( $Rec['vendorProduct']['price'] * $offer->offer_value ) / 100 ) ;
                        }
                        $trmpArray['offer_value']=$offer->offer_value;
                        $trmpArray['offer_type']=$offer->offer_type;
                        $trmpArray['offer_data']= json_encode($offer);
                        $trmpArray['is_offer']='yes';
                        $trmpArray['offer_price'] = $trmpArray['offer_price'];
                    } else{
                       $trmpArray['offer_price'] = $trmpArray['price'];
                    }

                    $trmpArray['price'] = $trmpArray['price'] * $Rec['qty'];
                    $trmpArray['offer_price'] = $trmpArray['offer_price'] * $Rec['qty'];
                    $trmpArray['qty'] = $Rec['qty'];
                    $trmpArray['vendor_product_id'] = $Rec['vendorProduct']['id'];
                    $trmpArray['offer_total'] = $trmpArray['offer_price'] ;
                    $trmpArray['message'] = $Rec['Product']['name'] .' (' .trans('site.out_of_stock').'). '.trans('site.max_qty').' '.$Rec['qty'];                      
                    $newstock=$this->measurementclass->where(['id'=>$Rec['vendorProduct']['Product']['measurement_class']])->get()->toArray();  

                    $newstockArray = [];
                    if(isset($newstock)){
                        if(isset($newstock[0]['translations'])) {
                            foreach ($newstock[0]['translations'] as $value) {
                                $newstockArray[$value['locale']]= $value;
                            }
                        }
                    }
                    if(isset($Rec['vendorProduct']['Product'])) {
                        $Rec['vendorProduct']['Product']['measurementclass'] = isset($newstockArray[App::getLocale()]['name'])? $newstockArray[App::getLocale()]['name'] : '';
                    }
                    $trmpArray['data']=$Rec;
                    $trmpArray['status'] = 1;
                } else {
                    $trmpArray['total'] = 0;
                    $trmpArray['offer_total'] = 0;
                    $trmpArray['message'] = $Rec['vendorProduct']['Product']['name'] . ' ('.trans('site.out_of_stock').'). '.trans('site.max_qty').' '.$Rec['qty'];
                    $trmpArray['status'] = 0;
                    $error = 1;
                }
                $result[] = $trmpArray;
            } 
            
        } catch (Exception $e) { 
            return $e;   
        }
        // echo "<pre>"; print_r($result); die;
        if($error){

           $msg =  $this->outOfStockResponse(collect($result)->where('status','=','0')->first());
           //print_r($msg); die();
            if($msg->getData()->error){
                return view('pages.NoProductpage')->with('message', $msg->getData()->message);
            }
        }else{

            DB::beginTransaction();
          try{
                $zone_data =  $this->zone->where('id',$zone_id)->first();
                //$delivery_charge = $zone_data->delivery_charges;
                $tax = 0;
                $sub_total = collect($result)->sum('price');
                $offer_total = collect($result)->sum('offer_price');
                if($offer_total >= $zone_data->minimum_order_amount) {
                    $delivery_charge = 0;
                } else {
                    $delivery_charge = $zone_data->delivery_charges;
                }
                
                if((!empty(Auth::user()->membership)) && (Auth::user()->membership_to>=date('Y-m-d H:i:s')) ){
                  if($offer_total >= $AppSetting->mim_amount_for_free_delivery_prime){ 
                    $delivery_charge = 0;
                  }else{ 
                    $delivery_charge = $delivery_charge; 
                  }
                }else{
                   $mim_amount_for_free_delivery = ($zone_data->minimum_order_amount!='0') ? $zone_data->minimum_order_amount : $AppSetting->mim_amount_for_free_delivery; 
                  if($offer_total >= $mim_amount_for_free_delivery){  
                    $delivery_charge = 0;
                  }else{   
                    $delivery_charge = $delivery_charge; 
                  }
                }
                
                $q = $request->all();
                $input['user_id'] = Auth::user()->id;
                $input['zone_id'] = $zone_id;
                $input['vendor_id'] = null;
                $input['shopper_id'] = null;
                $input['driver_id'] = null;
                $input['order_status'] = 'N';
                $input['delivery_boy_tip'] = $request->deliveryboytip;
                $delivery_boy_tip = ($request->deliveryboytip > 0)?$request->deliveryboytip:0;
                // $input['notes'] = $request->notes;
                $input['cart_id'] =  json_encode(array());
                if(empty($delivery_day) || (!isset($delivery_day))){  
                    $delivery_day = date('Y-m-d'); 
                    $delivery_date = date('Y-m-d'); 
                }           
                $to_day = Carbon::createFromFormat('Y-m-d',$delivery_day)->format('l');

                    $input['delivery_time_id'] = $delivery_time_id;
                    $input['delivery_date'] = $delivery_day;
                    //$order_code =  Helper::orderCode($delivery_date,$zone_id,$delivery_time_id);
                    if($delivery_time_id=='fast_delivery'){
                      $time = date('h');
                      $fast_delivery = date('+3h',strtotime($time));
                      $order_code =  Helper::orderCode($delivery_date,Auth::guard()->user()->zone_id,'',$fast_delivery);
                    }else{  
                      $order_code =  Helper::orderCode($delivery_date,Auth::guard()->user()->zone_id,$delivery_time_id);
                    }
                    // dd($order_code);
                    $input['order_code'] = str_replace(" ", "",$order_code);
           
                  $to_day = Carbon::createFromFormat('Y-m-d',$delivery_date)->format('l');
                  $today_data = Auth::user()->zone->weekPackage->$to_day->getSlotTimes()->first(function ($today_data) use($request,$to_day,$delivery_time_id) {
                    $today_data['name']=$to_day;
                    return $today_data->id==$delivery_time_id;
                  });

                    $input['delivery_time'] = json_encode($today_data,true);
                    
                    $input['tax'] = $tax;
                    $input['total_amount'] = $sub_total;
                    $input['offer_total'] = $offer_total;
                    $input['delivery_charge'] = $delivery_charge;
                    $input['payment_mode_id'] = $request->payment_mode_id;
                    $input['transaction_id'] = null;
                    $input['transaction_status'] = '0';
                    if(!empty($request->session()->get('coupon_discount'))){
                       $input['coupon_amount'] = $request->session()->get('coupon_discount');
                    }
                    if(!empty($request->session()->get('coupon_text'))){
                       $input['coupon_code'] = $request->session()->get('coupon_text');
                    }


                    if(empty($input) || empty($result)){ 
                        $data = 'No Product Selected';
                        return view('pages.NoProductpage')->with('message',$data);
                    }
                    // foreach($result as $k => $re){
                    //     $result[$k]['price'] = $re['offer_price'];
                    // }
                    //print_r($input); die("====okay");
                    $order = $this->productOrder->create($input);
                    $order->ProductOrderItem()->createMany($result);
                    $data  = $this->orderDetails($order->id);

                    if($request->payment_mode_id=='1'){

                      $client = new Client();
                      $authkey = env('AUTHKEY');
                      $phone_number = Auth::user()->phone_number;
                      $senderid = env('SENDERID');

                      $client = new Client();
                      
                      $message = "Thanks for the order. We will try to dispatch your order ASAP. Thanks!";
                        
                      $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91");

                      $statusCode = $response->getStatusCode(); 

                      // comment for stop cashback by Abhishek Bhatt//
                        /*if($input['offer_total'] >= 2500){
                          $amount =  100;
                          $transaction_type = "CREDIT";
                          $type ="Order Total Bonus";
                          $transaction_id = 'DAR'.time().$order->id;
                          $description ="Order purchase is more than 2500. Order Total bonus.";
                          $json_data = json_encode(['refuser'=>Auth::user()->id]); 
                          $order_id = $order->id; 
                          $user_wallet = Helper::updateCustomerWallet(Auth::user()->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id);
                        }*/
                    }

                    if($request->payment_mode_id=='2'){

                        $darbaar_coin_price = Session::get('darbaar_coin_price');

                        if($darbaar_coin_price > 0) {
                            $transaction_id = "DAR".time().$order->id;
                            $order_id = $order->id;
                            $description = "Darbarar Coin Applied for Product Order.";
                            $json_data = json_encode(['order_id'=>$order_id,'order_code'=>$order_code]);
                            $darbaar_coin_price = Session::get('darbaar_coin_price');
                            Helper::updateCustomerCoins(Auth::user()->id,$darbaar_coin_price,'DEBIT',"Order Payment",$transaction_id,$description,$json_data,$order_id);
                        }
                        
                        $offer_total = number_format($input['offer_total'],2,'.','');
                        $offer_total = $offer_total - $darbaar_coin_price;
                        
                        $pdata_array = array(
                            'order_id' => $order->id,
                            'order_code' => $order->order_code,
                            'amount'  => $offer_total,
                            'zone_id'  => $zone_id,
                            'delivery_charge' => $delivery_charge,
                            'payment_mode_id'  => $request->payment_mode_id,
                            'user_id' => Auth::user()->id,
                            'email' => Auth::user()->email,
                            'phone_number' => Auth::user()->phone_number,
                            'coin_payment' => $darbaar_coin_price
                        );
                        $pdata = collect($pdata_array);
                        $request->session()->put('payment_data',$pdata);
                        DB::commit();
                        //return redirect('/paywithrazorpay/'.encrypt($order->id));
                        return redirect('/order-payment/'.encrypt($order->id));
                    }elseif($request->payment_mode_id == '3'){
                        $wallet_amount = Helper::getUpdatedWalletData(Auth::user()->id);
                        if($wallet_amount->wallet_amount <= 0){
                            $msg = "You do not have amount in wallet. Choose another payment mode.";
                            return view('pages.NoProductpage')->with('message',$msg);
                        }

                        // comment for stop cashback by Abhishek Bhatt//
                        /*if($input['offer_total'] >= 2500){
                          $amount =  100;
                          $transaction_type = "CREDIT";
                          $type ="Order Total Bonus";
                          $transaction_id = 'DAR'.time().$order->id;
                          $description ="Order purchase is more than 2500. Order Total bonus.";
                          $json_data = json_encode(['refuser'=>Auth::user()->id]); 
                          $order_id = $order->id; 
                          $user_wallet = Helper::updateCustomerWallet(Auth::user()->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id);
                        }*/
                        $new_total = $offer_total-$wallet_amount->wallet_amount;
                        if($new_total <= 0){
                            $wd = $offer_total;
                            $wallet_total = $wd + $delivery_charge + $delivery_boy_tip;
                        }else{
                            $wd = $wallet_amount->wallet_amount;
                            $wallet_total = $offer_total + $delivery_charge + $delivery_boy_tip;
                        }
                        $new_total = $new_total + $delivery_charge + $delivery_boy_tip;
                        if($wallet_amount->wallet_amount >= $wallet_total){
                          $amount =  number_format($wallet_total,2,'.','');
                          $transaction_type = "DEBIT";
                          $type ="Order Payment";
                          $transaction_id = 'DAR'.time().$order->id;
                          $description ="Order purchase payment.";
                          $json_data = json_encode(['order_id'=>$order->id]); 
                          $order_id = $order->id; 
                          $user_wallet = Helper::updateCustomerWallet(Auth::user()->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id);
                        }
                        $darbaar_coin_price = Session::get('darbaar_coin_price');
                        if($darbaar_coin_price > 0) {
                            $transaction_id = "DAR".time().$order->id;
                            $order_id = $order->id;
                            $description = "Darbarar Coin Applied for Product Order.";
                            $json_data = json_encode(['order_id'=>$order_id,'order_code'=>$order_code]);
                            $darbaar_coin_price = Session::get('darbaar_coin_price');
                            Helper::updateCustomerCoins(Auth::user()->id,$darbaar_coin_price,'DEBIT',"Order Payment",$transaction_id,$description,$json_data,$order_id);
                        }

                        $new_total = $new_total - $darbaar_coin_price;

                        if($new_total > 0){
                            $pdata_array = array(
                            'order_id' => $order->id,
                            'order_code' => $order->order_code,
                            'amount'  => $new_total,
                            'wallet_amount'  => $wd,
                            'zone_id'  => $zone_id,
                            'payment_mode_id'  => $request->payment_mode_id,
                            'user_id' => Auth::user()->id,
                            'email' => Auth::user()->email,
                            'phone_number' => Auth::user()->phone_number,
                            'coin_payment' => $darbaar_coin_price
                        );
                        $pdata = collect($pdata_array);

                        $request->session()->put('payment_data',$pdata);
                        DB::commit();
                        //return redirect('/paywithrazorpay/'.encrypt($order->id));
                        return redirect('/order-payment/'.encrypt($order->id));
                        }
                    
                    }

                    if(!empty($request->session()->get('coupon_discount'))){
                        $request->session()->forget('coupon_discount');
                        $request->session()->forget('coupon_text');
                    }
                    Session::flash(trans('order.order_confirmed').$order->order_code);
                    /*send notification to vendor*/
                    $vendor_id_array1 =  User::whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor'])->select('id','device_type','device_token','name')->get();
                    $vendorData = User::whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor'])->select('device_token')->get();
                    $vendor_id_array = collect($vendorData)->pluck('device_token');
                    $vendordataArray = [];
                    $vendordataArray['type'] = 'Order';
                    $vendordataArray['product_type'] = 'New';
                    $vendordataArray['title'] = 'New Order';
                    $vendordataArray['body'] = 'New order placed in your Zone';
                    foreach($vendor_id_array1 as $vendors){
                       $vendordevice_type = $vendors->device_type;
                       Helper::sendNotification($vendor_id_array ,$vendordataArray, $vendordevice_type);
                    }
                    try{
                      if($delivery_time_id == "fast_delivery"){
                      }else{
                          if(isset($delivery_time_id) && !empty($delivery_time_id)){
                            $deliveryTime = Helper::getDeliveryTimeById($delivery_time_id);
                          }else{
                              $t=time();
                              $DeliveryTime['from_time'] = date('H:i:s',$t);
                              $DeliveryTime['to_time'] = $endTime = date("H:i", strtotime('+30 minutes',$t));
                              $deliveryTime=(object)$DeliveryTime;
                          }

                          if(isset($deliveryTime)){ 
                              $message = '#'.$order->order_code.trans('order.new_order').' '.$deliveryTime->from_time.'-'.$deliveryTime->to_time;
                          }else{  
                              $message = '#'.$order->order_code.trans('order.new_order');       
                          }
                            $type = 'new order';
                      }
                      /*end admin notification*/
                    }catch(\Exception $e){  
                        return view('pages.NoProductpage')->with('message',$e->getMessage());
                    }
                    foreach ($result as $res){
                      VendorProduct::where(['id'=>$res['vendor_product_id']])->decrement('qty',$res['qty']);
                    }
                    DB::commit();
                    $this->cart->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id])->delete();
           
                    // return view('pages.trackorder')->with('data',$data)->with('Success',trans('order.order_confirmed').$order->order_code);
                    // add for send whatsApp invoice added by Abhishek Bhatt //
                    $whatsApp = new WhatsappController();
                    $whatsApp->sendFile($order->id,Auth::user()->phone_number);
                    return redirect('/track-order/'.$order->id)->with('Success',trans('order.order_confirmed').$order->order_code);

            } catch (\Exception $e) {
                return $e;
                DB::rollBack();
                return view('pages.NoProductpage')->with('message',$e->getMessage());
            }
        }
    }

      public function orderDetails($id){
        $order =  $this->productOrder->select(['id','order_status','order_code','delivery_date','delivery_time','shipping_location','total_amount','offer_total','delivery_charge','coupon_amount','created_at','shopper_id','driver_id','coin_payment'])->where(['id'=>$id])->with(['ProductOrderItem'])->first();
        //     echo "<pre>"; print_r($order); die;
        if(!empty($order->delivery_time)){
        $order->time_slot = trim(preg_replace('/\s*\([^)]*\)/', '', $order->delivery_time->name));
        }else{
            $order->time_slot = 'Fast Delivery';
        }
        if(!empty($order->shipping_location)){
            if($order->shipping_location->region_id){ 
                $order->address =  $order->shipping_location->region->name;
                if(!empty($data)){
                    if(isset($addrss)){
                        $data->currentaddress  =  $addrss->address." , ".$addrss->buliding." , ".$addrss->flat." , ".$addrss->floor_number;
                    }else{ $data->currentaddress  = "address deleted";  }
                }
            }else{
                $order->address =  $order->shipping_location->address;
            }  
        }
        
        $order->coupon = $order->total_amount-$order->offer_total;
        $order->total = $order->offer_total+$order->delivery_charge;
        $order->items_price = $order->offer_total;
        $order->total_amount = $order->total_amount;
        if(!empty($order->coin_payment)) {
            $order->total_amount = $order->total_amount - $order->coin_amount;
        }
        $order->date = Carbon::parse($order['created_at'])->format('d/m/Y, H:i');
        unset($order->delivery_time,$order->shipping_location);
        $ProductOrderItemArray=[];
        foreach ($order['ProductOrderItem'] as $ProductOrderItem){
         $product = json_decode($ProductOrderItem['data'],true);
            //echo "<pre>"; print_r($product); die;
            // if(isset($product['vendor_product'])){ }
         $productdata =$this->vendorProduct->with(['Product'])->where('id',$product['vendor_product']['id'])->first();

          
            $ProductOrderItem['total_price'] = $ProductOrderItem['price'];
            $ProductOrderItem['price'] = $ProductOrderItem['price']/$ProductOrderItem['qty'];
            $ProductOrderItem['image'] = (!empty($productdata['image']['name']))?$productdata['image']['name']:'';
            $ProductOrderItem['name'] = $productdata['name'];
            $ProductOrderItem['data'] = $productdata;
           // $ProductOrderItem['offer_data']= (!empty($product['offer']))?$product['offer']:'';
            $ProductOrderItemArray[] = $ProductOrderItem;
        }

        // echo "<pre>"; print_r($order); die; 
        return $order;
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

    public function update($id)
    {
         try {
                $productOrder = $this->productOrder->find($id);
                $productOrder->update(['order_status'=>'C']);

                $updatestock = $this->ProductOrderItem->where("order_id", $productOrder->id)->get();
                
                //echo "<pre>"; print_r($updatestock); die();

                if(!empty($updatestock)){
                  foreach ($updatestock as $value) {
                    VendorProduct::where(['id'=>$value['vendor_product_id']])->increment('qty',$value['qty']);
                  }
                }


                $wallet_result = $this->user_wallet->where(['order_id'=>$productOrder->id,'type'=>'Order Payment'])->first();
                if(!empty($wallet_result)){                
                  $amount =  $wallet_result->amount;
                  $transaction_type = "CREDIT";
                  $type = "Order Cancelled Cashback";
                  $transaction_id = "DAR".time().$productOrder->id;
                  $description ="Order cancelled cashback refunded to wallet";
                  $json_data = json_encode(['refuser'=>Auth::user()->id]); 
                  $order_id = $productOrder->id; 
                  $user_wallet = Helper::updateCustomerWallet(Auth::user()->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id); 
                }


                $wallet_result = $this->user_wallet->where(['order_id'=>$productOrder->id,'type'=>'Order Total Bonus'])->first();
                if(!empty($wallet_result)){                
                  $amount =  $wallet_result->amount;
                  $transaction_type = "DEBIT";
                  $type = "Order Cancelled Cashback";
                  $transaction_id = "DAR".time().$productOrder->id;
                  $description ="Order cancelled cashback refunded to wallet";
                  $json_data = json_encode(['refuser'=>Auth::user()->id]); 
                  $order_id = $productOrder->id; 
                  $user_wallet = Helper::updateCustomerWallet(Auth::user()->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id); 
                }
                        

                $client = new Client();
                $authkey = env('AUTHKEY');
                $phone_number = Auth::user()->phone_number;
                $senderid = env('SENDERID');

                $client = new Client();
                
               // $msg = urlencode("Order Update! Your order#".$productOrder->order_code." has been cancelled. If you have any query, kindly contact to support. \n\rThanks!!");
               // $message = $msg;
                $message = urlencode("Your order has been cancelled. If you have already paid, refund will be initiated shortly. \n\r If you have any query contact to DARBAAR MART support. Thanks!");
                  
               // $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91");

                $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=1207162028236073658");

                $statusCode = $response->getStatusCode();


                  //$ProductOrderItem = $this->ProductOrderItem->where('order_id',$id);
                 // $ProductOrderItem->update(['product_order_status'=>'Cancelled']);
              
            } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
        }
        
        return redirect('/track-order/'.$id);

    }

     public function invoice($id){
            
       $orders_details =  $this->productOrder->select(['*'])->where(['id'=>$id])->with(['ProductOrderItem'])->first();
        if(isset($orders_details)){
           $user = User::where('id',$orders_details->user_id)->first();
         }
         $data = $this->orderDetails($id);
         //print_r($data); die();
        return view('pages.invoice')->with('orders_details',$orders_details)->with('id',$id)->with('user',$user)->with('data',$data); 
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
    $pdf = PDF::loadView('pages.pdfdownload',['orders_details'=>$orders_details,'id'=>$id]);
    // If you want to store the generated pdf to the server then you can use the store function
    //echo storage_path(); die;
    $pdf->save('/var/www/html/darbar_mart/public/invoices/order_'.$id.'_filename.pdf');
    // Finally, you can download the file using download function
   return  $pdf->download('order-'.$orders_details->order_code.'.pdf');

//    return redirect('/invoice/'.$id);

}

public function reOrder($id){
        $ProductOrderItemArray=[];
        $OrderItemArray=[];
        $input_request = [];
        $order =  ProductOrderItem::select('vendor_product_id','qty')->where(['order_id'=>$id])->get();
        //return  $order;
        if(count($order) > 0){
            foreach ($order as $key => $value) {
                $OrderItemArray[$value->vendor_product_id] = $value->qty;
            } 
        }
        $oldcart = $this->cart->where('user_id',Auth::user()->id)->where('zone_id',Auth::user()->zone_id);
        $oldcart->delete();
        foreach ($OrderItemArray as $vendorProductId => $productQty) {
            $zone_id = Auth::user()->zone_id;
            $qty = Helper::outOfStock($vendorProductId,$zone_id);
            if($qty > 0){
                $input_request['vendor_product_id']= $vendorProductId;
                $input_request['user_id']=Auth::user()->id;
                $input_request['zone_id']=$zone_id;
                if($qty > $productQty || $qty == $productQty ){
                    $input_request['qty']= $productQty;
                }
                if($qty < $productQty){
                     $input_request['qty']= $productQty;
                }
                $cart =   $this->cart->create($input_request);

            }
           
        }
     return redirect('/mycart')->with('Success',"Cart Updated successfully");

       // return $this->showResponse(trans('order.updated_cart'));
    }

    public function orderPayment($order_id) {
        $payment_data = Session::get('payment_data');
      //  echo "<pre>"; print_r($payment_data); die;

        $amount = Session::get('payment_data')['amount'];
        
        if(isset(Session::get('payment_data')['delivery_charge'])) {
         $delivery_charge = Session::get('payment_data')['delivery_charge'];
        }else{
             $delivery_charge = 0;
        }
        
        if(!empty(Session::get('coupon_discount'))){
        $coupon_discount = Session::get('coupon_discount');
          }else{
         $coupon_discount =  0;
         }
        $darbaar_coin_price = Session::get('darbaar_coin_price');
        $amount = $amount + $delivery_charge - $coupon_discount;
        $amount = $amount - $darbaar_coin_price; 
        $email = Session::get('payment_data')['email'];
        $order_id = Session::get('payment_data')['order_id'];
        $phone_number = Session::get('payment_data')['phone_number'];
        return view('pages.order-payment',compact('amount','email','order_id','phone_number'));
    }

}
