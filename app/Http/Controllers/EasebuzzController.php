<?php

/**
 * @Author: Abhi Bhatt
 * @Date:   2022-04-16 01:35:00
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-05-07 00:36:21
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Helpers\Easebuzz;
use App\Helpers\Payment;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class EasebuzzController extends Controller
{

    public function __construct() {
        $this->MERCHANT_KEY = "2PBP7IABZ2";
        $this->SALT = "DAH88E3UWQ";
        //$this->MERCHANT_KEY = "YVEUQFHC5F";
        //$this->SALT = "AB9TGNO4TK";
        $this->ENV = "test"; //
    }

    function easebuzz_gateway (){
        return View('easebuzz_gateway');
    }

    function paywitheasebuzz($order_id) {
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
        return view('pages.paywitheasebuzz',compact('amount','email','order_id','phone_number'));
    }

    public function payment(Request $request)
    {
        $user = Auth::user();
      //  echo '<pre>'; print_r($user); echo '</pre>'; die();
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
        $customerName = 'Abhishek Bhatt';
        
        $postData = array (
            "txnid" => $order_id.'-'.date('Ymdhis'),
            "amount" => $amount.'.0',
            "firstname" => $customerName,
            "email" => $email,
            "phone" => $phone_number,
            "productinfo" => $order_id,
            "surl" => url('easebuzz-webhook'),
            "furl" => url('easebuzz-webhook'),
            "udf1" => "",
            "udf2" => "",
            "udf3" => "",
            "udf4" => "",
            "udf5" => "",
            "udf6" => "",
            "udf7" => "",
            "address1" => '',
            "address2" => '',
            "city" => "",
            "state" => "",
            "country" => "",
            "zipcode" => '',
        );
        $easebuzzObj = new Easebuzz($this->MERCHANT_KEY, $this->SALT, $this->ENV);
        $easebuzzObj->initiatePaymentAPI($postData);
    
    } 

    function order (Request $request){
        $this->validate($request, [
            'amount' => 'required|regex:/^d+(.d{1,2})?$/',
            'customerName' => 'required',
            'customerPhone' => 'required',
            'customerEmail' => 'required',
        ]);
        $amount = $request->amount;
        $customerName = $request->customerName;
        $customerPhone = $request->customerPhone;
        $customerEmail = $request->customerEmail;
        $now = new \DateTime();
        $created_at = $now->format('Y-m-d H:i:s');
        $orderId = Order::insertGetId([
            'customerName' => $customerName,
            'customerPhone' => $customerPhone,
            'customerEmail' => $customerEmail,
            'amount' => $amount,
            'created_at' => $created_at,
            'status_id' => 3,
        ]);
        $postData = array (
            "txnid" => $orderId,
            "amount" => $amount.'.0',
            "amount" => $amount,
            "firstname" => $customerName,
            "email" => $customerEmail,
            "phone" => $customerPhone,
            "productinfo" => "Laptop",
            "surl" => url('easebuzz-webhook'),
            "furl" => url('easebuzz-webhook'),
            "udf1" => "",
            "udf2" => "",
            "udf3" => "",
            "udf4" => "",
            "udf5" => "",
            "udf6" => "",
            "udf7" => "",
            "address1" => '',
            "address2" => '',
            "city" => "",
            "state" => "",
            "country" => "",
            "zipcode" => '',
        );
        $easebuzzObj = new Easebuzz($this->MERCHANT_KEY, $this->SALT, $this->ENV);
        $easebuzzObj->initiatePaymentAPI($postData);
    }

    function easebuzz_webhook (Request $request){
        $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $this->SALT, $ENV = null);
        $result = $easebuzzObj->easebuzzResponse($request->all());
        $res = json_decode($result);
        echo '<pre>';
        print_r($res);
        echo '</pre>';
        exit();
        $status = $res->status;
        if ($status == 1){
            $data = $res->data;
            $orderId = $data->txnid;
            $status = $data->status;
            if ($status == 'success'){
                // here your update query
                Order::where('id', $orderId)->update(['status_id' => 1]);
                \Session::flash('successMessage', 'Successful..!');
                return redirect('easebuzz-gateway');
            }else{
                \Session::flash('errorMessage', 'failed!');
                return redirect('agent/add-money/v1/welcome');
            }
        }
    }
}