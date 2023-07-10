<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Coupon;
use App\Http\Resources\CartResource;
use App\ProductOrder;
use App\ProductOrderItem;
use App\Helpers\Helper;
use App\AppSetting;
use App\FirstOrder;
use App\Helpers\ResponseBuilder;
use App\VendorProduct;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Response;

class CartController extends Controller
{
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

    public function __construct(Request $request,Cart $cart,productOrder $productOrder,FirstOrder $firstorder,VendorProduct $vendorProduct)
    {
        parent::__construct();
        $this->cart = $cart;
        $this->productOrder = $productOrder;
        $this->first_order=$firstorder;
        $this->vendorProduct =  $vendorProduct;
        $this->method=$request->method();
        $this->validationRules = $this->cart->rules($this->method);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lat = $request->header('lat');
        $lng = $request->header('lng');
        $zonedata = $this->getZoneData($lat, $lng);
        $match_in_zone = $zonedata['match_in_zone'];
        $user = Auth::guard('api')->user();
        $AppSetting =AppSetting::select('mim_amount_for_order','mim_amount_for_order_prime','mim_amount_for_free_delivery','mim_amount_for_free_delivery_prime')->first();      
        if(!$user){
            return ResponseBuilder::error("User not found",$this->unauthStatus);
        }
        $data = $user->cart()->with(['vendorProduct.Product.image','vendorProduct.Product.MeasurementClass'])->get();
        $cartTotalArray= Helper::cartTotal($user->id,$user->zone_id);

//         $data= $this->cart->where(['user_id'=>Auth::guard('api')->user()->id])->where(['zone_id'=>Auth::guard('api')->user()->zone_id])->has('vendorProduct')->has('vendorProduct.Product')->with(['vendorProduct.Product.image'])->with(['vendorProduct.Product.MeasurementClass']);
//         $dataAll = $data;
//         $data = $data->get()->toArray();
//         //return $data;
// $cartTotalArray= Helper::cartTotal(Auth::guard('api')->user()->id,Auth::guard('api')->user()->zone_id);
        if($cartTotalArray['offer_price_total'] >= 1000){
            $flag = 0;   
        } else{ 
            $flag = 1; 
        }

        // $order_count = $this->productOrder->where('user_id',Auth::guard('api')->user()->id)->count();
        $order_count = $user->productOrder->count();
        $first_order = $this->first_order->first();
        foreach ($data as $rec){
            if(($order_count == 0) && ($flag==0)){
                if(!empty($first_order)){
                    foreach($first_order->free_product as $fk=>$fv){
                        if($fv==$rec['vendor_product']['product_id']){
                            $rec['vendor_product']['price'] = $rec['vendor_product']['offer_price'] = 0;
                            $rec['is_free_product']=true;
                            $flag=1;
                        }
                    }  
                }
             }else{
                $rec['is_free_product']=false;
            }
        }
//  $cartTotalArray= Helper::cartTotal(Auth::guard('api')->user()->id,Auth::guard('api')->user()->zone_id);
        
        if(!empty($user->membership) && $user->membership_to >= now()){
            if($cartTotalArray['offer_price_total'] >= $AppSetting->mim_amount_for_free_delivery_prime){
                $cartTotalArray['delivery_charge'] = 0;
            }
            $left_amount = $AppSetting->mim_amount_for_free_delivery_prime - $cartTotalArray['offer_price_total'];
  
        }else{
            if($cartTotalArray['offer_price_total'] >= $AppSetting->mim_amount_for_free_delivery){
                $cartTotalArray['delivery_charge'] = 0;
            }
            $left_amount = $AppSetting->mim_amount_for_free_delivery - $cartTotalArray['offer_price_total'];
        }
        if($left_amount>0){
            $delivery_charges_msg = "Shop ₹".$left_amount." more to get Free Delivery";
        }else{
            $delivery_charges_msg="Yay! Free Delivery";
        }
        $response = [
            'cart_list' => CartResource::collection($data),
            'cart_count' => count($data),
            'total_saving' => $cartTotalArray['total_saving'],
            'total_saving_percentage' => $cartTotalArray['total_saving_percentage'],
            'product_price' => $cartTotalArray['offer_price_total'],
            'delivery_charge' => $cartTotalArray['delivery_charge'],
            'delivery_charges_msg' => $delivery_charges_msg,
            'total_mrp' => $cartTotalArray['total'],
            'total_price' => $cartTotalArray['offer_price_total']+$cartTotalArray['delivery_charge'],
            'mim_amount_for_order' => $AppSetting->mim_amount_for_order,
            'mim_amount_for_order_prime' => $AppSetting->mim_amount_for_order_prime,
            'mim_amount_for_free_delivery' => $AppSetting->mim_amount_for_free_delivery,
            'mim_amount_for_free_delivery_prime' => $AppSetting->mim_amount_for_free_delivery_prime
            
        ];
        $response['can_order'] = $match_in_zone;
        $response['match_in_zone'] = $match_in_zone;
        $response['wallet_balence'] = number_format(Auth::guard('api')->user()->wallet_amount,2,'.','');
        return response()->json($response, 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $zone_id = $user->zone_id;
        $validator = Validator::make($request->all(),$this->cart->rules($this->method),$this->cart->messages($this->method));
        $AppSetting =AppSetting::select('mim_amount_for_order','mim_amount_for_free_delivery')->firstOrfail();
      
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->validationStatus);
        }else{
            try {
                $cart= $this->cart->where(['user_id'=>$user->id,'vendor_product_id'=>$request->vendor_product_id])->first();
                $qty = Helper::outOfStock($request->vendor_product_id,$zone_id);
                if($qty < $request->qty && $request->action == 'add'){
                    return ResponseBuilder::error(trans('order.product_out_of_stock'), $this->validationStatus);
                }
                if($cart){
                    if($request->qty == 0){
                        $cart->delete();
                        // $cartTotalArray= Helper::cartTotal($user->id,$user->zone_id);
                        $this->response->cart = new CartResource($cart); 
                        return ResponseBuilder::success($this->response, trans('order.removed_from_cart'), $this->successStatus);
                        // return $cartTotalArray;
                        // return response()->json($response, 200);
                    }

                    $cart->fill($request->only('qty'))->save();

                    $cartTotalArray= Helper::cartTotal($user->id,$user->zone_id);
                    

                    if(!empty($user->membership) && ($user->membership_to >= date('Y-m-d H:i:s'))){
                        if($cartTotalArray['offer_price_total'] >= $AppSetting->mim_amount_for_free_delivery){
                            $cartTotalArray['delivery_charge'] = 0;
                        }
                    }
                    
                    // $cartTotalArray= Helper::cartTotal($user->id,$user->zone_id);
                    // return $cartTotalArray;
                    // $cartTotalArray['cart'] = $cart;
                    $this->response->cart = new CartResource($cart); 
                    return ResponseBuilder::success($this->response, trans('order.updated_in_cart'), $this->successStatus);
                }

                $input_request = $request->all();
                $input_request['zone_id'] = $user->zone_id;

                $cart = $user->cart()->create($input_request);
                // $cartTotalArray= Helper::cartTotal($user->id,$user->zone_id);
                // return $cartTotalArray;
                $this->response->cart = new CartResource($cart); 
                return ResponseBuilder::success($this->response, trans('order.added_in_cart'), $this->successStatus);

            } catch (\Exception $e) {
                return $e;
                // return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
            }
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,$id)
    {

        $validator = Validator::make($request->all(),[
            'qty' => 'required',
            /*'id' => 'required',*/
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponce($validator);
        }else{

            try {
                    $cart = $this->cart->where('id',$id)->where('user_id',Auth::guard('api')->user()->id)->firstOrFail();
                    $qty = Helper::outOfStock($cart->vendor_product_id);

                    if($qty < $request->qty){
                        $out_of_stock_responce['message'] =trans('order.product_out_of_stock');
                        if($request->action == 'add'){
                            return $this->outOfStockResponse($out_of_stock_responce);
                        }

                    }
                    $cart->update($request->only('qty'));

            } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
            }
            return $this->showResponse($cart);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        /*$validator = Validator::make($request->all(),[
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponce($validator);
        }else{*/
            try {
                $cart = $this->cart->where('id',$id)->where('user_id',Auth::guard('api')->user()->id)->firstOrFail();
                $cart->delete();

            } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
            }
            return $this->deletedResponse($cart);
        /*}*/
    }

    public function clearCart()
    {

        try {
            $cart = $this->cart->where('user_id',Auth::guard('api')->user()->id)->where('zone_id',Auth::guard('api')->user()->zone_id);

            $cart->delete();
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e);
        }
        return $this->deletedResponse(trans('site.delete'));

    }
    public function reOrder(Request $request){
        $ProductOrderItemArray=[];
        $OrderItemArray=[];
        $input_request = [];
        $order =  ProductOrderItem::select('vendor_product_id','qty')->where(['order_id'=>$request->order_id])->get();
        //return  $order;
        if(count($order) > 0){
            foreach ($order as $key => $value) {
                $OrderItemArray[$value->vendor_product_id] = $value->qty;
            } 
        }
        $oldcart = $this->cart->where('user_id',Auth::guard('api')->user()->id)->where('zone_id',Auth::guard('api')->user()->zone_id);
 $oldcart->delete();
        foreach ($OrderItemArray as $vendorProductId => $productQty) {
            $zone_id = Auth::guard('api')->user()->zone_id;
            $qty = Helper::outOfStock($vendorProductId,$zone_id);
            if($qty > 0){
                $input_request['vendor_product_id']= $vendorProductId;
                $input_request['user_id']=Auth::guard('api')->user()->id;
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
        return $this->showResponse(trans('order.updated_cart'));
    }


 }
