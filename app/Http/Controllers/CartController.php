<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Offer;
use App\Zone;
use App\Ads;
use App\User;
use App\ZoneTranslation;
use App\CategoryTranslation;
use App\ProductOrder;
use App\Product;
use App\ProductOrderItem;
use App\Helpers\Helper;
use App\AppSetting;
use App\VendorProduct;
use App\Coupon;
use App\FirstOrder;
use App\CoinSettings;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Cart Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles redirecting them to your home screen. 
    |
    */


    /**
     * Where to redirect users before login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

   use RestControllerTrait,ResponceTrait;

    const MODEL = 'App\Cart';
    /**
     * @var Contact
     */
    private $cart;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $validationRules;

  public function __construct(Request $request,Cart $cart,productOrder $productOrder,FirstOrder $firstorder,product $product,Offer $offer,Zone $zone,vendorProduct $vendorProduct, Coupon $coupon)
   {
      parent::__construct();
        $this->cart = $cart;
        $this->offer = $offer;
        $this->zone = $zone;
        $this->product = $product;
        $this->vendorProduct = $vendorProduct;
        $this->coupon = $coupon;
        $this->productOrder = $productOrder;
        
        $this->first_order=$firstorder;
        $this->method=$request->method();
        $this->validationRules = $this->cart->rules($this->method);
        $this->middleware('auth');
    }


    public function mycart(Request $request)
    {   
        $user = Auth::user();
        $zone_id =  $request->session()->get('zone_id');
        if(empty($zone_id)){
            $zone_id =  Auth::user()->zone_id;
            $request->session()->put('zone_id',$zone_id);
            if(empty($zone_id)){
                if(isset($user->lat) && isset($user->lng)){
                    $zonedata = $this->getZoneData($user->lat, $user->lng);
                    $zone_id =  $zonedata['zone_id'];
                    $request->session()->put('zone_id', $zone_id);
                }
            }
        }
       $user = $user->get()->toArray();
       $currusers = Product::where(['show_in_cart_page'=>'1'])->get()->toArray();
            // $curruser = Auth::user();
            // $curruser->id = $id;
            // $curruser->update();
            // $user  = User::select('*');
            // $offer = $offer_arr =  [];
            // $user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);
            // // echo '<pre>';print_r($curruser);exit;
            // $user = $user->get()->toArray();
            foreach($currusers as $kk=>$vv){
              $product[]= $vv['id'];
            }
            $user_id_array = $product_id_array=[];
            $all_offer = $this->vendorProduct->with([
                    'product.MeasurementClass',
                    'product.image','cart'=>function($q) use($zone_id){
                        $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
                    },'wishList'=>function($q){
                        $q->where(['user_id'=>Auth::user()->id]);
                    }])->whereHas('product',function($q){ $q->where('status','1'); }  )->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id')->get();
            foreach($all_offer as $key=>$value){
                $product_id_array[]=$value->product_id;
            }
            $offerProduct = [];
  $offerProduct  =  $this->offerdata($zone_id);
        $data= $this->cart->where(['user_id'=>Auth::user()->id])->where(['zone_id'=>$zone_id])           
        ->with(['vendorProduct','vendorProduct.User'])
        ->whereHas('vendorProduct.Product',function($q){ $q->where('status','1');  })
        ->with(['vendorProduct.Product.image'])
        ->with(['vendorProduct.Product.MeasurementClass'])
        ->with(['vendorProduct.wishList'=>function($q) use($zone_id){ $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]); }]);
        $dataAll = $data;
        $data = $data->get()->toArray();
        $result= [];
        $result2= [];
        foreach ($data as $k=>$rec){
             //echo $rec['vendor_product']['qty']; echo "<br/>";
             if($rec['vendor_product']['qty'] == 0)
             {
                $result2[] = $rec['vendor_product']['id'];
             }
            $rec['user_name'] = $rec['vendor_product']['user']['name'];
            $rec['is_offer'] = false;
            $rec['price'] = number_format($rec['vendor_product']['price'],2, '.', '');
            $rec['offer_price'] = $rec['vendor_product']['price'];
            $rec['product']['image']=$rec['vendor_product']['product']['image']['name'];
   
            $offer = $this->offer->where('id',$rec['vendor_product']['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
            if(!empty($offer)){
                $offer->toArray();
                $rec['offer_data'] = $offer;
                  if($offer['offer_type']=='amount'){
                   $rec['offer_price'] =  $rec['price'] - $offer['offer_value'];
                  }else if($offer['offer_type']=='percentages'){
                  $rec['offer_price'] =  $rec['price'] - ( ( $rec['price'] * $offer['offer_value'] ) / 100 ) ;
                 }
                $rec['price'] = $rec['offer_price'] = number_format($rec['offer_price'],2, '.', '');
            }
            $result[]= $rec;
        }  //echo "<pre>";  print_r($result2); die;
        $cartTotalArray= Helper::cartTotal(Auth::user()->id,$zone_id);

        if(count($result) == 0){
            $request->session()->forget('coupon_discount');
            $request->session()->forget('coupon_text');
        }

        if(!empty($request->session()->get('coupon_discount'))){
            $coupon_discount = $request->session()->get('coupon_discount');
            $coupon_text = $request->session()->get('coupon_text');
        }else{
            $coupon_discount = 0;
            $coupon_text = "";
        }

        $total_price = $cartTotalArray['offer_price_total']+$cartTotalArray['delivery_charge'];
        if($coupon_discount>$total_price)
        {
            $coupon_discount=$total_price;
        }
        if(!empty($coupon_discount)){
          $coupon_per = number_format(((100*$coupon_discount) / $cartTotalArray['total']),2,'.',''); 
        }else{
           $coupon_per = 0;
        }
        $darbaar_coin_price = $this->darbaarCoin($cartTotalArray['offer_price_total']+$cartTotalArray['delivery_charge'] - $coupon_discount);
        // $coin_amount = $user->coin_amount;
        // if($coin_amount > $darbaar_coin_price) {
        //     $darbaar_coin_price = $darbaar_coin_price;
        // } else {
        //     $darbaar_coin_price = 0.00;    
        // }
        $request->session()->put('darbaar_coin_price',$darbaar_coin_price);

        $response = [
            'error'=>false,
            'code' => 0,
            'outOfStock_ids' => implode(",", $result2),
            'cart_list' => $result,
            'cart_count' => count($result),
            'total_saving' => $cartTotalArray['total_saving']+$coupon_discount,
            'total_saving_percentage' => $cartTotalArray['total_saving_percentage']+$coupon_per,
            'product_price' => $cartTotalArray['offer_price_total'],
            'min_amount_for_order' => $cartTotalArray['min_amount_for_order'],
            'min_amount_for_free_delivery' => $cartTotalArray['min_amount_for_free_delivery'],
            'delivery_charge' => $cartTotalArray['delivery_charge'],
            'coupon_discount' => $coupon_discount,
            'coupon_text' => $coupon_text,
            'total_price_amount' => $cartTotalArray['offer_price_total'] - $coupon_discount,
            'total_price' => $cartTotalArray['offer_price_total']+$cartTotalArray['delivery_charge'] - $coupon_discount - $darbaar_coin_price,
            'currency' => $cartTotalArray['currency'],
            'message'=>trans('site.success'),
            'darbaar_coin_price' => $darbaar_coin_price, 
        ];

        // echo "<pre>"; print_r($response); die;
        return view('pages.mycart', ['response' => $response,'offer'=>$product]);
    }
    public function offerdata($zone_id){
$user = User::select('*');
$user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
$user = $user->get()->toArray();
$product_data=[];
$user_id_array=[];
foreach($user as $kk=>$vv){
$user_id_array[] = $vv['id'];
$product_data[$vv['id']] = $this->vendorProduct->where('user_id',$vv['id'])->where('status','1')->get()->toArray();
}
$offer_product_id_array = [];
$offer_products = $this->vendorProduct->with(['Product',
'product.MeasurementClass',
'product.image','cart'=>function($q) use($zone_id){
$q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
},'wishList'=>function($q){
$q->where(['user_id'=>Auth::user()->id]);
}])->whereHas('product',function($q){ $q->where('status','1'); }  )
->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id')->get();              
foreach($offer_products as $offer_product){
$pffer_data = $this->offer->where('id',$offer_product->offer_id)->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
if(!empty($pffer_data)){
$offer_product_id_array[] = $offer_product->product_id;
}
}
$vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
  'cart'=>function($q) use($zone_id){
$q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
},'wishList'=>function($q){
$q->where(['user_id'=>Auth::user()->id]);
}])->whereHas('product',function($q){ $q->where('status','1'); }  )
->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id');

    if(!empty($vendorProduct)){
      $vProduct = $vendorProduct= $vendorProduct->groupBy('product_id')->take(10000)->get();
      $vendorProduct= $vendorProduct->toArray();
    }
   $data=[];
  if(!empty($vendorProduct)){
   foreach ($vendorProduct as $rec){
    $rec= $rec;
    $rec['price'] = number_format($rec['price'],2,'.','');  
    $rec['wish_list'] = isset($rec['wish_list'])?$rec['wish_list']:'';  
    $rec['mrp'] = number_format(!empty($rec['best_price']) ? $rec['best_price']:$rec['price'],2,'.','');   
    $rec['offer_price'] = number_format($rec['price'],2,'.','');   
   
    $rec['offer_data'] =   $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
    if(!empty($ffer_data)){
      $rec['is_offer'] = true;
      $rec['offer_id'] = $rec['offer_id'];
      if($ffer_data->offer_type=='amount'){
       $rec['offer_price'] = $rec['price']- $ffer_data->offer_value;
      }else if($ffer_data->offer_type=='percentages'){
       $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 )) ;                 
      }
       $rec['offer_price'] = number_format( $rec['offer_price'],2,'.','');
       $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $rec['price']:$rec['best_price'],2,'.','');   
      $data[]=$rec;                       
    }     
  }
  unset($vendorProduct);
  $vendorProduct = $data; 
  }

  $data=[];
  if(!empty($vendorProduct)){
  foreach ($vendorProduct as $rec){
  $rec['match_in_zone']=true;
  $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
  unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
  $data[]=$rec;
  }
  unset($vendorProduct);
  $vendorProduct = $data; 
  }
//echo "<pre>"; print_r($vendorProduct); die;
  return $vendorProduct;
 }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addtocart(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(),$this->cart->rules($this->method),$this->cart->messages($this->method));
        if ($validator->fails()) {
            return $this->validationErrorResponce($validator);
        }else{
            try {
            $user = Auth::user();
            $zone_id = $request->session()->get('zone_id');
            if(empty( $zone_id)){
             $zone_id =  Auth::user()->zone_id;
            }
            //echo "<pre>"; print_r($request->all()); die;
                    $cart= $this->cart->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id,'vendor_product_id'=>$request->vendor_product_id])->first();
                    $per_order = $this->vendorProduct->with('product')->where('id',$request->vendor_product_id)->select('*')->first();
                   // echo "<pre>"; print_r($per_order); die;
                    if($request->qty>0){
                        $super_deal_products = Helper::superDealProducts($zone_id);
                        $scart = $this->cart->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id])->whereIN('vendor_product_id',$super_deal_products)->first();
                        if(isset($scart) && !empty($scart)) {
                            $out_of_stock_responce['message'] = 'You are not illegible for this offer';
                            return $this->outOfStockResponse($out_of_stock_responce);
                        } else {
                            $is_first_order_product = Helper::checkFirstOrder($request->vendor_product_id,$zone_id,$user->id);
                            if($is_first_order_product==0) {
                                $out_of_stock_responce['message'] = 'You are not illegible for this offer';
                                return $this->outOfStockResponse($out_of_stock_responce);
                            }
                        }
                    }
                    $qty = Helper::outOfStock($request->vendor_product_id,$zone_id);
                    if($qty<$request->qty){
                            $out_of_stock_responce['message'] = trans('order.product_out_of_stock');
                          return $this->outOfStockResponse($out_of_stock_responce);
                   
                        // $response = [
                        // 'error'=>false,
                        // 'code' => 0,
                        // 'cart' => $cart,
                        // 'product' => $per_order,
                        // 'qty' => (int)$request->qty,
                        // 'per_order'=>$per_order->per_order,
                        // 'message'=>$out_of_stock_responce['message'] 
                        // ];   
                        // return response()->json($response, 200);
                    }

                    if(((int)$request->qty > $per_order->per_order) && !empty($per_order->per_order)){

                        $per_order->qty =  !empty($per_order->per_order)?$per_order->per_order:"0";
                        
                        $out_of_stock_responce['message'] = "Maximum Quantity for order of '".$per_order->product->name."' is ".$per_order->qty;
                         $response = [
                            'error'=>false,
                            'code' => 0,
                            'cart' => $cart,
                            'product' => $per_order,
                            'qty' => (int)$request->qty,
                            'per_order'=>$per_order->per_order,
                            'message'=>$out_of_stock_responce['message'] 
                        ];   
                        return response()->json($response, 200);
                    
                    }
                    if($cart==null){
                        $validator = Validator::make($request->all(),[
                           'qty'=>'required|integer|min:1',
                        ]);
                        if ($validator->fails()) {
                            return $this->validationErrorResponce($validator);
                        }
                        $input_request = $request->all();
                        $input_request['user_id']=Auth::user()->id;
                        $input_request['zone_id']=$zone_id;
                        $cart =   $this->cart->create($input_request);
                        $message = trans('order.added_in_cart');
                    }
                    else{
                         if($request->qty==0){
                            $cart->delete();
                            $cartTotalArray= Helper::cartTotal(Auth::user()->id,$zone_id);
                            $response = [
                                'error'=>false,
                                'code' => 0,
                                'cart_count' => $cartTotalArray['count'],
                                'total_saving' => $cartTotalArray['total_saving'],
                                'total_saving_percentage' => $cartTotalArray['total_saving_percentage'],
                                'product_price' => $cartTotalArray['offer_price_total'],
                                'delivery_charge' => $cartTotalArray['delivery_charge'],
                                'total_price' => $cartTotalArray['offer_price_total']+$cartTotalArray['delivery_charge'],
                                'currency' => $cartTotalArray['currency'],
                                'message'=>trans('order.removed_from_cart'),
                            ];
                            return response()->json($response, 200);

                        }
                        $cart->fill($request->only('qty'))->save();
                        $message = trans('order.updated_cart');
                    }


            } catch (\Exception $e) {  return $e;
                 
                return $this->clientErrorResponse($e);
            }
            $cartTotalArray= Helper::cartTotal(Auth::user()->id,$zone_id);
            $response = [
                'error'=>false,
                'code' => 0,
                'cart' => $cart,
                'product' => $per_order,
                'qty' => (int)$request->qty,
                'per_order'=>$per_order->per_order,
                'cart_count' => $cartTotalArray['count'],
                'total_saving' => $cartTotalArray['total_saving'],
                'total_saving_percentage' => $cartTotalArray['total_saving_percentage'],
                'product_price' => $cartTotalArray['offer_price_total'],
                'delivery_charge' => $cartTotalArray['delivery_charge'],
                'total_price' => $cartTotalArray['offer_price_total']+$cartTotalArray['delivery_charge'],
                'currency' => $cartTotalArray['currency'],
                'message'=>$message,
            ];
            return response()->json($response, 200);
        }
    }
    public function removeOutStock(Request $request)
    {
        $user = Auth::user();
        $zone_id =  $request->session()->get('zone_id');
        if(empty($zone_id)){
            $zone_id =  Auth::user()->zone_id;
            $request->session()->put('zone_id',$zone_id);
            if(empty($zone_id)){
                if(isset($user->lat) && isset($user->lng)){
                    $zonedata = $this->getZoneData($user->lat, $user->lng);
                    $zone_id =  $zonedata['zone_id'];
                    $request->session()->put('zone_id', $zone_id);
                }
            }
        }
       
        $data= $this->cart->where(['user_id'=>Auth::user()->id])->where(['zone_id'=>$zone_id])           
        ->with(['vendorProduct','vendorProduct.User'])
        ->whereHas('vendorProduct.Product',function($q){ $q->where('status','1');  })
        ->with(['vendorProduct.Product.image'])
        ->with(['vendorProduct.Product.MeasurementClass'])
        ->with(['vendorProduct.wishList'=>function($q) use($zone_id){ $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]); }]);
        $dataAll = $data;
        $data = $data->get()->toArray();
        $data['data'] = $data;
        $data['message'] = "Okay Delete All out of stock";
        return $data;
    }
    public function notifyme(Request $request)
    {
        
       $obj = [ 
            'product_id' => $request->vendor_product_id,
            'user_id' => Auth::user()->id,
       ];
       $obj = DB::table('notify_me')->insert($obj);
        $data['message'] = "Okay this notify for me";
        return $data;
    }

  public function getZoneData($lat, $lng)
    {
        $zone_id = '';
        $zoneArray = [];
        $zArray = [];
        $fArray = [];
        $finalArray = [];
        $zonedata = DB::table('zones')->select('id', DB::raw("ST_AsGeoJSON(point) as json"))->where('deleted_at', null)->where('status', '=', '1')->get();
        $json_arr = json_decode($zonedata, true);
        foreach ($json_arr as $zvalue) {
            $zone_id = $zvalue['id'];
            $json = json_decode($zvalue['json']);
            $coordinates = $json->coordinates;
            $new_coordinates = $coordinates[0];
            $lat_array = array();
            $lng_array = array();
            foreach ($new_coordinates as $new_coordinates_value) {
                $lat_array[] = $new_coordinates_value[0];
                $lng_array[] = $new_coordinates_value[1];
            }
            $is_exist = $this->isPointInPolygon($lat, $lng, $lat_array, $lng_array);
            if ($is_exist) {
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                return $data;
            }
        }
        $zone = Zone::where('status', '=', '1')->where('is_default', '=', 1)->withoutGlobalScope(StatusScope::class)->first();
        $zone_id_default = $zone->id;
        $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
        $data['match_in_zone'] = false;
        $data['zone_id'] = $zone_id_default;
        $data['zone_name'] = $zData->name;
        return $data;
    }

    public function isPointInPolygon($latitude, $longitude, $latitude_array, $longitude_array)
    {
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
  /**
     * Apply Prmocode
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function applypromocode(Request $request){
        $validator = Validator::make($request->all(),[
            'promocode'=>'required',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponce($validator);
        }
        try{
            $today = date('Y-m-d');
            $code = $request->promocode;
            ///dd($applypromocode);
            $res = $this->coupon->where(['code' =>$code])->first();
            if(!empty($res)){
                $order_count = $this->productOrder->where('user_id',Auth::user()->id)->where('coupon_code',$request->promocode)->count();

                if($res->to_time < $today){
                    $response = [
                        'error'=>true,
                        'code' => 1,
                        'message'=>"Promocode has expired.",
                    ];
                    return response()->json($response, 201);
                }else if($res->status == 0){
                    $response = [
                        'error'=>true,
                        'code' => 1,
                        'message'=>"Promocode has expired.",
                    ];
                    return response()->json($response, 201);
                }else if($res->number_of_use <= $order_count){
                    $response = [ 
                        'error' =>true ,
                        'code' =>1,
                        'message'=>"This code already used for ".$res->number_of_use." times"
                    ];
                    return response()->json($response, 201);
                }else{
                    /*'percentages','amount'*/
                    $user = Auth::user();
                    $zone_id = $request->session()->get('zone_id');
                    if(empty( $zone_id)){
                        $zone_id =  Auth::user()->zone_id;
                    }
                    $cart_total = Helper::cartTotal($user->id,$zone_id);
                    if($res->coupon_type == 'percentages'){
                        $coupon_discount = number_format((($cart_total['offer_price_total']*$res->coupon_value)/100),2,'.',',');
                    }elseif($res->coupon_type == 'amount'){
                        $coupon_discount = number_format($res->coupon_value,2,'.',',');
                    }else{
                        $coupon_discount = 0;
                    }
                    $request->session()->put('coupon_discount',$coupon_discount);
                    $request->session()->put('coupon_text',$code);
                    //echo $coupon_discount; die();
                    $response = [
                        'error'=>false,
                        'code' => 0,
                        'message'=>"Promocode Applied successfully.",
                    ];
                    return response()->json($response, 200);
                }
            }else{
                $response = [
                    'error'=>true,
                    'code' => 1,
                    'message'=>"Invalid Promocode.",
                ];
                return response()->json($response, 201);
            } 
        }catch (\Exception $e) {  return $e;  
            return $this->clientErrorResponse($e);
        }
    }
public function removepromocode(Request $request){
    $request->session()->forget('coupon_text');
     $request->session()->forget('coupon_discount');
      $response = [
                        'error'=>false,
                        'code' => 0,
                        'message'=>"Promocode Removed Successfully.",
                    ];
                    return response()->json($response, 200); 
}

      public function couponList(){
          $today = date('Y-m-d');
         $coupon = $this->coupon->where('to_time','>=',$today)->where('from_time','<=',$today)->get();
         ///dd($coupon);
         return view('pages.coupon_list')->with(['coupons' => $coupon]);
      }

      public function darbaarCoin($order_total=0) {
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
}

