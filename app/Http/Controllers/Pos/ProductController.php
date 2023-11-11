<?php

namespace App\Http\Controllers\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Pos\PosOrderResource;
use App\Http\Resources\Pos\VendorProductResource;
use App\Http\Resources\VendorProductWithQuantityResource;
use App\PosCustomerOrderItem;
use App\PosCustomerPayment;
use App\PosCustomerProductOrder;
use App\Product;
use App\User;
use App\UserWallet;
use App\VendorProduct;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $vendorProduct =  VendorProduct::with(['product.MeasurementClass', 'product.image'])->whereHas('product', function ($q) {
            $q->where('status', '1');
        })->get();
        // dd($vendorProduct);
        $this->response->product = VendorProductResource::collection($vendorProduct);

        return ResponseBuilder::success($this->response, 'Product list', $this->successStatus);
    }


    public function productWithQuantity(Request $request)
    {
        try {
            $vendorProduct =  VendorProduct::whereHas('product', function ($q) {
                $q->where('status', '1');
            })->get();
            $this->response->product = VendorProductWithQuantityResource::collection($vendorProduct);
            return ResponseBuilder::success($this->response, 'Product list', $this->successStatus);
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }
    public function placeOrder(Request $request)
    {
        try {

            //    dd($invoiceNo??1);
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
                $customer_remain_wallet = DB::table('users')->find($products['customerID'])->wallet_amount;

                if ($new_customer_wallet > 0) {
                    User::where('id', $products['customerID'])->update([
                        'wallet_amount' => ($customer_remain_wallet + $new_customer_wallet),
                    ]);
                    UserWallet::create([
                        'user_id' => $products['customerID'],
                        'transaction_id' => 'DARPOS16943319085785',
                        'transaction_type' => 'CREDIT',
                        'type' => 'Pos Amount Credit by Admin',
                        'amount' => $new_customer_wallet,
                        'status' => 1,
                        'wallet_type' => 'amount',

                    ]);
                }

                if ($new_customer_wallet < 0 && $new_customer_wallet != 0) {
                    User::where('id', $products['customerID'])->update([
                        'wallet_amount' => ($customer_remain_wallet + $new_customer_wallet),
                    ]);
                    UserWallet::create([
                        'user_id' => $products['customerID'],
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

            return ResponseBuilder::success(null, 'Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    public function orders(Request $request)
    {
        try {
         
            $orders = PosCustomerProductOrder::get();
            if(isset($request->date)){
                $orders = PosCustomerProductOrder::where(DB::raw('unix_timestamp(created_at)'),'>',$request->date)->get();
            }

            $this->response->order_list = PosOrderResource::collection($orders);
            return ResponseBuilder::success($this->response, 'Order List');
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }
}
