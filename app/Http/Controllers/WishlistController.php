<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\WishLish;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Product;
use App\Offer;
use App\User;
use App\VendorProduct;
use App\ProductOrder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Wishlist Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles redirecting them to your home screen. 
    |
    */


    /**
     * Where to redirect users before login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, VendorProduct $vendorProduct,WishLish $wishLish,ProductOrder $productorder,Offer $Offer)
    {
        parent::__construct();
        $this->wishLish = $wishLish;
        $this->order = $productorder;
        $this->offer = $Offer;
        $this->vendorProduct = $vendorProduct;
        $this->method=$request->method();
        $this->validationRules = $this->wishLish->rules($this->method);
         $this->middleware('auth');
    }

//    public function index()
//    {
//        return view('pages.mywishlist');
//    }


    protected $validationRules;

    public function index(Request $request){
        $zone_id = $request->session()->get('zone_id');
        $user_id = Auth::user()->id;

        $data= $this->wishLish->where(['user_id'=>Auth::user()->id])
                                //->where(['zone_id'=>$zone_id])
                                ->has('VendorProduct')
                                ->with(['VendorProduct.Product.image'])
                                ->with(['VendorProduct.Product.MeasurementClass'])
                                ->whereHas('VendorProduct.Product',function($q){ $q->where('status','1')->whereNULL('deleted_at');  });
       $wishLish =  $data = $data->groupBy('vendor_product_id')->paginate(10);
        $data = $data->toArray();
        
            $user = User::select('*');
            $user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
            $user = $user->get()->toArray();

          
            $user_id_array=[];
            foreach($user as $kk=>$vv){
            $user_id_array[] = $vv['id'];
           }


         $result= [];
         foreach ($data['data'] as $rec){
            //echo "<pre>"; print_r($rec); die; 
        //    $rec['vendor_product']['product']['image']=$rec['vendor_product']['product']['image']['name'];
            $rec['is_offer'] = false;
            $rec['offer_id'] = null;
            $rec['price'] = $rec['vendor_product']['price'];
            $rec['offer_price'] = $rec['vendor_product']['price'];
            
            $ffer_data = $this->offer->where('id',$rec['vendor_product']['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
            if(!empty($ffer_data)){
            $rec['is_offer'] = true;
            $rec['offer_id'] = $ffer_data->id;
            $rec['offer_data'] = $ffer_data;
            if($ffer_data->offer_type=='amount'){
            $rec['offer_price'] = $rec['price'] - $ffer_data->offer_value;
            }else if($ffer_data->offer_type=='percentages'){
            $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 )) ;                 
            }
            $rec['offer_price'] = number_format( $rec['offer_price'],2,'.','');
            } 
            $vproduct_status = $this->vendorProduct->where('id',$rec['vendor_product_id'])->whereIn('user_id',$user_id_array)->select('*')->first();
            $rec['not_avail']= 0;
          if(!empty($vproduct_status)){  $rec['not_avail']=1; }
          $result[]= $rec;
 }
     //  echo "<pre>";  print_r($result); die;
         $response = [
            'error'=>false,
            'code' => 0,
            'wish_list' => $result,
            'wish_list_count' => count($result),
            'message'=>trans('site.success'),
         ];
         $user = auth()->user();
         $total_order = $this->order->where('user_id',$user->id)->count();
         return view('pages.mywishlist',['wishlist' => $response,'wishLish' => $wishLish,'user' => $user,'total_order'=>$total_order,'zone_id'=>$zone_id]);
        //return response()->json($response, 200);
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

                $zone_id = $request->session()->get('zone_id');
                if(empty($zone_id)){   $zone_id = Auth::user()->zone_id; }
                 $user_id = Auth::user()->id;
                  $wish_list = $this->wishLish->select(['id'])->where(['user_id'=>$user_id,'zone_id'=>$zone_id,'vendor_product_id'=>$request->vendor_product_id])->first();

                if(!empty($wish_list)){
                  

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
                   return view('pages.NoProductpage')->with('message',$e->getMessage());       
           }
            $response = [
                'error'=>false,
                'code' => 1,
                'data' => $data,
                'message' => $message
            ];
            return response()->json($response, 200);
        }
    }


}
