<?php

namespace App\Http\Controllers\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Pos\VendorProductResource;
use App\Http\Resources\VendorProductWithQuantityResource;
use App\PosCustomerOrderItem;
use App\PosCustomerPayment;
use App\PosCustomerProductOrder;
use App\Product;
use App\VendorProduct;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $vendorProduct =  VendorProduct::with(['product.MeasurementClass','product.image'])->whereHas('product',function($q){ $q->where('status','1'); })->get();
        // dd($vendorProduct);
        $this->response->product = VendorProductResource::collection($vendorProduct);

        return ResponseBuilder::success($this->response, 'Product list',$this->successStatus);
    }


    public function productWithQuantity(Request $request){
        try{
            $vendorProduct =  VendorProduct::whereHas('product',function($q){ $q->where('status','1'); })->get();
            $this->response->product = VendorProductWithQuantityResource::collection($vendorProduct);
            return ResponseBuilder::success($this->response, 'Product list',$this->successStatus);
        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }
    public function updateProduct(Request $request){
        
        try{
           
        //    dd($invoiceNo??1);
            DB::beginTransaction();
            $data = $request->all();
          
            $customer_pos_order_list =$data['customer_pos_order_list'];
           
            foreach($customer_pos_order_list as $products){
                $PosCustomerOrder =  PosCustomerProductOrder::orderBy('id','desc')->first();
               
               $order =  PosCustomerProductOrder::create([
                    'customer_id'=>$products['customerID'],
                    'pos_user_id'=>1,
                    'invoice_no'=>(is_null($PosCustomerOrder)?'D_BEAW0':'D_BEAW'.$PosCustomerOrder->id),
                    'extra_discount'=>$products['extraDiscount'],
                    'delivery_charge'=>$products['deliveryCharge'],z
                    'due_amount'=>$products['due_amount'],
                    'mode'=>$products['mode'],
                    'description'=>$products['description'],
                ]);
              

                PosCustomerPayment::create([
                    'customer_id'=>$products['customerID'],
                    'order_id'=>$order->id,
                    'payment_mode'=>$products['payment_mode'],
                    'payment'=>$products['payment'],
                    'transaction_no'=>$products['transaction_no'],
                    'status'=>'paid'
                ]);
               
                foreach($products['products'] as $product){
                    PosCustomerOrderItem::create([
                        'order_id'=>$order->id,
                        'customer_id'=>$products['customerID'],
                        'price'=>$product['price'],
                        'qty'=>$product['quantity'],
                        'vendor_product_id'=>$product['vendor_product_id'],
                        'product_id'=>$product['productId'],
                        'offer_value'=>$product['offer_price'],
                    ]);

               
                $oldProductQty = DB::table('products')->find($product['productId']);
                // dd($oldProductQty->qty);
                
                DB::table('products')->where('id',$product['productId'])->update([
                        'qty'=>($oldProductQty->qty-$product['quantity']),
                    ]);

                    $oldVendorProductQty = VendorProduct::find($product['vendor_product_id']);

                    VendorProduct::where('id',$product['vendor_product_id'])->update([
                        'qty'=>($oldVendorProductQty->qty-$product['quantity']),
                      
                    ]);
                }
                }
            
                DB::commit();
     
            return ResponseBuilder::success(null,'Updated Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            dd($e);
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

}
