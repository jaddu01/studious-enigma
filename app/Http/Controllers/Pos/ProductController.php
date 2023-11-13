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
