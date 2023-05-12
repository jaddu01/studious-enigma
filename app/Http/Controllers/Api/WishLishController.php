<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Helpers\ResponseBuilder;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\WishLish;
use App\VendorProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WishListResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishLishController extends Controller
{
    use RestControllerTrait,ResponceTrait;

    const MODEL = 'App\WishLish';
    /**
     * @var Contact
     */
    private $wishLish;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $validationRules;

    public function __construct(Request $request,WishLish $wishLish)
    {
        parent::__construct();
        $this->wishLish = $wishLish;

        $this->method=$request->method();
        $this->validationRules = $this->wishLish->rules($this->method);
    }
    public function index(Request $request)
    {
        try{
            $user = Auth::guard('api')->user();
            $wishlists = $user->wishlist()->whereHas('vendorProduct')->with(['vendorProduct' => function($q){
                $q->whereNull('deleted_at');
            }],'vendorProduct.Product.image')->where('zone_id', $user->zone_id)->paginate(20);
            $this->response->wish_list = WishListResource::collection($wishlists);
            $this->response->wish_list_count = $wishlists->count();
            return ResponseBuilder::successWithPagination($wishlists, $this->response, $this->successStatus);
        }catch (\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),$this->wishLish->rules($this->method),$this->wishLish->messages($this->method));
        if ($validator->fails()) {
            // return $this->validationErrorResponce($validator);
            return ResponseBuilder::error($validator->errors()->first(), $this->validationStatus);
        }else{
            try {
                $user = Auth::guard('api')->user();
                //get vendor product
                $vendor_product = VendorProduct::find($request->vendor_product_id);
                if(!$vendor_product){
                    return ResponseBuilder::error("Vendor product not found", $this->validationStatus);
                }
                //get user wishlist
                $wishlist = $user->wishlist()->where(['vendor_product_id'=>$request->vendor_product_id, 'zone_id' => $user->zone_id])->first();
                if($wishlist){
                    //remove from wishlist
                    $wishlist->delete();
                    return ResponseBuilder::success(null, trans('site.wishlist_delete'), $this->successStatus);
                }else{
                    //add to wishlist
                    $user->wishlist()->create(['vendor_product_id'=>$request->vendor_product_id, 'zone_id'=>$user->zone_id]);
                    return ResponseBuilder::success(null,trans('site.wishlist_create'), $this->successStatus);
                }
            } catch (\Exception $e) {
                return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
            }
        }
    }


}
