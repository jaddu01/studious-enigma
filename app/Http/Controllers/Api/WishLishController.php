<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Helpers\ResponseBuilder;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\WishLish;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $user = Auth::guard('api')->user();
        $wishlists = $user->wishlist()->with('vendorProduct','vendorProduct.Product.image')->get();
        $data= $this->wishLish->where(['user_id'=>Auth::guard('api')->user()->id])->where(['zone_id'=>Auth::guard('api')->user()->zone_id])->has('vendorProduct')->has('vendorProduct.Product')->with(['vendorProduct.Product.image']);
       
        $result= [];
        foreach ($data as $rec){
            $rec['vendor_product']['product']['image']=$rec['vendor_product']['product']['image']['name'];
            $result[]= $rec;
        }

        $response = [
            'error'=>false,
            'code' => 0,
            'wish_list' => $data,
            'wish_list_count' => count($result),
            'message'=>trans('site.success'),
        ];

        return response()->json($response, 200);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),$this->wishLish->rules($this->method),$this->wishLish->messages($this->method));
        if ($validator->fails()) {
            // return $this->validationErrorResponce($validator);
            return ResponseBuilder::error($validator->errors()->first(), $this->validationStatus);
        }else{
            try {
                $user = Auth::guard('api')->user();
                //get user wishlist
                $wishlist = $user->wishlist()->where(['vendor_product_id'=>$request->vendor_product_id])->first();
                if($wishlist){
                    //remove from wishlist
                    $wishlist->delete();
                    return ResponseBuilder::success(null, trans('site.wishlist_delete'), $this->successStatus);
                }else{
                    //add to wishlist
                    $user->wishlist()->create(['vendor_product_id'=>$request->vendor_product_id]);
                    return ResponseBuilder::success(null,trans('site.wishlist_create'), $this->successStatus);
                }
            } catch (\Exception $e) {
                return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
            }
        }
    }


}
