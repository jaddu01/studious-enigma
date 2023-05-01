<?php

namespace App\Http\Controllers;

use App\Category;
use App\DeliveryDay;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\SiteSetting;
use App\AppSetting;
use App\Variant;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\User;
use App\VendorProduct;
use App\Product;
use App\ProductTranslation;
use App\Offer;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\CategoryAdsImage;
use App\CategoryTranslation;

class ProductController extends Controller
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

    public function __construct(Request $request, VendorProduct $vendorProduct, Product $product, Offer $offer, User $user,Category $category ,Zone $zone, Variant $variant)
    {

      parent::__construct();

      $this->vendorProduct = $vendorProduct;
      $this->product = $product;
      $this->offer = $offer;
      $this->user = $user;
      $this->zone = $zone;
      $this->variant = $variant;
      $this->category = $category;
      $this->method = $request->method();
      $this->validationRules = $this->vendorProduct->rules($this->method);
        //$this->middleware('guest')->except('logout');
        //$this->middleware('auth');
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

    
    public function productlisting(Request $request, $slug){
      $category_data=[];
      if(Auth::user()){
        $zone_id = $request->session()->get('zone_id');
        $vendor_zone_id = $zone_id;
        $user = Auth::user();
        $user_id = Auth::user()->id;
        if (empty($zone_id)) {
          $zone_id = Auth::user()->zone_id;
          if (empty($zone_id)) { $address = $user->address;
            $deliverylocations = DeliveryLocation::where('user_id',$user->id)->get();
            if(count($deliverylocations)==0){ 
              $first_address = DeliveryLocation::where('id',$address)->first(); 
              $first_address->user_id = $user->id;
              $first_address->save();
              $zonedata = $this->getZoneData($first_address->lat, $first_address->lng);
              $vendor_zone_id = $zone_id = $zonedata['zone_id'];
              $user->zone_id = $vendor_zone_id;
              $user->save();
            }else{
              $first_address =  DeliveryLocation::where('user_id',$user->id)->first(); 
              $zonedata = $this->getZoneData($first_address->lat, $first_address->lng);
              $vendor_zone_id = $zone_id = $zonedata['zone_id'];
            }
            Session::put('zone_id',$vendor_zone_id);
          }
        }}else{ 
         $zone_id =0;
         $Zone= Zone::all();
         foreach($Zone as $k=>$v ){ if($v['is_default']){ $zone_id =$v['id'];  } }
       }
       $match_in_zone = true;
       $products_collection = array();
       $user = User::select('*');
       $user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
       $user = $user->get()->toArray();
       $product_data=[];
       $user_id_array=[];
       foreach($user as $kk=>$vv){
        $user_id_array[] = $vv['id'];
        $product_data[$vv['id']] = $this->vendorProduct->where('user_id',$vv['id'])->where('status','1')->get()->toArray();
      }
      $product_id_array = $products = $product= [];
      if(Auth::user()){
       $vendorProduct =  $this->vendorProduct->with(['product', 'product.MeasurementClass',  'product.image','cart'=>function($q) use($zone_id){
        $q->where(['user_id'=>AUth::user()->id,'zone_id'=>$zone_id]);
      },'wishList'=>function($q)  use($zone_id){
        $q->where(['user_id'=>AUth::user()->id,'zone_id'=>$zone_id]);
      }])->wherehas('product', function($q){$q->where('status','=','1');})->where('status','1')->whereIn('user_id',$user_id_array);
     }else{
       $vendorProduct =  $this->vendorProduct->with(['product','product.MeasurementClass',  'product.image'])->whereHas('product', function($q){$q->where('status','=','1');})->where('status','1')->whereIn('user_id',$user_id_array);
     }
     $parent_data = [];
     if ($slug && !empty($slug) && $slug!='all') {
      $category = Helper::get_category_id($slug); 
            //echo "<pre>";print_r($category); exit;
      $category_data = $this->category->where('id',$category)->first();
            //echo $category_data->banner_image;echo "<pre>";print_r($category_data); exit;
      if(!empty($category_data)){
        if($category_data->parent_id>0){
          $parent_data = $this->category->where('id',$category_data->parent_id)->first()->toArray();
        } 
      }

      if(isset($category)) { 
                 if(!empty($vendorProduct)){  //echo $category; die;
                  $vendorProduct->whereHas(
                    'product',function($q) use($category){
                      $condition="FIND_IN_SET('".$category."',category_id)";
                               // echo $condition; die;
                      $q->whereRaw($condition);
                    });
                }
              }
            }




            if($request->filled('search')){
              $search = $request->search;
              if(!empty($vendorProduct)){
                $vendorProduct->whereHas('product.translations', function($q) use ($search) {
                  $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('keywords', 'like', '%' . $search . '%');
                });
          //
              }
            }else{
              $request->session()->forget('searchData');
            }


            $data=[];
            if(!empty($vendorProduct)){
             $vProduct = $vendorProduct= $vendorProduct->groupBy('product_id')->paginate(16);
             if(!empty($request->search)){ 
              $vProduct->appends(['search' => $search]);
            }
            $vendorProduct =  $vendorProduct->toArray();
            foreach ($vendorProduct['data'] as $rec){
              $rec =  $rec;
              $rec['cart'] = isset($rec['cart'])?$rec['cart']:'';
              $rec['wish_list'] = isset($rec['wish_list'])?$rec['wish_list']:'';
              $rec['is_offer'] = false;
   // $rec['offer_id'] = null;
              $rec['price'] = number_format($rec['price'],2,'.','');
              $rec['mrp'] = number_format(!empty($rec['best_price']) ? $rec['best_price']:$rec['price'],2,'.','');
              $rec['offer_price'] = number_format($rec['price'],2,'.','');  
              $rec['offer_data'] =  $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
              
              $discount = ($rec['best_price'] - $rec['price']) / $rec['best_price'];
              $discount = $discount * 100;
              $rec['discount'] = number_format($discount,2,'.','');
    //$rec['discount'] = round($discount,0);
    //$rec['discount'] = $discount;

//echo "<pre>"; print_r($ffer_data); die;
              if(!empty($ffer_data)){
               $rec['is_offer'] = true;
               $rec['offer_id'] = $rec['offer_id'];
               $rec['offer_data'] = $ffer_data;
               if($ffer_data->offer_type=='amount'){
                 $rec['offer_price'] = $rec['price'] - $ffer_data->offer_value;
               }else if($ffer_data->offer_type=='percentages'){
                 $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 )) ;                 
               }
       //$rec['price'] =
               $rec['offer_price'] = number_format($rec['offer_price'],2,'.','');                              
             }else{
              $rec['price'] = number_format($rec['price'],2,'.','');
              $rec['mrp'] = number_format(!empty($rec['best_price']) ? $rec['best_price']:$rec['price'],2,'.','');
              $rec['offer_price'] = number_format($rec['price'],2,'.','');  
            }
            $data[]=$rec; }
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
          unset($vendorProduct['data']);
          $vendorProduct['product'] = $data; 
        }
        
        $categoryAds = CategoryAdsImage::with('category','sub_category','product');
        $categoryAds =  $categoryAds->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
        foreach( $categoryAds as $ad){
          if($ad->link_type=='internal'){
            if($ad->link_url_type=='product'){
              $vproduct = $this->vendorProduct->where('id',$ad->vendor_product_id)->first();
              if(!empty($vproduct)){
                
                $product = ProductTranslation::where('product_id',$vproduct->product_id)->first();
                if(!empty($product)){
                  $ad->rawslug = $product->slug;
                } 
              }else{ $ad->rawslug = "";

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

     if($slug && !empty($slug)) {   return view('pages.productlisting', ['products' => $vendorProduct,'slug' => $slug,'vProduct'=>$vProduct,'parent_data'=>$parent_data,'zone_id'=>$zone_id,'categoryAds'=>$categoryAds,'category_data'=>$category_data]);}
     else{ return view('pages.productlisting', ['products' => $vendorProduct,'slug' => 'all','vProduct'=>$vProduct,'parent_data'=>$parent_data,'zone_id'=>$zone_id,'categoryAds'=>$categoryAds]); }
   }

   public function productdeatils(Request $request, $id){
    if(Auth::user()){ 
      $user = Auth::user();
      $user_id = Auth::user()->id;
      $membership = $user->membership;  
      $membership_to= $user->membership_to;  
      
      $zone_id = $request->session()->get('zone_id');
      if(empty($zone)){ $zone_id = Auth::user()->zone_id;}
      $vendor_zone_id = $zone_id;
    }else{
      $membership = '';  
      $membership_to= '';
      $zone_id =0;
      $Zone= $this->zone->where('is_default',1)->first();
      if(!empty($zone)){ $zone_id = $Zone->id; }
      else{ 
        $Zonedata= $this->zone->first();
        $zone_id = $Zonedata->id;
      }
    }
    $match_in_zone = true;
    $Product_related_product=[];
    $pData = $this->vendorProduct->find($id);
    if(!empty($pData)){  
      $id = $pData->id;  
    }else{
      Session::flash('error','Sorry!!! this product not found');
      return redirect('/mycart');
    }
    if(Auth::user()){ 
      $Product =    $this->vendorProduct->with(['Product.MeasurementClass', 'Product.images', 'cart' => function ($q) use ($zone_id,$id) {
        $q->where(['user_id'=> Auth::user()->id,'zone_id' => $zone_id,'vendor_product_id' => $id]);
      },'wishList'=>function($q)  use ($id){
        $q->where(['user_id'=> Auth::user()->id,'vendor_product_id' => $id]);
      }])->findOrFail($id);
    }else{   
      $Product = $this->vendorProduct->with(['Product.MeasurementClass', 'Product.images'])->findOrFail($id);  
    }
    if(empty($Product)){ 
      Session::flash('error','Sorry!!! This product is not found in your area');
      return redirect('/profile');  
    }

    $vendor_array =$zonevendors = [];
    $vendors  = User::select('*')->whereRaw('FIND_IN_SET('.$zone_id.', zone_id)')->where(['user_type'=>'vendor'])->get();
    foreach($vendors as $vk=>$vv){
      $zonevendors[] = $vv->id;
    }
    $user_id = User::where('zone_id', $zone_id)->get()->toArray();
    $Product_related_products = $varr =$test_varr = [];
      //print_r($Product->Product->related_products); die();
    if($Product->Product){
      foreach($Product->Product->related_products as $related_product){
        $vendors = $this->vendorProduct->where('product_id',$related_product)->select('user_id')->get();
          //echo "<pre>"; print_r($vendors); die();
        foreach($vendors as $kk=>$vv){  
          $varr[] = $vv['user_id'];  
        }
        if(Auth::user()){  
          $zonevendors = $user  = User::select('*')->whereRaw('FIND_IN_SET('.Auth::user()->zone_id.', zone_id)')->where(['user_type'=>'vendor']);
          
        }else{  
          $zonevendors = $user  = User::select('*')->whereRaw('FIND_IN_SET('.$zone_id.', zone_id)')->where(['user_type'=>'vendor']); 
        }  
        $zonevendors = $zonevendors->get()->toArray();
            //print_r($zonevendors); die();
        foreach($zonevendors as $zone_kk=>$zone_vv){
          $test_varr[] = $zone_vv['id'];
        } 
        if(Auth::user()){
                //$Product_related_product = $this->product->with(['MeasurementClass','image'])->where('id',$related_product)->first();
         $Product_related_products[] = $this->vendorProduct->with(['Product.MeasurementClass', 'Product.image',
          'wishList' => function($q)
          use($related_product){
            $q->where(['user_id'=> Auth::user()->id]);
          }
        ])->where('product_id',$related_product)->first();
       }else{
        $Product_related_products[] = $this->vendorProduct->with(['Product.MeasurementClass', 'Product.image'])->where('product_id',$related_product)->first();
      }
    }        
  }
  $Product->related_productes = $Product_related_products;
  $Product->match_in_zone = $match_in_zone;
  $products = [];
  if (!empty($Product)) {
        $variantdata = $this->variant->where('product_id', $Product->product_id)->get();
        //echo "<pre>";     print_r($variantdata); die;
    $products['variantdata'] = $variantdata;
    $products['membership'] = $membership;
    $products['membership_to'] = $membership_to;
    $products['id'] = $Product->id;
    $products['user_id'] = $Product->user_id;
    $user = $this->user->where('id',$Product->user_id)->select('name')->first();
    $products['user']['name'] = $user->name;
    $products['offer_id'] = $Product->offer_id;
    $products['per_order'] = $Product->per_order;
    $products['offer'] = null;
    $products['product'] = $Product->product;
    $products['cart'] = (Auth::user())?$Product->cart:[];
    $products['wishList'] = (Auth::user())?$Product->wishList:[];
    $products['qty'] = $Product->qty;
    $products['price'] = number_format($Product->price,2,'.',''); 
    $products['mrp'] = number_format(!empty($Product->best_price) ? $Product->best_price:$Product->price,2,'.','');
    $products['offer_price'] = number_format($Product->price,2,'.',''); 
    $products['match_in_zone'] = $Product->match_in_zone;
    $products['related_products'] = array_filter($Product->related_productes);
    $products['is_offer'] = false;
    $products['memebership_p_price'] = $Product->memebership_p_price;

    $discount = ($Product->best_price - $Product->price) / $Product->best_price;
    $discount = $discount * 100;
    $products['discount'] = number_format($discount,2,'.','');
    
        //echo '<pre>'; print_r($products); echo '</pre>'; die();
        //$products['related_products'] = $related_products;
    if (!empty($Product->offer_id)) {
      $products['offer_data'] = $offer_data = $this->offer->where('id', $Product->offer_id)->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
      if(!empty($offer_data)){
        $products['is_offer'] = true;
        if ($offer_data->offer_type == 'amount') {
          $products['offer_price'] = $Product->price - $offer_data->offer_value;
        } else if ($offer_data->offer_type == 'percentages') {
          $products['offer_price'] = $Product->price - ($Product->price * ($offer_data->offer_value / 100 ));
        }
        $products['offer_price'] = number_format($products['offer_price'],2,'.','');   
      }else{
        $products['offer_price'] =  $products['price'] = number_format($Product->price,2,'.','');   
      }

      if($products['offer_price']>0) {
        $discount = ($Product->price - $products['offer_price']) / $Product->price;
      } else {
        $discount = ($Product->best_price - $Product->price) / $Product->best_price;
      }
      
      $discount = $discount * 100;
      $products['discount'] = number_format($discount,2,'.','');
    }

  }else{
    Session::flash('error','Sorry!!! This product is not found in your area');
    return redirect('/list/fruits-vegetables'); 
  }
  
      //echo "<pre>"; print_r($products); die();
  return view('pages.productdeatils', compact('products','zone_id'));
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
