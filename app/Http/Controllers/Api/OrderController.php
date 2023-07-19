<?php

namespace App\Http\Controllers\Api;

use App\Notifications\AllOrderStatus;
use App\Cart;
use App\Coupon;
use App\User;
use App\VendorProduct;
use App\DeliveryLocation;
use App\DeliveryTime;
use App\Helpers\Helper;
use App\PaymentMode;
use App\PickUpPoint;
use App\Product;
use App\WishLish;
use App\FirstOrder;
use App\AppSetting;
use App\MeasurementClass;
use App\ProductOrder;
use App\ProductVarient;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\ProductOrderItem;

use App\UserWallet;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


use GuzzleHttp\Client;

class OrderController extends Controller
{
    use RestControllerTrait, ResponceTrait, SendsPasswordResetEmails;

    const MODEL = 'App\ProductOrder';
    /**
     * @var Contact
     */
    private $productOrder;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $validationRules;
    protected $cart;

    public function __construct(Request $request, ProductOrder $productOrder, FirstOrder $firstorder, Cart $cart, User $user, MeasurementClass $measurementClass, ProductOrderItem $productOrderItem, Coupon $coupon, Product $product, VendorProduct $vendorProduct, UserWallet $userWallet)
    {
        parent::__construct();
        $this->productOrder = $productOrder;
        $this->cart = $cart;
        $this->user = $user;
        $this->measurementclass = $measurementClass;
        $this->first_order = $firstorder;
        $this->coupon = $coupon;
        $this->method = $request->method();
        $this->validationRules = $this->productOrder->rules($this->method);
        $this->productOrderItem = $productOrderItem;
        $this->vendorProduct = $vendorProduct;
        $this->user_wallet = $userWallet;
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::guard('api')->user()) {
            $orders =  $this->productOrder->select(['id', 'order_status', 'order_code', 'delivery_date', 'delivery_time', 'shipping_location', 'total_amount', 'offer_total', 'delivery_charge', 'created_at'])->where(['user_id' => Auth::guard('api')->user()->id])->orderBy('created_at', 'DESC');
            if ($request->has('type') and $request->type != '') {
                if ($request->type == 'current') {
                    $orders->where('order_status', '!=', 'D')->where('order_status', '!=', 'C')->where('order_status', '!=', 'R');
                }
                if ($request->type == 'past') {

                    $orders->whereIN('order_status', ['D', 'C', 'R']);
                }
            }
            $orders = $orders->get();
            //return $orders;
            foreach ($orders as $order) {
                if (isset($order->delivery_time)) {
                    $order->time_slot = $order->delivery_time->from_time . '-' . $order->delivery_time->to_time;
                } else {
                    $order->time_slot = '';
                }
                // dd($order->created_at);
                if (isset($order->shipping_location->region_id) && !empty($order->shipping_location->region_id)) {
                    $order->address =  $order->shipping_location->region->name;
                } else {

                    if (isset($order->shipping_location->address) && !empty($order->shipping_location->address)) {

                        $order->address =  $order->shipping_location->address;
                    } else {
                        $order->address = "";
                    }
                }
                $order->coupon =  $order->coupon;
                $order->coupon_amount =  $order->coupon_amount;
                $order->total_saving = number_format((($order->total_amount - $order->offer_total - $order->coupon_amount) / 100), 2, '.', '');
                $order->total = $order->offer_total;
                $order->items_price = $order->offer_total - $order->delivery_charge;
                $order->discount =  number_format($order->offer_total - $order->total_amount, 2, '.', '');
                $order->date = Carbon::parse($order['created_at'])->format('d/m/Y, H:i');
                unset($order->delivery_time, $order->shipping_location, $order->offer_total, $order->total_amount);
            }


            return $this->listResponse($orders);
        } else {
            $response = [
                'error' => false,
                'code' => 0,
                'message' => "No Order Found",
            ];
            return response()->json($response, 200);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->delivery_time_id;
        if ($request->payment_mode_id == 1) {
            $order_count = $this->productOrder->where('user_id', Auth::guard('api')->user()->id)->where('payment_mode_id', 1)->whereIn('order_status', ['N', 'O', 'S', 'A', 'U', 'UP'])->count();
            // if ($order_count > 2) {
            //     $res['error'] = true;
            //     $res['code'] = 1;
            //     $res['message'] = "You alerady have 3 orders pending With COD";
            //     return response()->json($res);
            // }
        }
        $AppSetting = AppSetting::select('mim_amount_for_order', 'mim_amount_for_free_delivery', 'mim_amount_for_free_delivery_prime')->firstOrfail();
        $cartRec = $this->cart->has('vendorProduct.Product')->with(
            [
                'vendorProduct.Product.image',
                'vendorProduct.offer',
            ]
        )->where(['user_id' => Auth::guard('api')->user()->id, 'zone_id' => Auth::guard('api')->user()->zone_id])->get();
        //return $cartRec;
        $result = [];
        $error = 0;
        try {
            if ($cartRec->count() == '0') {
                $res['error'] = true;
                $res['code'] = 1;
                $res['message'] = "No Product in cart for this address";
                return response()->json($res);
            }
            foreach ($cartRec as $Rec) {
                $trmpArray = [];
                if ($Rec['qty'] <= $Rec['vendorProduct']['qty']) {
                    $trmpArray['price'] = $Rec['vendorProduct']['price'] * $Rec['qty'];
                    $trmpArray['qty'] = $Rec['qty'];
                    $trmpArray['vendor_product_id'] = $Rec['vendorProduct']['id'];
                    $trmpArray['offer_total'] = $Rec['vendorProduct']['offer_price'] * $Rec['qty'];
                    $trmpArray['message'] = $Rec['vendorProduct']['Product']['name'] . trans('site.out_of_stock') . '' . trans('site.max_qty') . '' . $Rec['qty'];
                    $trmpArray['is_offer'] = 'no';
                    $trmpArray['offer_data'] = json_encode(array());
                    if ($Rec['vendorProduct']['is_offer']) {
                        $trmpArray['offer_value'] = $Rec['vendorProduct']['offer']['offer_value'];
                        $trmpArray['offer_type'] = $Rec['vendorProduct']['offer']['offer_type'];
                        $trmpArray['offer_data'] = json_encode($Rec['vendorProduct']['offer']);
                        $trmpArray['is_offer'] = 'yes';
                        $trmpArray['offer_total'] = $Rec['vendorProduct']['offer_price'] * $Rec['qty'];
                    }
                    $ordercount = $this->productOrder->where('user_id', Auth::guard('api')->user()->id)->count();
                    $flag = 0;
                    $first_order = $this->first_order->where('status', '1')->first();
                    if (($ordercount == 0) && ($flag == 0)) {
                        if (!empty($first_order)) {
                            foreach ($first_order->free_product as $fk => $fv) {
                                // echo  $fv."== ".$product['vendor_product']['product']['id'];
                                if ($fv == $Rec['vendorProduct']['Product']['id']) {
                                    $trmpArray['price'] = $trmpArray['offer_total'] = 0;
                                    $flag = 1;
                                }
                            }
                        }
                    }
                    $newstock = $this->measurementclass->where(['id' => $Rec['vendorProduct']['Product']['measurement_class']])->get()->toArray();

                    $newstockArray = [];
                    if (isset($newstock)) {
                        foreach ($newstock[0]['translations'] as $value) {
                            //$newstockArray[[$key]['locale']][] =$value;
                            $newstockArray[$value['locale']] = $value;
                        }
                    }
                    //print_r($newstockArray[App::getLocale()]['name']);die;                  
                    $Rec['vendorProduct']['measurementclass'] = isset($newstockArray[App::getLocale()]['name']) ? $newstockArray[App::getLocale()]['name'] : '';
                    $trmpArray['data'] = $Rec;
                    $trmpArray['status'] = 1;
                } else {
                    $trmpArray['total'] = 0;
                    $trmpArray['offer_total'] = 0;
                    $trmpArray['message'] = $Rec['vendorProduct']['Product']['name'] . trans('site.out_of_stock') . '' . trans('site.max_qty') . '' . $Rec['qty'];
                    $trmpArray['status'] = 0;
                    $error = 1;
                }
                $result[] = $trmpArray;
            }
        } catch (\Exception $e) {
            return $this->notFoundResponse($e);
        }
        if ($error) {
            return $this->outOfStockResponse(collect($result)->where('status', '=', 0)->first());
        } else {
            DB::beginTransaction();
            try {
                $delivery_charge = Auth::guard('api')->user()->zone->delivery_charges ?? 0;
                $tax = 0;
                /*actual total price  without offer value*/
                $sub_total = collect($result)->sum('price');
                /*actual total  without offer value*/
                $offer_total = collect($result)->sum('offer_total');

                $input = [];
                $input['is_membership'] = 'N';
                //echo Auth::guard('api')->user()->membership." and ";
                if (!empty(Auth::guard('api')->user()->membership) && (Auth::guard('api')->user()->membership_to >= date('Y-m-d H:i:s'))) {
                    if ($offer_total >= $AppSetting->mim_amount_for_free_delivery_prime) {
                        $delivery_charge = 0;
                    } else {
                        $delivery_charge = $delivery_charge;
                    }
                    $input['is_membership'] = 'Y';
                } else {
                    if ($offer_total >= $AppSetting->mim_amount_for_free_delivery) {
                        $delivery_charge = 0;
                    } else {
                        $delivery_charge = $delivery_charge;
                    }
                    $input['is_membership'] = 'N';
                }


                $input['user_id'] = Auth::guard('api')->user()->id;
                $input['zone_id'] = Auth::guard('api')->user()->zone_id;
                $input['vendor_id'] = null;
                $input['shopper_id'] = null;
                $input['driver_id'] = null;
                $input['order_status'] = 'N';
                $input['cart_id'] = json_encode(array());
                if ($request->delivery_time_id == 'fast_delivery') {
                    $time = date('h');
                    $fast_delivery = date('+3h', strtotime($time));
                    $order_code =  Helper::orderCode($request->delivery_date, Auth::guard('api')->user()->zone_id, '', $fast_delivery);
                } elseif($request->delivery_time_id == 'in_store_pickup' || $request->delivery_time_id == 'standard_delivery'){
                    $order_code =  Helper::orderCode($request->delivery_date, Auth::guard('api')->user()->zone_id, null);
                }else {
                    $order_code =  Helper::orderCode($request->delivery_date, Auth::guard('api')->user()->zone_id, $request->delivery_time_id);
                }
                $input['order_code'] = str_replace(" ", "", $order_code);
                /* modified by sonu*/
                /*if($request->delivery_time_id=='fast_delivery'){
                  $input['shipping_location'] = 'Fast Delivery';
                }else{ 
                  $shipping_location = DeliveryLocation::with(['region'])->findOrFail($request->shipping_location_id);
                  $input['shipping_location'] = $shipping_location->toJson();
                }*/

                $shipping_location = DeliveryLocation::with(['region'])->findOrFail($request->shipping_location_id);
                $input['shipping_location'] = $shipping_location->toJson();

                /*modified by sonu*/

                $to_day = Carbon::createFromFormat('Y-m-d', $request->delivery_date)->format('l');
                $today_data = Auth::guard('api')->user()->zone->weekPackage->$to_day->getSlotTimes()->first(function ($today_data) use ($request, $to_day) {
                    $today_data['name'] = $to_day;
                    return $today_data->id == $request->delivery_time_id;
                });

                /*added by sonu*/
                if ($request->delivery_time_id == 'fast_delivery' || $request->delivery_time_id == 'in_store_pickup' || $request->delivery_time_id == 'standard_delivery') {
                    $input['delivery_time'] = null;
                } else {
                    $input['delivery_time'] = json_encode($today_data, true);
                }
                /*added by sonu*/

                //delivery date 
                if($request->delivery_time_id == 'fast_delivery'){
                    $input['delivery_date'] = Carbon::now()->format('Y-m-d');
                }else if($request->delivery_time_id == 'in_store_pickup' || $request->delivery_time_id == 'standard_delivery'){
                    $input['delivery_date'] = Carbon::tomorrow()->format('Y-m-d');
                }else{
                    $input['delivery_date'] = $request->delivery_date;
                }
                // $input['delivery_time'] = json_encode($today_data, true);
                $input['delivery_charge'] = $delivery_charge;
                $input['tax'] = $tax;
                $input['total_amount'] = $sub_total + $delivery_charge;
                //$input['total_amount'] = $sub_total;
                //$input['offer_total'] = $offer_total;
                $input['offer_total'] = $offer_total + $delivery_charge;
                $input['delivery_time_id'] = $request->delivery_time_id;
                // $input['delivery_date'] = $request->delivery_date;
                $input['payment_mode_id'] = $request->payment_mode_id;
                $input['delivery_boy_tip'] = $request->delivery_boy_tip ?? 0;
                $input['delivery_instruction'] = $request->delivery_instruction ?? "";
                $input['delivery_type'] = gettype($request->delivery_time_id) == 'integer' ? 'normal' : $request->delivery_time_id;


                $carttototal =   Helper::cartTotal(Auth::guard('api')->user()->id, Auth::guard('api')->user()->zone_id);
                $input['offer_total'] = $carttototal['offer_price_total'] + $delivery_charge;

                if (isset($request->coupon)) {
                    $coupondata = $this->coupon->find($request->coupon);

                    if (!empty($coupondata)) {
                        $input['coupon_amount'] = $request->coupon_amount;
                        $input['coupon_code'] = $coupondata->code;
                        $input['total_amount'] =  $input['total_amount'] - $input['coupon_amount'];
                        $input['offer_total'] = $input['offer_total'] - $input['coupon_amount'];
                    }
                }
                $wallet_flag = 0;
                if ($request->payment_mode_id == 2) {
                    $input['transaction_id'] = $request->transaction_id;
                    $input['transaction_status'] = '1';
                    $input['online_payment'] =  $request->online_payment;
                } else if ($request->payment_mode_id == 3) {
                    if ($request->wallet_payment <= Auth::guard('api')->user()->wallet_amount) {
                        $input['transaction_id'] = null;
                        $input['transaction_status'] = '1';
                        $input['wallet_payment'] = $request->wallet_payment;
                        $wallet_flag = 1;
                    } else {
                        $res = ['error' => true, 'code' => 5, 'message' => 'insufficient wallet amount i.e.' . Auth::guard('api')->user()->wallet_amount . ' INR'];
                        return response()->json($res);
                    }
                } else {
                    $input['transaction_id'] = null;
                    $input['transaction_status'] = '0';
                }


                $payment_mode_id =  explode(',', $request->payment_mode_id);
                if (count($payment_mode_id) > 1) {
                    $credit_bal = $request->wallet_payment + $request->online_payment;

                    if ((string)$credit_bal !== (string)$input['offer_total']) {
                        $res = ['error' => true, 'code' => 4, 'message' => 'Please select amount properly'];
                        return response()->json($res);
                    }
                    if ($payment_mode_id[0] == 2 || $payment_mode_id[1] == 2) {
                        $input['transaction_id'] = $request->transaction_id;
                        $input['transaction_status'] = '1';
                        $input['online_payment'] = $request->online_payment;
                    } else if ($payment_mode_id[0] == 3 || $payment_mode_id[1] == 3) {
                        if ($request->wallet_payment <= Auth::guard('api')->user()->wallet_amount) {
                            $input['transaction_id'] = null;
                            $input['transaction_status'] = '1';
                            $input['wallet_payment'] =  $request->wallet_payment;
                            $wallet_flag = 1;
                        } else {
                            $res = ['error' => true, 'code' => 5, 'message' => 'insufficient wallet amount i.e.' . Auth::guard('api')->user()->wallet_amount . ' INR'];
                            return response()->json($res);
                        }
                    }
                }  //echo "<pre>"; print_r($input); die;
                // dd($input);
                $order = $this->productOrder->create($input);
                $order->ProductOrderItem()->createMany($result);
                $data  = $this->orderDetails($order->id);
                if ($order->offer_total >= 2500) {
                    /*$customer_amount = Auth::guard('api')->user()->wallet_amount;
                    $userdata = User::find(Auth::guard('api')->user()->id);
                    $userdata->wallet_amount = $userdata->wallet_amount + 100 ;
                    $userdata->save();*/
                    // comment for stop cashback by Abhishek Bhatt//
                    /*$transaction_type = 'CREDIT';
                    $transaction_id = rand('000000','999999');
                    $type = "Order Total Bonus";
                    $amount = 100;
                    $description = "This is a order refund entry for order code - ".$order->order_code;
                    $json_data = json_encode(['order_id'=>$order->id,'order_code'=>$order->order_code]);
                    $order_id = $order->id;
                    Helper::updateCustomerWallet(Auth::guard('api')->user()->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id);*/
                }

                if ($wallet_flag == 1) {
                    if ($request->wallet_payment > 0) {
                        $customer_id = Auth::guard('api')->user()->id;
                        $transaction_type = 'DEBIT';
                        $transaction_id = rand('000000', '999999');
                        $type = "Order Payment";
                        $amount = $request->wallet_payment;
                        $description = "This is a order payment entry for order code - " . $order->order_code;
                        $json_data = json_encode(['order_id' => $order->id, 'order_code' => $order->order_code]);
                        $order_id = $order->id;
                        Helper::updateCustomerWallet($customer_id, $amount, $transaction_type, $type, $transaction_id, $description, $json_data, $order_id);
                    }
                }
                $shopper_id = $data->shopper_id;
                $driver_id = $data->driver_id;
                foreach ($result as $res) {
                    $wishList = WishLish::updateOrCreate(['vendor_product_id' => $res['vendor_product_id'], 'user_id' => Auth::guard('api')->user()->id, 'zone_id' => Auth::guard('api')->user()->zone_id]);
                    $wishList->save();
                }
                /*send notification to customer*/
                $user_id_array1 = User::where('id', Auth::guard('api')->user()->id)->select('id', 'device_type', 'device_token', 'name')->get();
                $userData = User::where('id', '=', Auth::guard('api')->user()->id)->select('device_token')->get();
                $user_id_array = collect($userData)->pluck('device_token');
                //echo "<pre>"; print_r($shopperArray); die;
                $dataArray = [];
                $dataArray['type'] = 'Order';
                $dataArray['product_type'] = 'New';
                $dataArray['title'] = 'New Order';
                $dataArray['body'] = trans('order.order_confirmed') . $order->order_code;
                $device_type = $user_id_array1[0]->device_type;

                /*notification to customer code end*/
                /*send notification to shopper and driver*/

                $shopperData = User::whereIn('id', [$shopper_id, $driver_id])->select('id', 'device_type', 'device_token')->get();
                // dd($shopperData, $shopper_id, $driver_id);
                if($shopperData){
                    $shopper_id_array = collect($shopperData)->where('id', $shopper_id)->pluck('device_token');
                    $driver_id_array = collect($shopperData)->where('id', $driver_id)->pluck('device_token');
                    $shopperArray = [];
                    $shopperArray['type'] = 'Order';
                    $shopperArray['product_type'] = 'New';
                    $shopperArray['title'] = 'New order placed';
                    $shopperArray['body'] = trans('order.create_success_ordercode') . $order->order_code;
                    $shopper_device_type_array = $shopperData->where('id', $shopper_id)->pluck('device_type');
                    $shopper_device_type = $shopper_device_type_array[0];
    
                    $driver_device_type_array = $shopperData->where('id', $driver_id)->pluck('device_type');
                    $driver_device_type = $driver_device_type_array[0];
                    //echo "<pre>"; print_r($dataArray);
                    // echo "<pre>"; print_r($shopperArray); //die;
                    //return $shopper_device_type;
                    //customer notification
                    //Helper::sendNotification($user_id_array, $dataArray, $device_type);
                    //shopper notifiction
                   // Helper::sendNotification($shopper_id_array, $shopperArray, $shopper_device_type);
                    //driver notifiction
                  //  Helper::sendNotification($driver_id_array, $shopperArray, $driver_device_type);
                    /*send notification to shopper and driver*/
                    /*admin notification start*/
                    // send notification using the "user" model, when the user receives new message
                }
                $senderName = $user_id_array1[0]->name;

                if ($request->delivery_time_id == 'fast_delivery' || $request->delivery_time_id == 'in_store_pickup' || $request->delivery_time_id == 'standard_delivery') {
                } else {
                    $deliveryTime = Helper::getDeliveryTimeById($request->delivery_time_id);
                }
                if (isset($deliveryTime)) {
                    $message = '#' . $order->order_code . trans('order.new_order') . ' ' . $deliveryTime->from_time . '-' . $deliveryTime->to_time;
                    //return $message;
                } else {
                    $message = '#' . $order->order_code . trans('order.new_order');
                }
                $type = 'new order';
                $order->user->notify(new AllOrderStatus($order, $senderName, $message, $type));

                /*end admin notification*/
                //echo json_encode($result);die;
                foreach ($result as $res) {
                    VendorProduct::where(['id' => $res['vendor_product_id']])->decrement('qty', $res['qty']);
                }
                $this->cart->has('vendorProduct.Product')->where(['user_id' => Auth::guard('api')->user()->id, 'zone_id' => Auth::guard('api')->user()->zone_id])->delete();
                DB::commit();
                return $this->listResponse($data);
            } catch (\Exception $e) {
                return $e;
                DB::rollBack();
                return $this->clientErrorResponse($e);
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
        return $this->listResponse($this->orderDetails($id));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkout(Request $request)
    {
        if (is_array($request->cart_id)) {
            $request_cart_ids = $request->cart_id;
        } else {
            $request_cart_ids = json_decode($request->cart_id);
        }

        $cartRec = $this->cart->has('vendorProduct.Product')->with(['vendorProduct'])->whereIn('id', $request_cart_ids)->get();
        // dd($cartRec);
        $cart_ids =  collect($request_cart_ids);
        $cartRecIds = $cartRec->pluck('id');

        $diff = $cart_ids->diff($cartRecIds);

        $result = [];
        $error = 0;

        if (!count($diff)) {
            try {
                foreach ($cartRec as $Rec) {
                    $trmpArray = [];
                    if ($Rec['qty'] <= $Rec['vendorProduct']['qty']) {
                        $trmpArray['total'] = $Rec['vendorProduct']['price'] * $Rec['qty'];
                        $trmpArray['offer_price_total'] = $Rec['vendorProduct']['offer_price'] * $Rec['qty'];
                        $trmpArray['message'] = $Rec['vendorProduct']['Product']['name'] . trans('site.out_of_stock') . '' . trans('site.max_qty') . '' . $Rec['qty'];
                        $trmpArray['status'] = 1;
                    } else {
                        $trmpArray['total'] = 0;
                        $trmpArray['offer_price_total'] = 0;
                        $trmpArray['message'] = $Rec['vendorProduct']['Product']['name'] . trans('site.out_of_stock') . '' . trans('site.max_qty') . '' . $Rec['qty'];
                        $trmpArray['status'] = 0;
                        $error = 1;
                    }

                    $result[] = $trmpArray;
                }
            } catch (\Exception $e) {
                return $this->notFoundResponse($e);
            }
        } else {
            return $this->deletedResponse(trans('order.deleted_by_admin'), 0);
        }

        if ($error) {

            return $this->outOfStockResponse(collect($result)->where('status', '=', 0)->first());
        } else {
            try {
                $shipping_id = Auth::guard('api')->user()->deliveryLocation()->get();
            } catch (\Exception $e) {
                $shipping_id = [];
            }

            $delivery_charge = 10;
            $tex = 20;
            $sub_total = collect($result)->sum('total');
            $offer_price_total = collect($result)->sum('offer_price_total');
            $data = [
                'shipping_address' => $shipping_id,
                'cart_detail' => [
                    'delivery_charge' => $delivery_charge,
                    'payable_amount' => $sub_total + $delivery_charge + $tex,
                    'sub_total' => $sub_total,
                    'offer_price_total' => $offer_price_total,
                    'tax' => $tex
                ],
                "payment_mode" => PaymentMode::all(),
            ];
            return $this->listResponse($data);
        }
    }

    public function orderDetails($id)
    {

        $order =  $this->productOrder->with(['VendorProduct', 'VendorProduct.Product', 'VendorProduct.Product.image', 'VendorProduct.Product.MeasurementClass'])->select(['id', 'user_id', 'is_membership', 'order_status', 'order_code', 'delivery_date', 'coupon_code', 'coupon_amount', 'promo_discount', 'delivery_time', 'shipping_location', 'total_amount', 'offer_total', 'payment_mode_id', 'wallet_payment', 'delivery_charge', 'created_at', 'shopper_id', 'driver_id'])->where(['id' => $id])->with(['ProductOrderItem'])->first();
        //  echo "<pre>"; print_r($order); die;
        if (!empty($order->delivery_time)) {
            $order->time_slot = trim(preg_replace('/\s*\([^)]*\)/', '', $order->delivery_time->name));
        } else {
            $order->time_slot = 'Fast Delivery';
        }
        if (!empty($order->shipping_location)) {
            if ($order->shipping_location->region_id) {
                $order->address =  $order->shipping_location->region->name;
            } else {
                $order->address =  $order->shipping_location->address;
            }
        }

        $order->coupon = $order->coupon_code;
        $order->coupon_amount = $order->coupon_amount;
        $order->product_discount = number_format(($order->total_amount - $order->offer_total), 2, '.', '');
        $order->discount = number_format(($order->total_amount - $order->offer_total + $order->coupon_amount), 2, '.', '');

        $saving = $order->total_amount - $order->offer_total + $order->coupon_amount;
        $total_saving =   (($saving) /  $order->total_amount) * 100;
        $order->total_saving = number_format($total_saving, 2, '.', '');

        $order->total = $order->offer_total;
        $order->items_price = $order->total_amount + $order->coupon_amount  - $order->delivery_charge;
        $order->date = Carbon::parse($order['created_at'])->format('d/m/Y, H:i');
        unset($order->delivery_time, $order->shipping_location, $order->offer_total, $order->total_amount);
        $ProductOrderItemArray = [];

        foreach ($order['ProductOrderItem'] as $ProductOrderItem) {
            $product = json_decode($ProductOrderItem['data'], true);

            $flag = 0;
            $order_count = $this->productOrder->where('user_id', $order->user_id)->count();
            //echo $order->user_id; die;
            $first_order = $this->first_order->first();

            //$ProductOrderItem['total_price'] = $ProductOrderItem['price'];
            $data = json_decode($ProductOrderItem['data']);
            //$ProductOrderItem['price'] = number_format($data->vendor_product->offer_price,2,'.','');  
            //$ProductOrderItem['mrp'] = number_format($data->vendor_product->price,2,'.','');
            //$ProductOrderItem['total_price'] = number_format($data->vendor_product->offer_price * $ProductOrderItem['qty'],2,'.','');



            $ProductOrderItem['mrp'] = number_format($product['vendor_product']['price'], 2, '.', '');
            //number_format($ProductOrderItem['price']/$ProductOrderItem['qty'],2,'.','');
            if ($ProductOrderItem['is_offer'] == 'yes') {
                if ($ProductOrderItem['offer_type'] == 'percentages') {
                    $offer =  ($ProductOrderItem['mrp'] * $ProductOrderItem['offer_value']) / 100;
                    $ProductOrderItem['price'] = number_format($ProductOrderItem['mrp'] - $offer, 2, '.', '');
                } else if ($ProductOrderItem['offer_type'] == 'amount') {
                    $ProductOrderItem['price'] = number_format($ProductOrderItem['mrp'] - $ProductOrderItem['offer_value'], 2, '.', '');
                }

                $ProductOrderItem['price'] =  number_format($ProductOrderItem['price'], 2, '.', '');
            } else {
                $ProductOrderItem['price'] = number_format($ProductOrderItem['mrp'], 2, '.', '');
            }

            $ProductOrderItem['total_price'] = number_format(($ProductOrderItem['price'] * $ProductOrderItem['qty']), 2, '.', '');

            if (($order_count == 0) && ($flag == 0)) {
                foreach ($first_order->free_product as $fk => $fv) {
                    // echo  $fv."== ".$product['vendor_product']['product']['id'];
                    if ($fv == $product['vendor_product']['product']['id']) {
                        $ProductOrderItem['price'] = $ProductOrderItem['mrp'] = $ProductOrderItem['total_price'] = 0;
                        $flag = 1;
                    }
                }
            }
            $image = "";
            $productData = Product::with(['image', 'MeasurementClass'])->find($product['vendor_product']['product']['id']);
            // echo "<pre>"; print_r($productData); die;
            if (!empty($product['vendor_product']['product']['image'])) {
                $image = $product['vendor_product']['product']['image']['name'];
            } else if (!empty($productData->image)) {
                $image = $productData->image->name;
            } else {
                $image = url('store/app/public/upload/160448889487.png');
            }

            $ProductOrderItem['image'] = $image;
            $ProductOrderItem['name'] = $product['vendor_product']['product']['name'];
            $ProductOrderItem['data'] = json_decode($ProductOrderItem['data']);
            $ProductOrderItem['offer_data'] = json_decode($ProductOrderItem['offer_data']);
            $ProductOrderItemArray[] = $ProductOrderItem;
        }
        //echo "<pre>"; print_r($ProductOrderItemArray); die;
        return $order;
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $cart = $this->productOrder->findOrFail($id);

            $cart->update(['order_status' => 'C']);

            $updatestock = $this->ProductOrderItem->where("order_id", $cart->id)->get();

            //print_r($updatestock); die();

            if (!empty($updatestock)) {
                foreach ($updatestock as $value) {
                    $vendorProduct = $this->vendorProduct->where('id', $value->vendor_product_id)->first();
                    $vendorProduct->update(['qty' => DB::raw('qty+' . $value->qty)]);
                }
            }


            $wallet_result = $this->user_wallet->where(['order_id' => $cart->id])->first();

            if (!empty($wallet_result)) {
                $amount =  $wallet_result->amount;
                $transaction_type = "CREDIT";
                $type = "Order Cancelled Cashback";
                $transaction_id = "DAR" . time() . $cart->id;
                $description = "Order cancelled cashback refunded to wallet";
                $json_data = json_encode(['order_id' => $cart->id]);
                $order_id = $cart->id;
                $user_wallet = Helper::updateCustomerWallet(Auth::guard('api')->user()->id, $amount, $transaction_type, $type, $transaction_id, $description, $json_data, $order_id);
            }

            $client = new Client();
            $authkey = env('AUTHKEY');
            $phone_number = Auth::guard('api')->user()->phone_number;
            $senderid = env('SENDERID');

            $client = new Client();

            //  $msg = urlencode("Order Update! Your order#".$cart->order_code." has been cancelled. If you have any query, kindly contact to support. \n\rThanks!!");
            $message = urlencode("Your order has been cancelled. If you have already paid, refund will be initiated shortly. \n\r If you have any query contact to DARBAAR MART support. Thanks!");
            //$message = $msg;

            // $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91");

            $response = $client->request('GET', "http://login.yourbulksms.com/api/sendhttp.php?authkey=" . $authkey . "&mobiles=" . $phone_number . "&message=" . $message . "&sender=" . $senderid . "&route=4&country=91&DLT_TE_ID=1207162028236073658");

            $statusCode = $response->getStatusCode();
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e);
        }
        return $this->listResponse($this->orderDetails($id));
    }
    public function statusUpdate(Request $request)
    {

        if (isset($request->id) && !empty($request->id)) {

            try {
                $cart = $this->productOrder->findOrFail($request->id);
                $shopper_id = $cart->shopper_id;
                $driver_id = $cart->driver_id;
                $cart->update(['order_status' => 'C']);

                $updatestock = $this->productOrderItem->where("order_id", $cart->id)->get();

                //print_r($updatestock); die();

                if (!empty($updatestock)) {
                    foreach ($updatestock as $value) {
                        $vendorProduct = $this->vendorProduct->where('id', $value->vendor_product_id)->first();
                        $vendorProduct->update(['qty' => DB::raw('qty+' . $value->qty)]);
                    }
                }


                $wallet_result = $this->user_wallet->where(['order_id' => $cart->id, 'type' => 'Order Payment'])->first();

                if (!empty($wallet_result)) {
                    $amount =  $wallet_result->amount;
                    $transaction_type = "CREDIT";
                    $type = "Order Cancelled Cashback";
                    $transaction_id = "DAR" . time() . $cart->id;
                    $description = "Order cancelled cashback refunded to wallet";
                    $json_data = json_encode(['order_id' => $cart->id]);
                    $order_id = $cart->id;
                    $user_wallet = Helper::updateCustomerWallet(Auth::guard('api')->user()->id, $amount, $transaction_type, $type, $transaction_id, $description, $json_data, $order_id);
                }

                $wallet_result = $this->user_wallet->where(['order_id' => $cart->id, 'type' => 'Order Total Bonus'])->first();

                if (!empty($wallet_result)) {
                    $amount =  $wallet_result->amount;
                    $transaction_type = "DEBIT";
                    $type = "Order Cancelled Cashback";
                    $transaction_id = "DAR" . time() . $cart->id;
                    $description = "Order cancelled cashback refunded to wallet";
                    $json_data = json_encode(['order_id' => $cart->id]);
                    $order_id = $cart->id;
                    $user_wallet = Helper::updateCustomerWallet(Auth::guard('api')->user()->id, $amount, $transaction_type, $type, $transaction_id, $description, $json_data, $order_id);
                }

                $user = User::findOrFail($cart->user_id);

                if ($user) {
                    $client = new Client();
                    $authkey = env('AUTHKEY');
                    $phone_number = $user->phone_number;
                    $senderid = env('SENDERID');
                    //$message="Your OTP for Darbaar Mart is ".$otp_details->otp;

                    $message = urlencode("Your order has been cancelled. If you have already paid, refund will be initiated shortly. \n\r If you have any query contact to DARBAAR MART support. Thanks!");

                    // echo "http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message='".$message."'&sender=".$senderid."&route=1&country=0"; die;

                    $response = $client->request('GET', "http://login.yourbulksms.com/api/sendhttp.php?authkey=" . $authkey . "&mobiles=" . $phone_number . "&message=" . $message . "&sender=" . $senderid . "&route=4&country=91&DLT_TE_ID=1207162028236073658");
                    $statusCode = $response->getStatusCode();
                }


                /*send notification code to shopper*/
                $shopperData = User::whereIn('id', [$shopper_id, $driver_id])->select('id', 'device_type', 'device_token')->get();

                $shopper_id_array = collect($shopperData)->where('id', $shopper_id)->pluck('device_token');
                $driver_id_array = collect($shopperData)->where('id', $driver_id)->pluck('device_token');
                //return $shopper_id_array;
                $shopperArray = [];
                $shopperArray['type'] = 'Order';
                $shopperArray['product_type'] = 'Cancel';
                $shopperArray['title'] = 'Cancel Order';
                $shopperArray['body'] = trans('order.order_cancel') . $cart->order_code;
                $shopper_device_type_array = $shopperData->where('id', $shopper_id)->pluck('device_type');
                $shopper_device_type = $shopper_device_type_array[0];

                $driver_device_type_array = $shopperData->where('id', $driver_id)->pluck('device_type');
                $driver_device_type = $driver_device_type_array[0];

                //Helper::sendNotification($shopper_id_array ,$shopperArray, $shopper_device_type);
                /*send notification code to shopper*/
                /*send notification code to customer*/
                $user_id_array1 = User::where('id', Auth::guard('api')->user()->id)->select('id', 'device_type', 'device_token')->get();
                $userData = User::where('id', '=', Auth::guard('api')->user()->id)->select('device_token')->get();
                $user_id_array = collect($userData)->pluck('device_token');
                //echo "<pre>"; print_r($user_id_array); die;
                $dataArray = [];
                $dataArray['type'] = 'Order';
                $dataArray['product_type'] = 'Cancel';
                $dataArray['title'] = 'Cancel Order';
                $dataArray['body'] = trans('order.order_cancel') . $cart->order_code;
                $device_type = $user_id_array1[0]->device_type;

                // print_r($driver_device_type); die();
                //shopper notifiction
                //Helper::sendNotification($shopper_id_array, $shopperArray, $shopper_device_type);
                //driver notifiction
                //Helper::sendNotification($driver_id_array, $shopperArray, $driver_device_type);
                //customer notifiction 
                //Helper::sendNotification($user_id_array, $dataArray, $device_type);
                /*send notification code to customer*/

                return $this->showResponse(trans('order.order_cancel'));
            } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
            }
        }
        //return $this->listResponse($this->orderDetails($id));

    }




    /************************************* driver order List*****************/



    public function driverOrderList(Request $request)
    {

        if ($request->type == 1) {
            $orders =  $this->productOrder->where(['driver_id' => $request->user_id])->where('delivery_date', '>', date('Y-m-d'))->with('ProductOrderItem')->get();
            $neworderList = array();
            foreach ($orders as $order) {


                $neworder['delivery_time_slot'] = $order->delivery_time->from_time . "-" . $order->delivery_time->to_time;
                $neworder['delivery_time_slot']['user_name'] = $order->shipping_location->name;
                $neworder['delivery_time_slot']['order_id'] = $order->order_code;
                $neworder['delivery_time_slot']['status'] = Helper::$order_status[$order->order_status];
                $neworder['delivery_time_slot']['items'] = count($order->ProductOrderItem);
                $neworder['delivery_time_slot']['price'] = $order->total_amount;


                $neworderList[] = $neworder;
            }
        } elseif ($request->type == 2) {
            $orders =  $this->order->where(['driver_id' => $request->user_id])->where('delivery_date', '=', date('Y-m-d'))->with('ProductOrderItem')->get();
            $neworderList = array();
            foreach ($orders as $order) {
                $neworder['delivery_time_slot'] = $order->delivery_time->from_time . "-" . $order->delivery_time->to_time;
                $neworder['user_name'] = $order->shipping_location->name;
                $neworder['order_id'] = $order->order_code;
                $neworder['status'] = Helper::$order_status[$order->order_status];
                $neworder['items'] = count($order->ProductOrderItem);
                $neworder['price'] = $order->total_amount;


                $neworderList[] = $neworder;
            }
        } else {
            $orders =  $this->order->where(['driver_id' => $request->user_id])->where('delivery_date', '>=', date('Y-m-d'))->with('ProductOrderItem')->get();
            $neworderList = array();
            foreach ($orders as $order) {
                $neworder['delivery_time_slot'] = $order->delivery_time->from_time . "-" . $order->delivery_time->to_time;
                $neworder['user_name'] = $order->shipping_location->name;
                $neworder['order_id'] = $order->order_code;
                $neworder['status'] = Helper::$order_status[$order->order_status];
                $neworder['items'] = count($order->ProductOrderItem);
                $neworder['price'] = $order->total_amount;


                $neworderList[] = $neworder;
            }
        }
        if (!empty($neworderList->toArray())) {
            return $this->showResponse($neworderList);
        } else {
            return $this->userNotExistResponse(trans('order.no_order'));
        }
    }


    public static function testNotification()
    {
        //$device_type = 'A';
        $device_type = 'I';
        /*android*/ //$device_token = ['eO0ySe12Ai4:APA91bG-8kRbzzQk7kc6Dv7n3TKLYthmThA3OPKW1derj47m0g-F4tJtsqgXv57qZ_izKRY72AO-0GMNZV49Mvtp_VGB-2s1nNw7eENCGPpLvZZm2k2EbbGlG5oGWhkT0vIM11_QLHP3'];
        /*device token IOS*/
        //$device_token = ['em7QTa5siK8:APA91bFshlrrQ_yb10sURQ29nZMQQpjFQA0lQpW3ZgD_ie8cHcN4HzZ79MnvRenNfqEZfhYhpcm-ZX1tg8kdjjMznjZvSGDr3k3gu7MANWyDpKX7qXLrKu456MzEFpt5fOVczJ99DID6'];
        $device_token = ['fCbc2I_9D1M:APA91bE2Yvw6bH6h-5X0u1eQEX8_Fa6mBk7JO-4AZAXz5XQODhAbR3uXvGJeW4-ukx3oG8vItltAg70lrfRKCF9U-glL7ogSLllLjBV76T9fSUlZsdSkdQfKMyO8TNCMFKXZV4nAVJne'];


        $type = 'Order';
        $product_type = 'New';
        $title = "New Order";
        $body = 'Your order has been confirmed';
        $push_message = "Test message";
        $fields = [];

        if ($device_token && !empty($device_token)) {

            //$url = env('FCM_URL');
            $url = 'https://fcm.googleapis.com/fcm/send';
            // $server_key = 'AIzaSyDh6vQ7cAqaMRP7r-tzrDZwi9w2EGDcGE0';
            $server_key = env('FCM_API_KEY');
            if ($device_type == 'I') {

                $fields = array(
                    'priority' => "high",
                    'notification' => array("title" => $title, "body" => $body, "sound" => "mySound", 'badge' => 4, 'vibrate' => 1),
                    'data' => array(
                        'type' => $type,
                        'order_type' => $product_type,
                        'title' => $title,
                        'body' => $body
                    ),
                );
            } else if ($device_type == 'A') {
                $fields = array(
                    'priority' => "high",
                    'data' => array(
                        'type' => $type,
                        'order_type' => $product_type,
                        'title' => $title,
                        'body' => $body
                    ),
                );
            }

            $fields['registration_ids'] = $device_token;
            //$fields = json_encode($fields);
            //echo '<pre>';print_r($fields);
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key=' . $server_key
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch);
            //    echo "hello"; echo '<pre>';print_r($result);die;
            if ($result === FALSE) {
                die('FCM Send Error:' . curl_error($ch));
            }

            curl_close($ch);
            return true;
        }
    }

    public function sendinvoiceonmail($order_id)
    {
        try {

            $order = $this->productOrder->with(['user', 'ProductOrderItem', 'ProductOrderItem.Product', 'ProductOrderItem.Product.measurementclass'])->where('id', $order_id)->first();

            $user = $this->user->where('id', $order->user_id)->first();
            if (!empty($order)) {
                //echo "<pre>"; print_r($order); die;
                $email = $user->email;
                $token =  $user->createToken('MyApp')->accessToken;
                $to_name = $user->name;;
                $to_email = $email;
                $data = array('name' => $to_name, "tokan" =>  $token, "email" =>  $to_email);

                try {
                    $info = ['orders_details' => $order];
                    //print_r($info);
                    $response = Mail::send("emails.mail", $info, function ($message) use ($to_name, $to_email) {
                        $message->from("darbaarevents@gmail.com", "Darbar Mart Delivery");
                        $message->to($to_email, $to_name)->subject("Order Invoice");
                    });

                    if (count(Mail::failures()) > 0) {
                        // echo "<pre>"; print_r(Mail::failures());die;
                        $errors = 'Failed to send Order Invoice, please try again for ' . $email . '.';

                        $result['error'] = true;
                        $result['code'] = 1;
                        $result['data'] = [];
                        $result['message'] = "This order is not in our records ";
                    }
                } catch (\Exception $e) {

                    //print_r($e->message);  
                    //echo 'Message: ' .$e->getMessage(); die();
                    $result['error'] = true;
                    $result['code'] = 1;
                    $result['data'] = [];
                    $result['message'] = $e->getMessage();

                    return response()->json($result);
                    //return $e;  
                }
                $result['error'] = false;
                $result['code'] = 0;
                $result['data'] = $response;
                $result['message'] = "Mail sent to your Email address " . $data['email'];
            } else {
                $result['error'] = true;
                $result['code'] = 1;
                $result['data'] = [];
                $result['message'] = "This order is not in our records ";
            }
            return response()->json($result);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
