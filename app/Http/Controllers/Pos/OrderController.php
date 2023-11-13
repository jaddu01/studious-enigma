<?php

namespace App\Http\Controllers\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderShortResource;
use App\Http\Resources\Pos\PosOrderResource;
use App\PosCustomerOrderItem;
use App\PosCustomerPayment;
use App\PosCustomerProductOrder;
use App\ProductOrder;
use App\User;
use App\UserWallet;
use App\VendorProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        try {

            DB::beginTransaction();
            $data = $request->all();

            $customer_pos_order_list = $data['customer_pos_order_list'];

            foreach ($customer_pos_order_list as $products) {
                $PosCustomerOrder =  PosCustomerProductOrder::orderBy('id', 'desc')->first();
           
                $order =  PosCustomerProductOrder::create([
                    'customer_id' => $products['customerID'],
                    'pos_user_id' => 1,
                    'invoice_no' => (is_null($PosCustomerOrder) ? 'D_BEAW0' : 'D_BEAW' . $PosCustomerOrder->id),
                    'extra_discount' => $products['extraDiscount'],
                    'delivery_charge' => $products['deliveryCharge'],
                    'due_amount' => $products['due_amount'],
                    'mode' => $products['mode'],
                    'description' => $products['description'],
                    'order_date' => $products['order_date'],
                    'order_time' => $products['order_time'],
                    'bill_amount' => $products['bill_amount'],
                    'changes' => $products['changes'],
                    'payment' => $products['payment'],
                    'online_order_id' => $products['online_order_id'],
                    'pos_state' => $products['pos_state'],
                ]);

                $new_customer_wallet = $products['payment'] - $products['bill_amount'] - $products['changes'];
                $customer_remain_wallet = DB::table('users')->find($order->customer_id)->wallet_amount;
                if ($new_customer_wallet > 0) {
                    User::where('id', $order->customer_id)->Increment('wallet_amount', $new_customer_wallet);
                    UserWallet::create([
                        'user_id' => $order->customer_id,
                        'transaction_id' => 'DARPOS16943319085785',
                        'transaction_type' => 'CREDIT',
                        'type' => 'Pos Amount Credit by Admin',
                        'amount' => $new_customer_wallet,
                        'status' => 1,
                        'wallet_type' => 'amount',
                    ]);
                }

                if ($new_customer_wallet < 0 && $new_customer_wallet != 0) {
                    User::where('id', $order->customer_id)->update([
                        'wallet_amount' => ($customer_remain_wallet + $new_customer_wallet),
                    ]);
                    UserWallet::create([
                        'user_id' => $order->customer_id,
                        'transaction_id' => 'DARPOS16943319085785',
                        'transaction_type' => 'DEBIT',
                        'type' => 'Pos  Amount Debit by Admin',
                        'amount' => (-1 * $new_customer_wallet),
                        'status' => 1,
                        'wallet_type' => 'amount',

                    ]);
                }

                PosCustomerPayment::create([
                    'customer_id' => $products['customerID'],
                    'order_id' => $order->id,
                    'payment_mode' => $products['payment_mode'],
                    'payment' => $products['payment'],
                    'transaction_no' => $products['transaction_no'],
                    'status' => 'paid'
                ]);

                foreach ($products['products'] as $product) {
                    PosCustomerOrderItem::create([
                        'order_id' => $order->id,
                        'customer_id' => $products['customerID'],
                        'price' => $product['price'],
                        'qty' => $product['quantity'],
                        'is_offer' => $product['is_offer'],
                        'vendor_product_id' => $product['vendor_product_id'],
                        'product_id' => $product['productId'],
                        'offer_value' => $product['offer_price'],
                        'offer_data' => $product['offer_data'],
                        'best_price' => $product['best_price'],

                    ]);


                    $oldProductQty = DB::table('products')->find($product['productId']);
                    // dd($oldProductQty->qty);

                    DB::table('products')->where('id', $product['productId'])->update([
                        'qty' => ($oldProductQty->qty - $product['quantity']),
                    ]);

                    $oldVendorProductQty = VendorProduct::find($product['vendor_product_id']);

                    VendorProduct::where('id', $product['vendor_product_id'])->update([
                        'qty' => ($oldVendorProductQty->qty - $product['quantity']),

                    ]);
                }
            }

            DB::commit();

            return ResponseBuilder::success(null, 'Order placed Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $order_ids =[];
            $data = $request->all();
            $customer_pos_order_list = $data['customer_pos_orders'];
            foreach ($customer_pos_order_list as $products) {
                $order_ids[]=($products['order_id']);
                PosCustomerProductOrder::where('id', $products['order_id'])->update([
                    'extra_discount' => $products['extraDiscount'],
                    'delivery_charge' => $products['deliveryCharge'],
                    'due_amount' => $products['due_amount'],
                    'mode' => $products['mode'],
                    'description' => $products['description'],
                    'order_date' => $products['order_date'],
                    'order_time' => $products['order_time'],
                    'bill_amount' => $products['bill_amount'],
                    'changes' => $products['changes'],
                    'payment' => $products['payment'],
                    'pos_state' => $products['pos_state'],
                    'online_order_id' => $products['online_order_id'],
                ]);
                $order = PosCustomerProductOrder::find($products['order_id']);
                PosCustomerPayment::create([
                    'customer_id' => $order->customer_id,
                    'order_id' => $order->id,
                    'payment_mode' => $products['payment_mode'],
                    'payment' => $products['payment'],
                    'transaction_no' => $products['transaction_no'],
                    'status' => 'paid'
                ]);
              
                $new_customer_wallet = $products['payment'] - $products['bill_amount'] - $products['changes'];
                $customer_remain_wallet = DB::table('users')->find($order->customer_id)->wallet_amount;
                if ($new_customer_wallet > 0) {
                    User::where('id', $order->customer_id)->Increment('wallet_amount', $new_customer_wallet);
                    UserWallet::create([
                        'user_id' => $order->customer_id,
                        'transaction_id' => 'DARPOS16943319085785',
                        'transaction_type' => 'CREDIT',
                        'type' => 'Pos Amount Credit by Admin',
                        'amount' => $new_customer_wallet,
                        'status' => 1,
                        'wallet_type' => 'amount',
                    ]);
                }

                if ($new_customer_wallet < 0 && $new_customer_wallet != 0) {
                    User::where('id', $order->customer_id)->update([
                        'wallet_amount' => ($customer_remain_wallet + $new_customer_wallet),
                    ]);
                    UserWallet::create([
                        'user_id' => $order->customer_id,
                        'transaction_id' => 'DARPOS16943319085785',
                        'transaction_type' => 'DEBIT',
                        'type' => 'Pos  Amount Debit by Admin',
                        'amount' => (-1 * $new_customer_wallet),
                        'status' => 1,
                        'wallet_type' => 'amount',

                    ]);
                }


                PosCustomerOrderItem::where('order_id', $order->id)->delete();
                foreach ($products['products'] as $product) {
                    PosCustomerOrderItem::where('order_id', $order->customer_id)->create([
                        'order_id' => $order->id,
                        'customer_id' => $order->customer_id,
                        'price' => $product['price'],
                        'qty' => $product['quantity'],
                        'is_offer' => $product['is_offer'],
                        'vendor_product_id' => $product['vendor_product_id'],
                        'product_id' => $product['productId'],
                        'offer_value' => $product['offer_price'],
                        'offer_data' => $product['offer_data'],
                        'best_price' => $product['best_price'],
                    ]);
                    DB::table('products')->where('id', $product['productId'])->decrement('qty', $product['quantity']);
                    VendorProduct::where('id', $product['vendor_product_id'])->decrement('qty', $product['quantity']);
                }
            }
            $orders =PosCustomerProductOrder::whereIn('id',$order_ids)->get();
            DB::commit();

            $this->response->order_list = PosOrderResource::collection($orders);
            return ResponseBuilder::success($this->response, 'Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    public function appOrderlist(Request $request)
    {
        $orders =  ProductOrder::select(['id', 'order_status', 'order_code', 'delivery_date', 'delivery_time', 'shipping_location', 'total_amount', 'offer_total', 'delivery_charge', 'created_at', 'payment_mode_id'])
            ->where('order_status', '!=', 'D')->where('order_status', '!=', 'C')->where('order_status', '!=', 'R')
            ->orderBy('created_at', 'DESC')->get();
        $this->response->orders = OrderShortResource::collection($orders);
        return ResponseBuilder::success($this->response, "success");
    }
}
