<?php

namespace App\Http\Controllers\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Pos\VendorProductResource;
use App\VendorProduct;

class ProductController extends Controller
{
    public function index()
    {
        $vendorProduct =  VendorProduct::with(['product.MeasurementClass','product.image'])->whereHas('product',function($q){ $q->where('status','1'); })->get();
        // dd($vendorProduct);
        $this->response->product = VendorProductResource::collection($vendorProduct);

        return ResponseBuilder::success($this->response, 'Product list',$this->successStatus);
    }
}
