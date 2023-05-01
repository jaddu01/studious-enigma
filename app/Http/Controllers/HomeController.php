<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Slider;
use App\OfferSlider;
use App\Ads;
use App\Category;
use App\CategoryTranslation;
use App\User;
use App\VendorProduct;
use App\CountryPhoneCode;
use App\Product;
use App\ProductTranslation;
use App\WishLish;
use App\ProductOrder;
use App\Offer;
use App\Zone;
use App\AppSetting;
use App\AccessLevel;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\Tempcustomers;
use App\Providers\RouteServiceProvider;
use App\ZoneTranslation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Contracts\Auth\Authenticatable;
use App;
use Illuminate\Support\Facades\DB;

use App\ProductOrderItem;

class HomeController extends Controller
{
       /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(Request $request,CategoryTranslation $category,Tempcustomers $user,Product $product,Offer $offer,VendorProduct $vendorProduct,WishLish $wishLish,ProductOrder $productOrder,ProductTranslation $ProductTranslation) 
    {
        parent::__construct();
        $this->category=$category;
        $this->user=$user; 
        $this->offer=$offer;
        $this->product=$product;
        $this->wishLish = $wishLish;
        $this->vendorProduct=$vendorProduct;
        $this->productOrder = $productOrder;
        $this->ProductTranslation = $ProductTranslation;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request){
    $category =  $this->category->join('categories','categories.id','=','category_translations.category_id')->select('categories.id','image','name','category_translations.slug')->where(['locale' => 'en'])->where(['categories.parent_id' => '0'])->where(['categories.status' => '1'])->whereNull('categories.deleted_at')->orderBy('sort_no', 'ASC')->get();
            // echo '<pre>';print_r($category);exit;
      // $ids = array('0'=>19,'1'=>227);
      $combo_offer = $this->product->with('ProductTranslation')->whereRaw('FIND_IN_SET(227, category_id) ')->get();
      //echo '<pre>';print_r($combo_offer);exit;
      $slider = Slider::with('category','sub_category','product');
      $offer_sliders = OfferSlider::with('category','sub_category','product');
      $ads = Ads::with('category','sub_category','product');
      $user = Auth::user();
     
      $zone_id = $request->session()->get('zone_id');
      if(empty($zone_id)){ 
          $zone_id = Auth::user()->zone_id;
       if(empty($zone_id)){ 
       $user->address = DeliveryLocation::where('user_id',Auth::user()->id)->first();
       if(empty($user->address->lat) || empty($user->address->lng)){
           return redirect()->route('addnewaddress')->with('success','Add Your first address address');
      }
      $zonedata = $this->getZoneData($user->address->lat, $user->address->lng);
      $zone_id =  $zonedata['zone_id'];
      $zone_name =  $zonedata['zone_name'];
      $match_in_zone = $zonedata['match_in_zone'];
      $user->zone_id = $zone_id;
      $user->save();
      $request->session()->put('zone_id',$zone_id);
      }
       }
     if(empty($zone_id)){
       $first_zone =  $Zone= Helper::Zone_list();//Zone::where('status',1)->first(); 
       $zone_id = $first_zone[0]->id;
       $request->session()->put('zone_id',$zone_id);
      }
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

      $user = $user->get()->toArray();
      $curruser = Auth::user();
      $curruser->zone_id = $zone_id;
      $curruser->update();
      $user  = User::select('*');
      $offer = $offer_arr =  [];
      $user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);
      $user = $user->get()->toArray();
      foreach($user as $kk=>$vv){
        $user_id_array[] = $vv['id'];
      }
      $user_id_array = $product_id_array=[];
      $all_offer = $this->vendorProduct->with([
              'product.MeasurementClass',
              'product.image','cart'=>function($q) use($zone_id){
                  $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
              },'wishList'=>function($q){
                  $q->where(['user_id'=>Auth::user()->id]);
              }])->whereHas('product',function($q){ $q->where('status','1'); }  )->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id')->get();
      foreach($all_offer as $key=>$value){
          $product_id_array[]=$value->product_id;
      }

      $wishProduct = [];

      $wishProduct =  $this->wishLish->where(['user_id'=>Auth::user()->id])
                    //->where(['zone_id'=>$zone_id])
                    ->has('VendorProduct')
                    ->with(['VendorProduct.Product.image'])
                    ->with(['VendorProduct.cart' => function($q) use($zone_id){$q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);}])
                    ->with(['VendorProduct.Product.MeasurementClass'])
                    ->whereHas('VendorProduct.Product',function($q){ $q->where('status','1')->whereNULL('deleted_at');  });
      $wishProduct = $wishProduct->groupBy('vendor_product_id')->orderBy('created_at','DESC')->take(10)->get();

      $data=[];
      if(!empty($wishProduct)){
        foreach ($wishProduct as $rec){
          $rec= $rec;
          $rec['VendorProduct']['price'] = number_format($rec['VendorProduct']['price'],2,'.','');
          $rec['VendorProduct']['mrp'] = number_format(!empty($rec['VendorProduct']['best_price']) ? $rec['VendorProduct']['best_price']:$rec['VendorProduct']['price'],2,'.','');   
          $rec['VendorProduct']['offer_price'] = number_format($rec['VendorProduct']['price'],2,'.','');   
         
          $rec['VendorProduct']['offer_data'] =   $ffer_data = $this->offer->where('id',$rec['VendorProduct']['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
          if(!empty($ffer_data)){
            $rec['VendorProduct']['is_offer'] = true;
            $rec['VendorProduct']['offer_id'] = $rec['VendorProduct']['offer_id'];
            if($ffer_data->offer_type=='amount'){
             $rec['VendorProduct']['offer_price'] = $rec['VendorProduct']['price']- $ffer_data->offer_value;
            }else if($ffer_data->offer_type=='percentages'){
             $rec['VendorProduct']['offer_price'] = $rec['VendorProduct']['price'] -( $rec['VendorProduct']['price'] * ( $ffer_data->offer_value / 100 )) ;                 
            }
             $rec['VendorProduct']['offer_price'] = number_format( $rec['VendorProduct']['offer_price'],2,'.','');
             $rec['VendorProduct']['mrp'] = number_format(!empty($rec['VendorProduct']['offer_price']) ? $rec['VendorProduct']['price']:$rec['VendorProduct']['best_price'],2,'.','');      
          }
          $data[]=$rec;       
        }
        unset($wishProduct);
        $wishProduct = $data; 
      }

      //echo "<pre>";print_r($wishProduct); die();
      $offerProduct = [];
      $offerProduct  =  $this->offerdata($zone_id);
      $topsellingproducts  =  $this->topsellingproducts($zone_id);
      $super_deal = Helper::superDeal($zone_id);
      $appdata =  AppSetting::first();
      $recentpurchage = [];
      if(Auth::user()){
        $recentpurchage = $this->GetRecentPurchaseItem($zone_id);
      }

      return view('home',['Category' => $category,'Slider'=>$slider,'Ads'=>$ads,'offer'=>$offerProduct,'wish_list'=>$wishProduct,'zone'=>$zone_id,'appdata'=>$appdata,'offer_sliders'=>$offer_sliders,'topsellings'=>$topsellingproducts,'super_deal'=>$super_deal, 'recentpurchage'=>$recentpurchage,'combo_offer'=>$combo_offer]);
}


public function GetRecentPurchaseItem($zone_id){
        $vp_id_array=[]; 
        $current_orders = $this->productOrder->where([['user_id',Auth::user()->id],['zone_id',$zone_id]])
         ->where(function($q){
            $q->where('order_status','D')->orwhere('order_status','C')->orwhere('order_status','R');
        })->OrderBy('id','DESC')->get();
        foreach($current_orders as $kk=>$vv){
          $vp_id_array[] = $vv['id'];
        }
        $products = $this->vendorProduct->with(['Product',
        'product.MeasurementClass',
        'product.image'])->whereHas('product',function($q){ $q->where('status','1'); })
        ->where('id',$vp_id_array)->get(); 
        return $products;
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
    $offer_product_id_array = [];
    $offer_products = $this->vendorProduct->with(['Product',
    'product.MeasurementClass',
    'product.image','cart'=>function($q) use($zone_id){
    $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
    },'wishList'=>function($q){
    $q->where(['user_id'=>Auth::user()->id]);
    }])->whereHas('product',function($q){ $q->where('status','1'); }  )
    ->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id')->get();              
    foreach($offer_products as $offer_product){
    $pffer_data = $this->offer->where('id',$offer_product->offer_id)->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
    if(!empty($pffer_data)){
    $offer_product_id_array[] = $offer_product->product_id;
    }
    }
    $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
      'cart'=>function($q) use($zone_id){
    $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
    },'wishList'=>function($q){
    $q->where(['user_id'=>Auth::user()->id]);
    }])->whereHas('product',function($q){ $q->where('status','1'); }  )
    ->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id');

    if(!empty($vendorProduct)){
      $vProduct = $vendorProduct= $vendorProduct->groupBy('product_id')->take(10000)->get();
      $vendorProduct= $vendorProduct->toArray();
    }
   $data=[];
  if(!empty($vendorProduct)){
   foreach ($vendorProduct as $rec){
    $rec= $rec;
    $rec['price'] = number_format($rec['price'],2,'.','');  
    $rec['wish_list'] = isset($rec['wish_list'])?$rec['wish_list']:'';  
    $rec['mrp'] = number_format(!empty($rec['best_price']) ? $rec['best_price']:$rec['price'],2,'.','');   
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
       $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $rec['price']:$rec['best_price'],2,'.','');   
      $data[]=$rec;                       
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


public function topsellingproducts($zone_id){
  $user = User::select('*');
  $user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
  $user = $user->get()->toArray();
  $product_data=[];
  $user_id_array=[];
  foreach($user as $kk=>$vv){
    $user_id_array[] = $vv['id'];
  }
  //print_r($user_id_array); die();

  $results = ProductOrderItem::select(DB::raw( "vendor_product_id,COUNT(id) as cnt"))->groupBy('vendor_product_id')->orderBy('cnt', 'DESC')->paginate(20)->toArray();
  $response_array = [];
  if(!empty($results['data'])){
    foreach ($results['data'] as $result) {
      $pid = $result['vendor_product_id'];
      if(Auth::guard()->user()){
        $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
          'cart'=>function($q) use($zone_id){
            $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
          },
          'wishList'=>function($q){
            $q->where(['user_id'=>Auth::user()->id]);
          }
        ])
        ->whereHas('product',function($q){ $q->where('status','1'); }  )
        ->whereIn('user_id',$user_id_array)->where('id',$pid);
        
        if(!empty($vendorProduct)){
          $vendorProduct= $vendorProduct->first();
        }
      }else{
        $vendorProduct = $this->vendorProduct->with(['product.MeasurementClass','product.image'])->where('id',$pid)->first();
      }
      /*if(!empty($vendorProduct)){
        echo $vendorProduct->price;
      }*/
      $response_array[] = $vendorProduct;

    }
    //print_r($response_array); die();
   // die();
    $data=[];
    if(!empty($response_array)){
      foreach ($response_array as $rec){
        $rec['price'] = number_format($rec['price'],2,'.','');
        $rec['wish_list'] = isset($rec['wishList'])?$rec['wishList']:''; 
        $rec['mrp'] = number_format(!empty($rec['best_price']) ? $rec['best_price']:$rec['price'],2,'.','');   
        $rec['offer_price'] = number_format($rec['price'],2,'.','');   
        if(!empty($rec['offer_id'])){
          $rec['offer_data'] =   $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
          if(!empty($ffer_data)){
            $rec['is_offer'] = true;
            $rec['offer_id'] = $rec['offer_id'];
            if($ffer_data->offer_type=='amount'){
              $rec['offer_price'] = $rec['price']- $ffer_data->offer_value;
            }else if($ffer_data->offer_type=='percentages'){
              $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 ));
            }
            $rec['offer_price'] = number_format( $rec['offer_price'],2,'.',''); 
            $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $rec['price']:$rec['best_price'],2,'.','');  
            $data[]=$rec;                       
          }  
        }    
      }
      
      unset($response_array);
      $response_array = $data; 
    }

    $data=[];
    if(!empty($response_array)){
      foreach ($response_array as $rec){
        $rec['match_in_zone']=true;
        $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
        unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
        $data[]=$rec;
      }
      unset($response_array);
      $response_array = $data; 
    }
  }

  return $response_array;
}

  public function updateZone(Request $request,$id){
        $request->session()->put('zone_id', $id);
        echo  1;
    }

    public function getZone(Request $request){
        $zone_id = $request->session()->get('zone_id');
        echo   $zone_id;
    }


 public function getZoneData($lat, $lng){
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
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', 'en')->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                return $data;
            }

            }
            $zone = Zone::where('status','=','1')->where('is_default','=',1)->withoutGlobalScope(StatusScope::class)->first();
            if(isset($zone)){
                $zone_id_default = $zone->id;
            }else{
                $zone_id_default = 24;
            }
            $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
            $data['match_in_zone'] = false;
            $data['zone_id'] = $zone_id_default;
            $data['zone_name'] = $zData->name;
            return $data;
       
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


}
