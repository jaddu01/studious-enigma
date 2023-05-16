<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Auth\Authenticatable;
use App;
use Illuminate\Support\Facades\DB;

use Razorpay\Api\Api;
use Session;
use Redirect;
use App\Helpers\Helper;
use App\User;
use App\Cart;
use App\ProductOrder;

use GuzzleHttp\Client;

class RazorpayController extends Controller
{    
    public function payWithRazorpay($order_id)
    {   
      
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
        return view('pages.payWithRazorpay',compact('amount','email','order_id','phone_number'));
    }

    public function payment()
    {
        //Input items of form
        $input = Input::all();
        //get API Configuration 
        $api = new Api(env('razor_key'), env('razor_secret'));
        //print_r($api); 
        //print_r($input);
        //die;
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        //print_r($payment); 
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']-$payment['fee']));
                if($response->status == 'captured'){
                    $amount = Session::get('payment_data')['amount'];
                    $order_id = Session::get('payment_data')['order_id'];
                    $order_code = Session::get('payment_data')['order_code'];
                    $zone_id = Session::get('payment_data')['zone_id'];
                    $payment_mode_id = Session::get('payment_data')['payment_mode_id'];
                    $darbaar_coin_price = Session::get('darbaar_coin_price');

                    if($darbaar_coin_price > 0) {
                        $transaction_id = "DAR".time().$order_id;
                        $order_id = $order_id;
                        $description = "Darbarar Coin Applied for Product Order.";
                        $json_data = json_encode(['order_id'=>$order_id,'order_code'=>$order_code]);
                        $darbaar_coin_price = Session::get('darbaar_coin_price');
                        Helper::updateCustomerCoins(Auth::user()->id,$darbaar_coin_price,'DEBIT',"Order Payment",$transaction_id,$description,$json_data,$order_id);
                    }
                    

                   
                    if($payment_mode_id == '3'){
                        $transaction_id = "DAR".time().$order_id;
                        $order_id = $order_id;
                        $description = "Order Purchase from wallet.";
                        $json_data = json_encode(['order_id'=>$order_id,'order_code'=>$order_code]);
                        $wallet_amount = Session::get('payment_data')['wallet_amount'];
                        Helper::updateCustomerWallet(Auth::user()->id,$wallet_amount,'DEBIT',"Order Payment",$transaction_id,$description,$json_data,$order_id);
                    }
                    if($payment_mode_id == '2'){
                        // comment for stop cashback by Abhishek Bhatt//
                        /*if($amount >= 2500){
                          $amount =  100;
                          $transaction_type = "CREDIT";
                          $type ="Order Total Bonus";
                          $transaction_id = 'DAR'.time().$order_id;
                          $description ="Order purchase is more than 2500. Order Total bonus.";
                          $json_data = json_encode(['order_id'=>$order_id,'order_code'=>$order_code]);
                          $user_wallet = Helper::updateCustomerWallet(Auth::user()->id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id);
                        }*/
                    }
                    $orders = ProductOrder::find($order_id);
                    if(!empty($orders)){
                        $orders->transaction_id = $response->id;
                        $orders->transaction_status = '1';
                        if($darbaar_coin_price > 0) {
                            $orders->coin_payment = $darbaar_coin_price;
                        }
                        $orders->save();
                    }

                    Session::forget('payment_data');
                    if(!empty(Session::get('coupon_discount'))){
                        Session::forget('coupon_discount');
                        Session::forget('coupon_text');
                    }
                    
                    $client = new Client();
                    $authkey = env('AUTHKEY');
                    $phone_number = Auth::user()->phone_number;
                    $senderid = env('SENDERID');
                  
                    $message = "Thanks for the order. We will try to dispatch your order ASAP. Thanks!";
                    
                    $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91");

                    $statusCode = $response->getStatusCode(); 

                    Session::flash(trans('order.order_confirmed').$order_code);
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
                    
                    Cart::where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id])->delete();
           
                    // return view('pages.trackorder')->with('data',$data)->with('Success',trans('order.order_confirmed').$order->order_code);
                    return redirect('/track-order/'.$order_id)->with('Success',trans('order.order_confirmed').$order_code);
                }else{
                    Session::forget('payment_data');
                }
                 

            } catch (\Exception $e) {
                return  $e->getMessage();
                \Session::put('error',$e->getMessage());
                return redirect()->back();
            }

            // Do something here for store payment details in database...
        }
        \Session::put('success', 'Payment successful.');
        return redirect()->back();
    }  
        
}

/*

*/
?>