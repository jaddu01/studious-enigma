<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
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
        $data= $this->wishLish->where(['user_id'=>Auth::guard('api')->user()->id])->where(['zone_id'=>Auth::guard('api')->user()->zone_id])->has('vendorProduct')->has('vendorProduct.Product')->with(['vendorProduct.Product.image']);
        $dataAll = $data;
        $data = $data->get()->toArray();

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
            return $this->validationErrorResponce($validator);
        }else{

            try {

                $user_id =  Auth::guard('api')->user()->id;
                $zone_id =  Auth::guard('api')->user()->zone_id;
                //return  $zone_id;
                if($wish_list = $this->wishLish->select(['id'])->where(['user_id'=>$user_id,'zone_id'=>$zone_id,'vendor_product_id'=>$request->vendor_product_id])->first()){
                    $message =trans('site.delete');
                    $data= [];
                   if($wish_list->delete()){

                        $response = [
                            'error'=>false,
                            'code' => 0,
                            'message'=>trans('site.wishlist_delete'),
                        ];
                        return response()->json($response, 200);
                   }
                    
                    
                }else{
                    $input_request = $request->all();
                    $input_request['user_id']=$user_id;
                    $input_request['zone_id']=$zone_id;
                    $data =  $this->wishLish->create($input_request);
                    
                    $message =trans('site.wishlist_create');
                }



            } catch (\Exception $e) {

                return $this->clientErrorResponse($e);
            }
            return $this->showResponse($data,$message);
        }
    }


}
