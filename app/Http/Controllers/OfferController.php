<?php

namespace App\Http\Controllers;

use App\Category;
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
use App\Offer;
use App\OfferSlider;
use App\Zone;
use App\ZoneTranslation;
use DB;
use App;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class  OfferController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Wallet Controller
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

    use RestControllerTrait, ResponceTrait;

    const MODEL = 'App\VendorProduct';
    /**
     * @var Contact
     */
    private $Product;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $validationRules;

    public function __construct(Request $request, VendorProduct $vendorProduct, Product $product, Offer $offer, User $user,Category $category)
    {

        parent::__construct();

        $this->vendorProduct = $vendorProduct;
        $this->product = $product;
        $this->offer = $offer;
        $this->user = $user;
        $this->category = $category;
        $this->method = $request->method();
        $this->validationRules = $this->vendorProduct->rules($this->method);
    //    $this->middleware('auth');
    }


    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //    public function __construct()
    //    {
    //        $this->middleware('guest')->except('logout');
    //    }

   

    public function index(Request $request,$slug){

        $zone_id = $request->session()->get('zone_id');
        if(empty($zone_id)){ $zone_id = Auth::user()->zone_id;  }
        $vendor_zone_id = $zone_id;
        $user_id = Auth::user()->id;
        $match_in_zone = true;
        $products_collection = array();
        $user = User::select('*');
        $user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
        $user = $user->get()->toArray();

        $slider = OfferSlider::with('category','sub_category','product');
        $slider = $slider->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
          

            $product_data=[];
            $user_id_array=[];
            foreach($user as $kk=>$vv){
              $user_id_array[] = $vv['id'];
              $product_data[$vv['id']] = $this->vendorProduct->where('user_id',$vv['id'])->where('status','1')->get()->toArray();
            }
             $product_id_array = [];
             $products = $product= [];

            foreach($product_data as $kp=>$vp){
                foreach($vp as $k1=>$v1){
                     $product_id_array[$kp.'_'.$k1] =$v1['product_id'];
                     $vendorProductdata[$v1['product_id']][$kp]=['user_id'=>$v1['user_id'],'qty'=>$v1['qty']];
               }
           }
                     $offer_product_id_array = [];
                      $offer_products = $this->vendorProduct->whereNOTNULL('offer_id')->get();              
                        foreach($offer_products as $offer_product){
                            $pffer_data = $this->offer->where('id',$offer_product->offer_id)->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
                            if(!empty($pffer_data)){
                                $offer_product_id_array[] = $offer_product->product_id;
                           }
                          }
                $vendorProduct =  $this->vendorProduct->with([
                    'product.MeasurementClass',
                    'product.image','cart'=>function($q) use($zone_id){
                        $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
                    },'wishList'=>function($q){
                        $q->where(['user_id'=>Auth::user()->id]);
                    }])->whereHas('product',function($q){ $q->where('status','1'); }  )->whereIn('user_id',$user_id_array)->whereIn('product_id',$offer_product_id_array);

 $parent_data = [];
     if ($slug && !empty($slug) && $slug!='all') {
            $category = Helper::get_category_id($slug); 
            $category_data = $this->category->where('id',$category)->first();
            if($category_data->parent_id>0){
                $parent_data = $this->category->where('id',$category_data->parent_id)->first()->toArray();
            }
              if(isset($category)) {
                $category_product = [];
                 if(!empty($vendorProduct)){
                        $vendorProduct->with('product')->whereHas(
                            'product',function($q) use($category){
                                $condition="FIND_IN_SET('".$category."',category_id)";
                                 $q->whereRaw($condition);
                            });
                    }
            }
        }


     
    if(!empty($vendorProduct)){
      $vProduct = $vendorProduct= $vendorProduct->groupBy('product_id')->paginate(16);
      $vendorProduct= $vendorProduct->toArray();
    }
  //echo "<pre>"; print_r($vendorProduct); die;
   $data=[];
  if(!empty($vendorProduct)){
   foreach ($vendorProduct['data'] as $rec){ 
     $rec['offer_data'] =   $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
    
    if(!empty($ffer_data)){
      $rec['offer_id'] = $rec['offer_id'];
      if($ffer_data->offer_type=='amount'){
       $rec['offer_price'] = $rec['price'] - $ffer_data->offer_value;
      }else if($ffer_data->offer_type=='percentages'){
       $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 )) ;                 
      }
     //$rec['price'] = 
      $rec['offer_price'] = number_format( $rec['offer_price'],2,'.','');   
      $data[]=$rec;                       
    }
     }   
  unset($vendorProduct['data']);
  $vendorProduct['data'] = $data; 
  }

  $data=[];
  if(!empty($vendorProduct)){
  foreach ($vendorProduct['data'] as $rec){
  $rec['match_in_zone']=$match_in_zone;
  $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
  unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
  $data[]=$rec;
  }
  //return $data;
  unset($vendorProduct['data']);
  $vendorProduct['product'] = $data; 
  }

if(empty($slug)) { $slug="all"; }
 return view('pages.offerlisting', ['products' => $vendorProduct,'vProduct'=>$vProduct,'slug' => $slug,'zone_id' => $zone_id,'Slider' => $slider,'parent_data'=>$parent_data]); 
}

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 16,$page = null,  $options = [],$slug)
    {
        $options = ['path' => url('list/'.$slug)]; 
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function getZoneData($lat, $lng)
    {
        $zone_id = '';
        $zoneArray = [];
        $zArray = [];
        $fArray = [];
        $finalArray = [];

        $zonedata = DB::table('zones')->select('id', DB::raw("ST_AsGeoJSON(point) as json"))->where('deleted_at', null)->where('status', '=', '1')->get();

        $json_arr = json_decode($zonedata, true);
        foreach ($json_arr as $zvalue) {
            $zone_id = $zvalue['id'];
            $json = json_decode($zvalue['json']);
            $coordinates = $json->coordinates;
            $new_coordinates = $coordinates[0];
            $lat_array = array();
            $lng_array = array();
            foreach ($new_coordinates as $new_coordinates_value) {
                $lat_array[] = $new_coordinates_value[0];
                $lng_array[] = $new_coordinates_value[1];


            }
            $is_exist = $this->isPointInPolygon($lat, $lng, $lat_array, $lng_array);

            if ($is_exist) {
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                return $data;
            }

        }
        $zone = Zone::where('status', '=', '1')->where('is_default', '=', 1)->withoutGlobalScope(StatusScope::class)->first();
        $zone_id_default = $zone->id;
        $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
        $data['match_in_zone'] = false;
        $data['zone_id'] = $zone_id_default;
        $data['zone_name'] = $zData->name;
        return $data;


    }

    public function isPointInPolygon($latitude, $longitude, $latitude_array, $longitude_array)
    {
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


}
