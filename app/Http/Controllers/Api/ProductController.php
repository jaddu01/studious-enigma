<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\CategoryTranslation;
use App\DeliveryDay;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\SiteSetting;
use App\AppSetting;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\User;
use App\VendorProduct;
use App\Product;
use App\Zone;
use App\Offer;
use App\Slider;
use App\OfferSlider;
use App\WishLish;
use App\Ads;
use App\Brand;
use App\ZoneTranslation;
use App;
use App\Coupon;
use App\SearchQueries;
use App\Helpers\ResponseBuilder;
use App\Http\Resources\CategoryResource;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\VendorProductDetailedResource;
use App\Http\Resources\VendorProductResource;
use App\NotifyMe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


use App\ProductOrderItem;
use Illuminate\Validation\Rule;
use Request as GlobalRequest;

class ProductController extends Controller
{
    use RestControllerTrait,ResponceTrait;

    const MODEL = 'App\VendorProduct';
    /**
     * @var Contact
     */
    private $vendorProduct;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $validationRules;

    public function __construct(Request $request,VendorProduct $vendorProduct,Zone $zone,Offer $offer,CategoryTranslation $category,WishLish $wishLish)
    {

        parent::__construct();
        $this->vendorProduct = $vendorProduct;
        $this->zone = $zone;
        $this->category=$category;
        $this->offer=$offer;
        $this->wishLish = $wishLish;
        $this->method=$request->method();
        $this->validationRules = $this->vendorProduct->rules($this->method);
    }

    public function listDemo(Request $request)
    {
        /*$validator = Validator::make($request->all(), [

            'lat'=>'required',
            'lng' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        }*/


        try {
            $zone_id = Zone::whereRaw('CONTAINS(point, point('.$request->lat.','.$request->lng.'))')->firstOrFail()->id;

            $user  = User::whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor'])->firstOrFail();
                //return $vendorProduct = $user->vendorProduct();
            $vendorProduct = $user->vendorProduct()
                ->with([
                    'product.MeasurementClass',
                    'product.image','cart'=>function($q){
                        $q->where(['user_id'=>5,'zone_id'=>1]);
                    },'wishList'=>function($q){
                        $q->where(['user_id'=>5]);
                    }]);

            if($request->has('category')){
                $category=explode(',',$request->category);
                // $category=$request->category;
                $vendorProduct->with('product')->whereHas(
                    'product',function($q) use($category){

                    //$q->whereIn('category_id', $category);
                    $condition = ' ';

                    foreach ($category as $cat){
                        $condition.="FIND_IN_SET('".$cat."',category_id) or ";
                    }

                    $condition =  rtrim($condition,' or ');
                    // echo $condition;die;
                    $q->whereRaw($condition);
                    //dd($q->toSql());
                });
            }

            if($request->filled('search')){
                $search = $request->search;
                $vendorProduct->whereHas('Product.translations', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            if($request->has('offer')){
                $vendorProduct->has('offer');
                if($request->offer != ''){
                    $vendorProduct->where(['offer_id'=>$request->offer]);
                }
            }


            $vendorProduct= $vendorProduct->paginate(1000)->toArray();
            //$vendorProduct= $vendorProduct->toArray();

            $data=[];
            foreach ($vendorProduct['data'] as $rec){
                $rec['product']['image'] = $rec['product']['image']['name'];
                unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
                $data[]=$rec;
            }

            unset($vendorProduct['data']);
            $vendorProduct['product'] = $data;
            $subcategory =[];
            if($request->filled('category')){
                $subcategory = Category::whereIn('parent_id',explode(',',$request->category))->listsTranslations('name','id')->get();
            }
            $vendorProduct['subcategory'] =  $subcategory;

            return $this->showResponse($vendorProduct);

        } catch (\Exception $e) {
            return $this->clientErrorResponse($e);
        }


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return Auth::guard('api')->user()->id;
        $validator = Validator::make($request->all(), [
            'lat'=>'required',
            'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        }

        try {
            if(Auth::guard('api')->user()){
             $zonedata = $this->getZoneData($request->lat, $request->lng);
            $zone_id =  $zonedata['zone_id'];
            $zone_name =  $zonedata['zone_name'];
            $match_in_zone = (bool)$zonedata['match_in_zone'];
            }else{
           $zonedata =   $this->zone->where('is_default','1')->where('status','1')->first();
            $zone_id =  $zonedata->id;
            $zone_name =  $zonedata->name;
            $match_in_zone = true;

         } 
            $user  = User::select('*');
            $user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);
            $user = $user->get();
            if(!isset($user)){
                 $vendorProduct = [];
            }else{
                $useridarray =[];
                foreach($user as $userkey=>$uservalue){
                        $useridarray[]= $uservalue->id;
                }
                if(Auth::guard('api')->user()){
            $vendorProduct = $this->vendorProduct
                ->with([
                    'product.MeasurementClass',
                    'product.image','cart'=>function($q){
                        $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>Auth::guard('api')->user()->zone_id]);
                    },'wishList'=>function($q){
                        $q->where(['user_id'=>Auth::guard('api')->user()->id]);
                    }])->where('status','1')->whereIn('user_id',$useridarray);

            }else{
                 $vendorProduct = $this->vendorProduct
                ->with(['product.MeasurementClass','product.image'])->where('status','1')->whereIn('user_id',$useridarray);
            }
            }
            
            if($request->has('is_favourite') && $request->is_favourite==1){
                if(!empty($vendorProduct)){
                    $vendorProduct->has('wishList');
                }

            }
                if($request->has('category')){
                    $category=explode(',',$request->category);
                   // $category=$request->category;
                    if(!empty($vendorProduct)){
                        $vendorProduct->with('product')->whereHas(
                            'product',function($q) use($category){

                                //$q->whereIn('category_id', $category);
                                $condition = ' ';

                                foreach ($category as $cat){
                                    $condition.="FIND_IN_SET('".$cat."',category_id) or ";
                                }

                            $condition =  rtrim($condition,' or ');
                               // echo $condition;die;
                                 $q->whereRaw($condition);
                                 //dd($q->toSql());
                            });
                    }
                }

                if($request->filled('search')){
                    $search = $request->search;
                    if(!empty($vendorProduct)){
                         $vendorProduct->whereHas('Product.translations', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('keywords', 'like', '%' . $search . '%');
                        });
                    }
                   
                }

        if($request->has('offer')){
                    if(!empty($vendorProduct)){
                        $vendorProduct->has('offer');
                        if($request->offer != ''){
                            //$vendorProduct->where(['offer_id'=>$request->offer]);
                             $vendorProduct->where('offer_id','>',0);
                        }
                    }
                }
                if($request->has('sort_by')){
                    if(!empty($request->sort_by)){
                        if($request->sort_by=='lth'){
                            $vendorProduct->OrderBy('price');
                        }elseif($request->sort_by=='htl'){
                              $vendorProduct->OrderBy('price','DESC');
                        }

                    }
                }



            //$vendorProduct= $vendorProduct->paginate(config('setting.pagination_limit'))->toArray();
                if(!empty($vendorProduct)){
                    $vendorProduct= $vendorProduct->paginate(50)->toArray();

                }
            $data=[];
             //   return  $vendorProduct;
            //    dd($vendorProduct);
            if(!empty($vendorProduct)){
           
                     if($request->has('is_favourite') && $request->is_favourite==1){
        	                foreach ($vendorProduct['data'] as $rec){
        	                	if(isset($rec['wish_list']) && $rec['wish_list'] != null){
        	                		$rec['match_in_zone']=$match_in_zone;
        			                $rec['product']['image'] = $rec['product']['image']['name'];
        			                unset($rec['product']['related_products']);
        		              	 	$data[] = $rec;
        	                	}
        	                }
                    	}else{
                    		foreach ($vendorProduct['data'] as $rec){
        		                $rec['match_in_zone']=$match_in_zone;
        		                $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
        		                unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
        		                $data[]=$rec;
        		            }
                    	}

                   //return $data;
                	
                    unset($vendorProduct['data']);
                    $vendorProduct['product'] = $data;
         }
            $subcategory =[];
            if($request->filled('category')){

                $subcategory = Category::whereIn('parent_id',explode(',',$request->category))->listsTranslations('name','id')->get();
            }
            $vendorProduct['subcategory'] =  $subcategory;
            $AppSetting = AppSetting::first();
            $vendorProduct['min_order']= $AppSetting->mim_amount_for_order;
     if(!empty(Auth::guard('api')->user()->membership) && (Auth::guard('api')->user()->membership_to>=date('Y-m-d H:i:s')) ){
            $vendorProduct['min_free_delivery']= $AppSetting->mim_amount_for_free_delivery_prime;
     }else{
        $vendorProduct['min_free_delivery']= $AppSetting->mim_amount_for_free_delivery;
     }
            
            return $this->showResponse($vendorProduct,null,0,['match_in_zone'=>$match_in_zone,'zone_id'=>$zone_id,'zone_name'=>$zone_name]);

        } catch (\Exception $e) {
            return $e; die;
            return $this->clientErrorResponse($e);
        }


    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = Auth::guard('api')->user();
        //    DB::enableQueryLog();
            $vendorProduct = $this->vendorProduct->whereHas('product')->with(['product','product.MeasurementClass','product.images','cart'=>function($q) use($user){
                $q->where(['user_id'=>$user->id,'zone_id'=>$user->zone_id]);
            },'wishList'=>function($q) use($user){
                $q->where(['user_id'=>$user->id]);
            }])->find($id);
            
            // dd(DB::getQueryLog());
            if(!$vendorProduct){
                return ResponseBuilder::error("Vendor Product not found", 404);
            }
            // $vendorProduct->related_products= Helper::relatedProducts($vendorProduct->product->related_products,$vendorProduct->user_id);
            $this->response->vendorProduct = new VendorProductDetailedResource($vendorProduct);
            // dd($vendorProduct->user->zone_id);
            $this->response->related_products = Helper::relatedProducts($vendorProduct->product->related_products,$vendorProduct->user_id,true,$vendorProduct->user->zone_id);
            $this->response->variant_products = Helper::relatedProducts($vendorProduct->product->variant_products,$vendorProduct->user_id,true,$vendorProduct->user->zone_id);
            $this->response->similar_products = Helper::similarProducts($vendorProduct->product->category_id,$vendorProduct->user->zone_id);
            return ResponseBuilder::success($this->response);
        } catch (\Exception $e) {
            return ResponseBuilder::error($e, 500);
        }
    }

    public function getRelatedProducts($id){
        try{
            $related_product_ids = Product::where('id',$id)->pluck('related_products')->first();
        }catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    public function productDetails(Request $request)
    {
        $id = $request->product_id;
        $validator = Validator::make($request->all(), [
            'lat'=>'required',
            'lng' => 'required',
            'product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }
        try {


            $zonedata = $this->getZoneData($request->lat, $request->lng);
            $zone_id =  $zonedata['zone_id'];
            //return $zone_id;
            $match_in_zone = $zonedata['match_in_zone'];
             if(Auth::guard('api')->user()){
            $vendorProduct = $this->vendorProduct->whereHas('product')->with(['product.MeasurementClass','product.images','User','cart'=>function($q)use($zone_id){
                $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>$zone_id]);
            },'wishList'=>function($q){
                $q->where(['user_id'=>Auth::guard('api')->user()->id]);
            }])->findOrFail($id);
             $vendorProduct->related_products= Helper::relatedProducts($vendorProduct->product->related_products,$vendorProduct->user_id, $match_in_zone,$zone_id);
    
            }else{
                 $vendorProduct = $this->vendorProduct->whereHas('product')->with(['product.MeasurementClass','product.images','User'])->findOrFail($id);
                 $user  = User::select('*');
            $user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);
            $user = $user->get();

                $useridarray =[];
                foreach($user as $userkey=>$uservalue){
                        $useridarray[]= $uservalue->id;
                }

              $product = Product::whereIn('id', $vendorProduct->product->related_products)->with([
                 'MeasurementClass','image'])->get();
                $data = [];
            if (!empty($product)) {
                foreach ($product as $rec) {
                    $is_related = VendorProduct::With(['product','product.MeasurementClass','product.images','User'])->whereIn('user_id',$useridarray)->where('product_id',$rec['id'])->first();
                if(!empty($is_related)){
                   $image = isset($rec['image']['name']) ? $rec['image']['name'] : Helper::imageNotFound(null);
                    unset($is_related['product']['offer'],$is_related['product']['image'], $is_related['product']['related_products']/*,$rec['product']['category_id']*/);
                    $is_related['product']['image'] = $image;
                    $is_related['product']['match_in_zone'] = $match_in_zone;
                      $data[] = $is_related;
                   }
                    
                }
            }
            $vendorProduct->related_products =  $data;
            }
            $vendor_zone_id = $vendorProduct->user->zone_id;
            $vendorProduct["match_in_zone"] = $match_in_zone;

            //echo "<pre>"; print_r($vendorProduct->product); die;
           // $image =  $vendorProduct->product->image->name;
            unset(/*$vendorProduct['offer'],*/$vendorProduct['product']['related_products']/*,$vendorProduct['product']['image']*/);
           /// $vendorProduct['product']['image'] =$image;
            return $this->showResponse($vendorProduct);

        } catch (\Exception $e) { return $e;
            return $this->clientErrorResponse($e);
        }
    }
    public function isPointInPolygon($latitude, $longitude, $latitude_array, $longitude_array) {
        $size = count($longitude_array);
        $flag1 = false;
        $k = $size - 1;
        $j = 0;
        while ($j < $size) {
            $flag = false;
            $flag2 = false;
            $flag3 = false;
            if ($latitude_array[$j] > $latitude) {
                $flag2 = true;
            } else {
                $flag2 = false;
            }
            if ($latitude_array[$k] > $latitude) {
                $flag3 = true;
            } else {
                $flag3 = false;
            }
            $flag = $flag1;
            if ($flag2 != $flag3) {
                $flag = $flag1;
                if ($longitude < (($longitude_array[$k] - $longitude_array[$j]) * ($latitude - $latitude_array[$j])) / ($latitude_array[$k] - $latitude_array[$j]) +
                    $longitude_array[$j]) {
                    if (!$flag1) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
            }
            $k = $j;
            $j++;
            $flag1 = $flag;
        }
        return $flag1;
    }
    public function getZoneData($lat, $lng)
    {
        $zone_id = '';
        $zoneArray = [];
        $zArray = [];
        $fArray = [];
        $finalArray = [];
      
        $zonedata = DB::table('zones')->select('id',DB::raw("ST_AsGeoJSON(point) as json") )->where('deleted_at',null)->where('status','=','1')->get();
      
            $json_arr = json_decode($zonedata, true);
            foreach ($json_arr as $zvalue) {
                $zone_id=$zvalue['id'];
                $json=json_decode($zvalue['json']);
                $coordinates=$json->coordinates;
                $new_coordinates=$coordinates[0];
                $lat_array=array();
                $lng_array=array();
                foreach($new_coordinates as $new_coordinates_value){
                    $lat_array[]=$new_coordinates_value[0];
                    $lng_array[]=$new_coordinates_value[1];


                }
           
            $is_exist = $this->isPointInPolygon($lat, $lng,$lat_array,$lng_array);
           
            if($is_exist){
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                return $data;
            }

            }
            $zone = Zone::where('status','=','1')->where('is_default','=','1')->withoutGlobalScope(StatusScope::class)->first();
            $zone_id_default = $zone ? $zone->id : 1;
            $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
            $data['match_in_zone'] = false;
            $data['zone_id'] = $zone_id_default;
            $data['zone_name'] = $zData->name;
            return $data;
       
       

       
    }

    //get popular search products
    public function getPopularSearchProducts(){
        try{
            $products = SearchQueries::query()->select('query as keyword')->where('count','>=', 5)->groupBy('query')->orderBy('count','DESC')->limit(10)->get();
            $this->response->popular_searches = $products;
            return ResponseBuilder::success($this->response);
        }catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    public function search(Request $request){
        try{
            $keyword = $request->keyword;
            $products = $this->vendorProduct->with(['product','product.MeasurementClass','product.image'])->whereHas('Product.translations',function($q) use($keyword){
                $q->where('name','like', '%'.$keyword.'%');
                $q->orWhere('keywords','like', '%'.$keyword.'%');
            });

            if($request->has('category_id')){
                $category_id = $request->category_id;
                $products = $products->whereHas('product',function($q){ $q->where('status','1'); })->whereHas('product.category',function($q) use($category_id){
                    $q->whereRaw('FIND_IN_SET('.$category_id.', category_id) ');
                });
            }
            $products = $products->paginate(20);
            //save to search query
            if(!empty($keyword)){
                SearchQueries::query()->updateOrCreate(['query'=>$keyword],['count'=>DB::raw('count+1')]);
                $this->response->searchSuggestions = $this->searchSuggestion();
            }
            $this->response->vendorProduct = VendorProductResource::collection($products);
            
            return ResponseBuilder::successWithPagination($products, $this->response);
        }catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    //search suggestion
    public function searchSuggestion(){
        try{
            $keyword = \request()->keyword;
            $products = $this->vendorProduct->with(['product','product.MeasurementClass','product.image'])->whereHas('Product.translations',function($q) use($keyword){
                $q->where('name','like', '%'.$keyword.'%');
                $q->orWhere('keywords','like', '%'.$keyword.'%');
            })->limit(10)->get();
            $suggestions = [];
            foreach($products as $product){
                $suggestions[] = $product->product->name;
            }
            return $suggestions;
        }catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }

    public function searchproduct($keyword){

        $productlist =  $this->vendorProduct->with('Product')->whereHas('Product.translations', function($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('keywords', 'like', '%' . $keyword . '%');
                        })->get();

        foreach($productlist as $kk=>$vv){
            $vv->name = $vv->Product->name;
            unset($vv->Product);
        }
        return $productlist;
    }

    public function getTopSellingProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'lat'=>'required',
            'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        }
        try {
            if(Auth::guard('api')->user()){
                $zonedata = $this->getZoneData($request->lat, $request->lng);
                $zone_id =  $zonedata['zone_id'];
                $zone_name =  $zonedata['zone_name'];
                $match_in_zone = (bool)$zonedata['match_in_zone'];
            }else{
                $zonedata =   $this->zone->where('is_default','1')->where('status','1')->first();
                $zone_id =  $zonedata->id;
                $zone_name =  $zonedata->name;
                $match_in_zone = true;
            } 
            
            $user  = User::select('*');
            $user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);
            $user = $user->get();
            if(!isset($user)){
                 $vendorProduct = [];
            }else{
                $useridarray = [];
                foreach($user as $userkey=>$uservalue){
                    $useridarray[] = $uservalue->id;
                }

                $results = ProductOrderItem::select(DB::raw( "vendor_product_id,COUNT(id) as cnt"))->groupBy('vendor_product_id')->orderBy('cnt', 'DESC')->paginate(20)->toArray();
                $resultArray=[];
                if(!empty($results['data'])){
                    foreach ($results['data'] as $result) {
                        $pid = $result['vendor_product_id'];
                        if(Auth::guard('api')->user()){
                            $vendorProduct = $this->vendorProduct
                                ->with([
                                    'product.MeasurementClass',
                                    'product.image','cart'=>function($q){
                                        $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>Auth::guard('api')->user()->zone_id]);
                                    },
                                    'wishList'=>function($q){
                                        $q->where(['user_id'=>Auth::guard('api')->user()->id]);
                                    }
                                ])
                                ->where('id',$pid)->first();

                        }else{
                             $vendorProduct = $this->vendorProduct
                            ->with(['product.MeasurementClass','product.image'])->where('id',$pid)->first();
                        }

                        $data=[];
                        if(!empty($vendorProduct)){
                            $vendorProduct['product']['image'] = isset($vendorProduct['product']['image']['name']) ? $vendorProduct['product']['image']['name'] : '';
                            $vendorProduct['match_in_zone'] = $match_in_zone;
                        }
                        $resultArray[] = $vendorProduct;
                    }
                }

            }
            
            return $this->showResponse($vendorProduct);

        }catch (\Exception $e) {
            return $e; die;
            return $this->clientErrorResponse($e);
        }        

    }
    public function offerdata($zone_id){
        $user = User::select('*');
        $user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
        $user = $user->get()->toArray();
        $product_data=[];
        $user_id_array=[];
        
        foreach($user as $kk=>$vv){
            $user_id_array[] = $vv['id'];
            $product_data[$vv['id']] = $this->vendorProduct->where('user_id',$vv['id'])->where('status','1')->get()->toArray();
        }
        //dd('pppppp');
        $offer_product_id_array = [];
        $offer_products = $this->vendorProduct->with(['Product','product.MeasurementClass','product.image',
            'cart'=>function($q) use($zone_id){
                $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
            },
            'wishList'=>function($q){
                $q->where(['user_id'=>Auth::user()->id]);
            }
        ])
        ->whereHas('product',function($q){ $q->where('status','1'); })->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id')->get();

        foreach($offer_products as $offer_product){
            $pffer_data = $this->offer->where('id',$offer_product->offer_id)->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
            
            if(!empty($pffer_data)){
                $offer_product_id_array[] = $offer_product->product_id;
            }
        }

        $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
            'cart'=>function($q) use($zone_id){
                $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
            },
            'wishList'=>function($q){
                $q->where(['user_id'=>Auth::user()->id]);
            }
        ])
        ->whereHas('product',function($q){ $q->where('status','1'); })->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id');

        if(!empty($vendorProduct)){
            $vProduct = $vendorProduct= $vendorProduct->groupBy('product_id')->take(10000)->get();
            $vendorProduct= $vendorProduct->toArray();
        }

        $data=[];
        if(!empty($vendorProduct)){
            foreach ($vendorProduct as $rec){
                $rec = $rec;
                $rec['price'] = number_format($rec['price'],2,'.','');   
                $rec['offer_price'] = number_format($rec['price'],2,'.','');   
       
                $rec['offer_data'] =   $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
                
                if(!empty($ffer_data)){
                    $rec['is_offer'] = true;
                    $rec['offer_id'] = $rec['offer_id'];
                    
                    if($ffer_data->offer_type=='amount'){
                        $rec['offer_price'] = $rec['price']- $ffer_data->offer_value;
                    }else if($ffer_data->offer_type=='percentages'){
                        $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 )) ;                 
                    }
                    
                    $rec['offer_price'] = number_format( $rec['offer_price'],2,'.','');   
                    $data[] = $rec;                       
                }     
            }
            unset($vendorProduct);
            $vendorProduct = $data; 
        }

        $data=[];
        if(!empty($vendorProduct)){
            foreach ($vendorProduct as $rec){
                $rec['match_in_zone']=true;
                $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
                unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
                $data[]=$rec;
            }
            unset($vendorProduct);
            $vendorProduct = $data; 
        }
        //echo "<pre>"; print_r($vendorProduct); die;
        return $vendorProduct;
     }

    public function getHomedata(Request $request)
    {
        //get lat lng from header
        $lat = request()->header('lat');
        $lng = request()->header('lng');

        // $lat = $request->lat;
        // $lng = $request->lng;
        $category =  $this->category->join('categories','categories.id','=','category_translations.category_id')->select('categories.id','image','name','category_translations.slug')->where(['locale' => 'en'])->where(['categories.parent_id' => '0'])->where(['categories.status' => '1'])->whereNull('categories.deleted_at')->orderBy('sort_no', 'ASC')->get();
            
        $slider = Slider::with('category','sub_category','product');
        $offer_sliders = OfferSlider::with('category','sub_category','product');
        $ads = Ads::with('category','sub_category','product');
        $user = Auth::guard('api')->user();
        $zonedata = $this->getZoneData($lat, $lng);
        $zone_id =  $zonedata['zone_id'];
        $zone_name =  $zonedata['zone_name'];
        $match_in_zone = $zonedata['match_in_zone'];
           // $user->zone_id = $zonedata['zone_id'];
            
            //$user->save();
            //$request->session()->put('zone_id',$zone_id);
          
        if(empty($zone_id)){
            $first_zone =  $Zone= Helper::Zone_list();//Zone::where('status',1)->first(); 
            $zone_id = $first_zone[0]->id;
            $request->session()->put('zone_id',$zone_id);
        }
        $user->zone_id = $zone_id;
        $user->save();

        $vendor  = User::select('*');
        $vendor->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);
        $slider = $slider->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
        foreach( $slider as $sliders){
            if($sliders->link_type=='internal'){
                if($sliders->link_url_type=='product'){
                    $vproduct = $this->vendorProduct->where('id',$sliders->vendor_product_id)->first();
                    if(!empty($vproduct)){
                        
                    $product = ProductTranslation::where('product_id',$vproduct->product_id)->first();
                     if(!empty($product)){
                    $sliders->rawslug = $product->slug;
                      } 
                   }else{ $sliders->rawslug = "";

                   }
                }else if($sliders->link_url_type=='category'){
                    $subcat = CategoryTranslation::where('category_id',$sliders->cat_id)->first();
                    if(!empty($subcat)){ $sliders->rawslug = $subcat->slug; }
                     else{ $sliders->rawslug = "";}
                }else if($sliders->link_url_type=='subcategory'){
                     $subcat = CategoryTranslation::where('category_id',$sliders->sub_cat_id)->first();
                    if(!empty($subcat)){$sliders->rawslug = $subcat->slug; }
                    else{  $sliders->rawslug = "";}
                }
            }
        }
        $ads = $ads->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
        foreach( $ads as $ad){
            if($ad->link_type=='internal'){
                if($ad->link_url_type=='product'){
                    $vproduct = $this->vendorProduct->where('id',$ad->vendor_product_id)->first();
                    if(!empty($vproduct)){
                        $product = ProductTranslation::where('product_id',$vproduct->product_id)->first();
                        if(!empty($product)){
                            $ad->rawslug = $product->slug;
                        } 
                    }else{ 
                        $ad->rawslug = "";
                    }
                }else if($ad->link_url_type=='category'){
                    $subcat = CategoryTranslation::where('category_id',$ad->cat_id)->first();
                    if(!empty($subcat)){ $ad->rawslug = $subcat->slug; }
                    else{ $ad->rawslug = "";}
                }else if($ad->link_url_type=='subcategory'){
                    $subcat = CategoryTranslation::where('category_id',$ad->sub_cat_id)->first();
                    if(!empty($subcat)){$ad->rawslug = $subcat->slug; }
                    else{  $ad->rawslug = "";}
                }
            }
        }
        $offer_sliders =  $offer_sliders->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
        foreach( $offer_sliders as $offer_slider){
            if($offer_slider->link_type=='internal'){
                if($offer_slider->link_url_type=='product'){
                    $vproduct = $this->vendorProduct->where('id',$offer_slider->vendor_product_id)->first();
                    if(!empty($vproduct)){
                        $product = ProductTranslation::where('product_id',$vproduct->product_id)->first();
                        if(!empty($product)){
                            $offer_slider->rawslug = $product->slug;
                        } 
                    }else{ $offer_slider->rawslug = "";

                    }
                }else if($offer_slider->link_url_type=='category'){
                    $subcat = CategoryTranslation::where('category_id',$offer_slider->cat_id)->first();
                    if(!empty($subcat)){ $offer_slider->rawslug = $subcat->slug; }
                    else{ $offer_slider->rawslug = "";}
                }else if($offer_slider->link_url_type=='subcategory'){
                    $subcat = CategoryTranslation::where('category_id',$offer_slider->sub_cat_id)->first();
                    if(!empty($subcat)){$offer_slider->rawslug = $subcat->slug; }
                    else{  $offer_slider->rawslug = "";}
                }
            }
        }
        
        $brands = Brand::with('barndTraslation')->limit(6)->get();
         
        $offerProduct  =  $this->offerdataHOme($zone_id);
        $topsellingproducts  =  $this->topsellingproducts($zone_id);
        $super_deal = $this->superDeal($zone_id);
        $appdata =  AppSetting::select(['mim_amount_for_order','mim_amount_for_free_delivery','mim_amount_for_free_delivery_prime','mim_amount_for_order_prime'])->first();
        $homeStrip = "Min ₹".$appdata->mim_amount_for_order." for order & Min. ₹".$appdata->mim_amount_for_free_delivery." for free delivery";
        
        $sliders = array("data"=>$slider, "type"=>"slider");
        $homeStrip = array("data"=>$homeStrip, "type"=>"homeStrip");
        $offerProducts = array("data"=>$offerProduct, "type"=>"offerProduct","heading"=>'Weekly Offer Products','api_url'=>'get-all-weekly-Offer-products');
        $categorys = array("data"=>$category, "type"=>"category",'api_url'=>'category');
        $adss = array("data"=>$ads, "type"=>"ads");
        $offer_sliderss = array("data"=>$offer_sliders, "type"=>"offer_sliders");
        $topsellingproductss = array("data"=>$topsellingproducts, "type"=>"product","heading"=>'Top selling products','api_url'=>'get-all-top-selling-products');
        $super_dealss = array("data"=>$super_deal, "type"=>"product","heading"=>'Super Deals', 'api_url'=>'get-all-super-deal-products');
        $brands = array('brands'=>$brands, "type"=>"brands",'api_url'=>'brands');

        // $this->response->sliders = $slider;
        // $this->response->homeStrip = $homeStrip;
        // $this->response->offerProducts = $offerProduct;
        // $this->response->categories = $category;
        // $this->response->adss = $ads;
        // $this->response->zone_ids = $zone_id;
        // $this->response->appdatas = $appdata;
        // $this->response->offer_sliders = $offer_sliders;
        // $this->response->topsellingproducts = $topsellingproducts;
        // $this->response->super_deals = $super_deal;
        // $this->response->brands = $brands;

        //return ResponseBuilder::success($this->response);
        //$ldata = array($categorys,$sliders,$adss,$offerProducts,$zonss,$appdatas,$offer_sliderss,$topsellingproductss,$super_dealss,$brands);
        $ldata = ['data' => ['data' => [$sliders,$homeStrip,$offerProducts,$categorys,$adss,$topsellingproductss,$offer_sliderss,$super_dealss,$brands], 'match_in_zone' => $match_in_zone]];
        return response()->json($ldata);
     } 




    public function offerdataHOme($zone_id){
       
        
        $user_id_array = User::whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor'])->get()->pluck('id')->toArray();
        $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
            'cart'=>function($q) use($zone_id){
                $q->where(['zone_id'=>$zone_id]);
            }
        ])
        ->whereHas('product',function($q){ $q->where('status','1'); })->whereIn('user_id',$user_id_array)->whereHas('Offer')->limit(20)->get();
        

        return VendorProductResource::collection($vendorProduct);
    }
    public function topsellingproducts($zone_id){
        $user_id_array = User::whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor'])->get()->pluck('id')->toArray();
        //print_r($user_id_array); die();
        // DB::enableQueryLog();
        $vendoreProductIds = ProductOrderItem::select(DB::raw( "vendor_product_id,COUNT(id) as cnt"))->groupBy('vendor_product_id')->orderBy('cnt', 'DESC')->get()->pluck('vendor_product_id')->toArray();
        // dd($vendoreProductIds);
        $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
            'cart'=>function($q) use($zone_id){
                $q->where(['zone_id'=>$zone_id]);
            }
        ])
        ->whereHas('product')
        ->whereIn('user_id',$user_id_array)->whereIn('id',$vendoreProductIds)->limit(20)->get();
        
        // dd(DB::getQueryLog());

        return VendorProductResource::collection($vendorProduct);
    }
 
    public function superDeal($zone_id) {
		$user_id_array  = User::whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor'])->get()->pluck('id')->toArray(); 
		$vendorProduct = VendorProduct::with(['product.MeasurementClass','product.image'])->whereHas('product', function($q){
			$q->whereHas('category.translations', function($q){
				$q->where('name', 'SUPER DUPER OFFER');
			});
		})->whereIn('user_id',$user_id_array)->limit(10)->get();
		return VendorProductResource::collection($vendorProduct);
	}
    public function getAllSuperDealProducts(){
        try{
            $lat = request()->header('lat');
            $lng = request()->header('lng');
            $zone = $this->getZoneData($lat, $lng);
            $zone_id = $zone['zone_id'];
            $user_id_array  = User::whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor'])->get()->pluck('id')->toArray(); 
            // $category_name = 'SUPER DUPER OFFER';
            $vendorProduct = VendorProduct::with(['product.MeasurementClass','product.image'])->whereHas('product', function($q){
                $q->whereHas('category.translations', function($q){
                    $q->where('name', 'SUPER DUPER OFFER');
                });
            })->whereIn('user_id',$user_id_array)->paginate(20);
            $this->response->vendorProduct = VendorProductResource::collection($vendorProduct);
            return ResponseBuilder::successWithPagination($vendorProduct, $this->response);
        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), 500);
        }
    }
    public function getWeeklyOfferProducts(){
        try{
            $lat = request()->header('lat');
            $lng = request()->header('lng');
            $zone = $this->getZoneData($lat, $lng);
            $zone_id = $zone['zone_id'];
            $user_id_array = User::whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor'])->get()->pluck('id')->toArray();
            $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
                'cart'=>function($q) use($zone_id){
                    $q->where(['zone_id'=>$zone_id]);
                }
            ])
            ->whereHas('product',function($q){ $q->where('status','1'); })->whereIn('user_id',$user_id_array)->whereHas('Offer')->paginate(20);
    
            $this->response->vendorProduct = VendorProductResource::collection($vendorProduct);
            return ResponseBuilder::successWithPagination($vendorProduct, $this->response);
        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $e->getCode());
        }
    }

    public function getAllTopSellingProducts(){
        $lat = request()->header('lat');
        $lng = request()->header('lng');
        $zone = $this->getZoneData($lat, $lng);
        $zone_id = $zone['zone_id'];
        $user_id_array = User::whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor'])->get()->pluck('id')->toArray();
        //print_r($user_id_array); die();

        $vendoreProductIds = ProductOrderItem::select(DB::raw( "vendor_product_id,COUNT(id) as cnt"))->groupBy('vendor_product_id')->orderBy('cnt', 'DESC')->get()->pluck('vendor_product_id')->toArray();
        //dd($results);
        if(Auth::guard()->user()){
            $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
                'cart'=>function($q) use($zone_id){
                    $q->where(['zone_id'=>$zone_id]);
                }
            ])
            ->whereHas('product',function($q){ $q->where('status','1'); }  )
            ->whereIn('user_id',$user_id_array)->whereIn('id',$vendoreProductIds)->paginate(20);
        }else{
                $vendorProduct = $this->vendorProduct->with(['product.MeasurementClass','product.image'])
                ->whereHas('product',function($q){ $q->where('status','1'); })
                ->whereIn('id',$vendoreProductIds)
                ->whereIn('user_id',$user_id_array)
                ->paginate(20);
        }
        $this->response->vendorProduct = VendorProductResource::collection($vendorProduct);
        return ResponseBuilder::successWithPagination($vendorProduct, $this->response);
        //$response_array = [];
        // if(!empty($results['data'])){
        //   foreach ($results['data'] as $result) {
        //     $pid = $result['vendor_product_id'];
        //     if(Auth::guard()->user()){
        //       $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
        //         'cart'=>function($q) use($zone_id){
        //           $q->where(['zone_id'=>$zone_id]);
        //         }
        //       ])
        //       ->whereHas('product',function($q){ $q->where('status','1'); }  )
        //       ->whereIn('user_id',$user_id_array)->where('id',$pid);
              
        //       if(!empty($vendorProduct)){
        //         $vendorProduct= $vendorProduct->first();
        //       }
        //     }else{
        //       $vendorProduct = $this->vendorProduct->with(['product.MeasurementClass','product.image'])->where('id',$pid)->first();
        //     }
        //     /*if(!empty($vendorProduct)){
        //       echo $vendorProduct->price;
        //     }*/
        //     $response_array[] = $vendorProduct;

        //   }
        //   //print_r($response_array); die();
        //  // die();
        //   $data=[];
        //   if(!empty($response_array)){
        //     foreach ($response_array as $rec){
        //       $rec['price'] = number_format($rec['price'],2,'.','');
        //       $rec['wish_list'] = isset($rec['wishList'])?$rec['wishList']:''; 
        //       $rec['mrp'] = number_format(!empty($rec['best_price']) ? $rec['best_price']:$rec['price'],2,'.','');   
        //       $rec['offer_price'] = number_format($rec['price'],2,'.','');   
        //       if(!empty($rec['offer_id'])){
        //         $rec['offer_data'] =   $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
        //         if(!empty($ffer_data)){
        //           $rec['is_offer'] = true;
        //           if($ffer_data->offer_type=='amount'){
        //             $rec['offer_price'] = $rec['price']- $ffer_data->offer_value;
        //           }else if($ffer_data->offer_type=='percentages'){
        //             $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 ));
        //           }
        //           $rec['offer_price'] = number_format( $rec['offer_price'],2,'.',''); 
        //           $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $rec['price']:$rec['best_price'],2,'.','');  
        //           $data[]=$rec;                       
        //         }  
        //       }    
        //     }
            
        //     unset($response_array);
        //     $response_array = $data; 
        //   }

        //   $data=[];
        //   if(!empty($response_array)){
        //     foreach ($response_array as $rec){
        //       $rec['match_in_zone']=true;
        //       $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
        //       unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
        //       $data[]=$rec;
        //     }
        //     unset($response_array);
        //     $response_array = $data; 
        //   }
        // }

        //return $response_array;
      }

    public function seeAll(){
        try{
           $lat = request('lat');
           $lng = request('lng');
           $zone = $this->getZoneData($lat, $lng);
           $zone_id = $zone['zone_id'];
           $type = request('type');
           switch($type){
                case 'top_selling':
                      $vendorProduct = $this->getAllTopSellingProducts($zone_id);
                      break;
                case 'new_arrival':
                      $vendorProduct = $this->newArrivalProducts($zone_id);
                      break;
                case 'weekly_offer':
                      $vendorProduct = $this->weeklyOfferProducts($zone_id);
                      break;
           }
            $this->response->vendorProduct = VendorProductResource::collection($vendorProduct);
            return ResponseBuilder::successWithPagination($vendorProduct, $this->response);
        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $e->getCode());
        }
    }

    //get category Products
    public function categoryProducts($category_id){
        try{
            $lat = request()->header('lat');
            $lng = request()->header('lng');
            $zone = $this->getZoneData($lat, $lng);
            // dd($zone);
            $zone_id = $zone['zone_id'];
            $user_id_array = User::whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor'])->get()->pluck('id')->toArray();


            $vendorProduct = $this->vendorProduct->with(['product.MeasurementClass','product.image',
                'cart'=>function($q) use($zone_id){
                    $q->where(['zone_id'=>$zone_id]);
                }
            ])
            ->whereHas('product',function($q){ $q->where('status','1'); })->whereHas('product.category',function($q) use($category_id){
                $q->whereRaw('FIND_IN_SET('.$category_id.', category_id) ');
            })->whereIn('user_id',$user_id_array)->paginate(20);

            //get category details
            $category = Category::query()->with('translations')->where('id',$category_id)->first();
            $this->response->vendorProduct = VendorProductResource::collection($vendorProduct);
            $this->response->category = new CategoryResource($category);
            $this->response->match_in_zone = $zone['match_in_zone'];
            return ResponseBuilder::successWithPagination($vendorProduct, $this->response);
        }catch(\Exception $e){
            return $e;
            //return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    //getAllOffers
    public function getAllOffers(){
        try{
            //get offers
            $offers = Offer::with(['translations'])->paginate(20);
            $this->response->offers = OfferResource::collection($offers);
            return ResponseBuilder::successWithPagination($offers, $this->response);
        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    //getAllCoupons
    public function getAllCoupons(){
        try{
            //get offers
            $coupons = Coupon::with(['translations'])->paginate(20);
            $this->response->coupons = CouponResource::collection($coupons);
            return ResponseBuilder::successWithPagination($coupons, $this->response);
        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    //function to add to notify me
    public function addToNotifyMe(Request $request){
        try{
            //write laravel validation here product id required
            $validator = Validator::make($request->all(), [
                'product_id' => ['required', Rule::exists('vendor_products','id')->where(function ($query) {
                    $query->where('status', '1');
                })],
            ]);

            //if validation fails
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->validationStatus);
            }

            $user = $request->user('api');
            $product_id = $request->product_id;

            //save or update to notify me
            NotifyMe::updateOrCreate(
                ['user_id' => $user->id, 'product_id' => $product_id],
                ['user_id' => $user->id, 'product_id' => $product_id]
            );

            return ResponseBuilder::success(__('api.notification.added_to_notify_me'));

        }catch(\Exception $e){
            return $e;
            // return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

}

