<?php

namespace App\Http\Controllers\Api;
use App\Notifications\AllOrderStatus;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\Coupon;

use App\ProductOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\ProductOrderItem;
use App\User;

class CouponController extends Controller
{
    use RestControllerTrait,ResponceTrait;

    const MODEL = 'App\Coupon';

    public function __construct(Request $request,Coupon $Coupon,ProductOrder $order)
    {
        parent::__construct();
        $this->Coupon = $Coupon;
        $this->order = $order;
    }


   public function checkcoupon(Request $request){

         $validator = Validator::make($request->all(),[
            'coupon_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponce($validator);
        }else{
            try{
    $coupondata =$this->Coupon->where('code',$request->coupon_code)->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))
    ->first();
    
   // echo "<pre>"; print_r($coupondata); die;
//    echo Auth::guard('api')->user()->id; die();
    if(!empty($coupondata)){
    	$order_count = $this->order->where('user_id',Auth::guard('api')->user()->id)->where('coupon_code',$request->coupon_code)->count();
        //echo $coupondata->number_of_use;
        //echo $order_count; die();
    	if($coupondata->number_of_use <= $order_count){
    		$noresult =[ 'error'=>true ,'code' =>3,'message'=>"This code already used for ".$coupondata->number_of_use." times"];
            return response()->json($noresult);
    	}else if($coupondata->number_of_use == 0){
            $noresult =[ 'error'=>true ,'code' =>3,'message'=>"Promocode expired!"];
             return response()->json($noresult);   
        }else{
            return $this->showResponse($data = $coupondata, $message = 'Promocode applied successfully');    
        }
        
    }else{
       
        $noresult =[ 'error'=>true ,'code' =>1,'message'=>"Invalid Coupon Code"];
        return response()->json($noresult);
    }
    }catch(Exception $e){ return $e; }
        }
    $noresult =[ 'error'=>true ,'code' =>2,
    'message'=>"Somthing Went wrong . Please try again"];
        return response()->json($noresult);
    }

       public function getcouponcodes(){
        $today = date('Y-m-d');
           $coupons = $this->Coupon->where('to_time','>=',$today)->get();
           return $this->showResponse($coupons);
       }
   }
