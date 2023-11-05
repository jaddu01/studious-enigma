<?php

namespace App\Http\Controllers\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Pos\PosOrderResource;
use App\PosCustomerOrderItem;
use App\PosCustomerPayment;
use App\PosCustomerProductOrder;
use App\User;
use App\UserWallet;
use App\VendorProduct;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            PosCustomerProductOrder::where('id', $request->order_id)->update([
                'extra_discount' => $request->extraDiscount,
                'delivery_charge' => $request->deliveryCharge,
                'due_amount' => $request->due_amount,
                'mode' => $request->mode,
                'description' => $request->description,
                'order_date' => $request->order_date,
                'order_time' => $request->order_time,
                'bill_amount' => $request->bill_amount,
                'changes' => $request->changes,
                'payment' => $request->payment,
            ]);
            $order = PosCustomerProductOrder::find($request->order_id);


            PosCustomerPayment::create([
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'payment_mode' => $request->payment_mode,
                'payment' => $request->payment,
                'transaction_no' => $request->transaction_no,
                'status' => 'paid'
            ]);

            $new_customer_wallet = $request->payment - $request->bill_amount - $request->changes;
            $customer_remain_wallet = DB::table('users')->find($order->customer_id)->wallet_amount;

            if ($new_customer_wallet > 0) {
                User::where('id', $order->customer_id)->update([
                    'wallet_amount' => ($customer_remain_wallet + $new_customer_wallet),
                ]);

                UserWallet::create([
                    'user_id' => $order->customer_id,
                    'transaction_id' => 'DARPOS16943319085785',
                    'transaction_type' => 'CREDIT',
                    'type' => 'Pos Amount Credit by Admin',
                    'amount' => $new_customer_wallet,
                    'status' => 1,
                    'wallet_type' => 'amount',
                ]);

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
               
            }
            // dd($request->products);
            PosCustomerOrderItem::where('order_id', $order->id)->delete();
            
            foreach ($request->products as $product) {
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
            DB::commit();

            $this->response->order_list = new  PosOrderResource($order);

            return ResponseBuilder::success($this->response, 'Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }
}
